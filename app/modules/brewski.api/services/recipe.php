<?php
class RecipeService {
    private $_db;
    
    private static $InsertSql = 
        'INSERT INTO recipe (
            parent_id, name, description, 
            notes, estimated_time, date_created
        ) 
        VALUES (
            :parentId, :name, :description, 
            :notes, :estimatedTime, NOW()
        )';
    
    private static $UpdateSql = 
        'UPDATE recipe SET 
            name = :name, description = :description,
            notes = :notes, estimated_time = :estimatedTime,
            date_modified = NOW()
         WHERE id = :id';
        
    public function __construct($Database) {
        $this->_db = $Database;
    }  
    
    public function find($id) {
        $recipe = $this->findRecipe($id);
        
        if (!$recipe)
            return null;
            
        $recipe['ingredients'] = $this->findIngredients($recipe['id']);  
        $recipe['steps'] = $this->findSteps($recipe['id']);
        
        return $recipe;   
    }
    
    public function create($recipe) {
        $this->_db->begin();
        
        try {
            $params = self::buildParams($recipe, array(':id'));

            $this->_db->execute(self::$InsertSql, $params);
            $recipe['id'] = $this->_db->getLastInsertId();
            
            $this->createIngredients($recipe['id'], $recipe['ingredients']);
            $this->createSteps($recipe['id'], $recipe['steps']);
            
            $this->_db->commit();
            return $recipe;
        }
        catch(Exception $ex) {
            $this->_db->rollback();
            throw $ex; // not our problem
        }
    }
    
    public function update($recipe) {
        $this->_db->begin();
        
        try {
            // Update the recipe
            $params = self::buildParams($recipe, array(':parentId'));
            $this->_db->execute(self::$UpdateSql, $params);
            
            // Delete the steps + ingredients
            $deleteSql = 
               'DELETE s.*, i.* 
                FROM recipe_step s, recipe_ingredient i 
                WHERE s.recipe_id = i.recipe_id AND s.recipe_id = :recipeId';
            
            $this->_db->execute($deleteSql, array(':recipeId' => $recipe['id']));
            
            // Create the new steps + ingredients
            $this->createIngredients($recipe['id'], $recipe['ingredients']);
            $this->createSteps($recipe['id'], $recipe['steps']);
            
            // Commit and finish
            $this->_db->commit();
            return $recipe;
        }
        catch(Exception $ex) {
            $this->_db->rollback();
            throw $ex; // not our problem
        } 
    }
    
    private function validate($recipe) {
           
    }
    
    private function findRecipe($id) {
        $sql = 'SELECT c.id, c.name, c.description,
                c.parent_id AS parentId, c.notes, 
                c.estimated_time AS estimatedTime, 
                c.date_created AS dateCreated, p.name AS parentName
             FROM recipe c
             LEFT JOIN recipe p ON p.id = c.parent_id
             WHERE c.id = :id';

        return $this->_db->querySingle($sql, array(':id' => $id));
    }
    
    private function findIngredients($recipeId) {
        $sql = 'SELECT i.quantity, i.description FROM recipe_ingredient i WHERE recipe_id = :recipeId';
        return $this->_db->query($sql, array(':recipeId' => $recipeId)); 
    }
    
    private function createIngredients($recipeId, array $ingredients) {
        $sql = 'INSERT INTO recipe_ingredient(recipe_id, quantity, description) VALUES (:recipeId, :quantity, :description)';
        foreach($ingredients as $ing) {
            $params = array(':recipeId' => $recipeId, ':quantity' => $ing['quantity'], ':description' => $ing['description']);
            $this->_db->execute($sql, $params, false); // turn off emulate to optimize the prepared statement  
        }
    }
    
    private function findSteps($recipeId) {
        $sql = 'SELECT s.content FROM recipe_step s WHERE s.recipe_id = :recipeId ORDER BY s.step_order';
        return $this->_db->query($sql, array(':recipeId' => $recipeId));
    }
    
    private function createSteps($recipeId, array $steps) {
        $sql = 'INSERT INTO recipe_step(recipe_id, content, step_order) VALUES(:recipeId, :content, :i)';
        for($i = 0, $j = count($steps); $i < $j; ++$i) {
            $params = array(':recipeId' => $recipeId, ':content' => $steps[$i]['content'], ':i' => $i);
            $this->_db->execute($sql, $params, false);
        }
    }
    
    private static function buildParams($recipe, array $ignoredFields=null) {
        $params = array(
            ':parentId' => self::nvl($recipe, 'parentId'),
            ':name' => $recipe['name'],
            ':description' => $recipe['description'],
            ':notes' => self::nvl($recipe, 'notes'),
            ':estimatedTime' => self::nvl($recipe, 'estimatedTime'),
            ':id' => self::nvl($recipe, 'id')
        );
        
        if ($ignoredFields !== null) {
            foreach($ignoredFields as $field)
                unset($params[$field]);   
        }
        
        return $params;
    }
    
    private static function nvl($obj, $key, $defaultValue=null) {
        return array_key_exists($key, $obj) ? $obj[$key] : $defaultValue;
    }
};