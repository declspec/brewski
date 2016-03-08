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
            notes = :notes, estimated_time = :estimated_time,
            date_modified = NOW()
         WHERE id = :id';
        
    public function __construct($Database) {
        $this->_db = $Database;
    }  
    
    public function find($id) {
        $recipe = $this->findRecipe($id);
        
        if (!$recipe)
            return null;
            
        $recipe->ingredients = $this->findIngredients($recipe->id);  
        $recipe->steps = $this->findSteps($recipe->id);
        
        return $recipe;   
    }
    
    public function create($recipe) {
        // start the transaction
        $this->_db->begin();
        
        try {
            $params = self::buildParams($recipe, array(':id'));

            $this->_db->execute(self::$InsertSql, $params);
            $recipe->id = $this->_db->getLastInsertId();
            
            
            
            $this->_db->commit();
            return $recipe;
        }
        catch(Exception $ex) {
            $this->_db->rollback();
            throw $ex; // not our problem
        }
    }
    
    public function save($recipe) {
        
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
    
    private function findSteps($recipeId) {
        $sql = 'SELECT s.content FROM recipe_step s WHERE s.recipe_id = :recipeId ORDER BY s.step_order';
        return $this->_db->query($sql, array(':recipeId' => $recipeId));
    }
    
    private static function buildParams($recipe, array $ignoredFields=null) {
        $params = array(
            ':parentId' => self::nvl($recipe, 'parentId'),
            ':name' => $recipe->name,
            ':description' => $recipe->description,
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
        return isset($obj->$key) ? $obj->$key : $defaultValue;
    }
};