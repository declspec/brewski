<?php
class RecipeService {
    private $_db;
    
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
    
    public function save($recipe) {
        $sql = 'INSERT INTO recipe (parent_id, name, description, notes, estimated_time, date_created) 
                VALUES (:parentId, :name, :description, :notes, :estimatedTime, NOW())';
          
        $this->_db->begin();

        try {
            $this->_db->execute($sql, array(
                ':parentId' => self::nvl($recipe, 'parentId'),
                ':name' => $recipe['name'],
                ':description' => $recipe['description'],
                ':notes' => self::nvl($recipe, 'notes'),
                ':estimatedTime' => self::nvl($recipe, 'estimatedTime')
            ));
            
            // Save the newly inserted ID but don't update the model just yet
            $newRecordId = $this->_db->getLastInsertId();
            
            // Create the steps/ingredients
            $this->createIngredients($newRecordId, $recipe['ingredients']);
            $this->createSteps($newRecordId, $recipe['steps']);
            
            // If there is an existing record, update it and all old versions to point
            // to the new record. This allows people to still refer to the old recipe,
            // but find the latest copy if they want.
            if (isset($recipe['id'])) {
                $sql = 'UPDATE recipe SET next_version_id = :id WHERE id = :oldId OR next_version_id = :oldId';
                $this->_db->execute($sql, array(':id' => $newRecordId, ':oldId' => $recipe['id']));
            }
                
            $this->_db->commit();
            $recipe['id'] = $newRecordId;
            return $recipe;
        } 
        catch(Exception $ex) {
            $this->_db->rollback();
            throw $ex; // not our problem
        }  
    }
    
    private function findRecipe($id) {
        $sql = 'SELECT c.id, c.name, c.description,
                c.parent_id AS parentId, c.notes, 
                c.estimated_time AS estimatedTime, 
                c.next_version_id AS nextVersionId,
                c.date_created AS dateCreated, 
                p.name AS parentName, n.name AS nextVersionName
             FROM recipe c
             LEFT JOIN recipe p ON p.id = c.parent_id
             LEFT JOIN recipe n ON n.id = c.next_version_id
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
    
    private static function nvl($obj, $key, $defaultValue=null) {
        return array_key_exists($key, $obj) ? $obj[$key] : $defaultValue;
    }
};