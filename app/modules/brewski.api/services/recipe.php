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
    
    public function create($recipe) {
        // start the transaction
        $this->_db->begin();
        
        try {
            $params = self::buildParams($recipe, array('id'));
            $this->_db->execute(self::$InsertSql, $params);
            $recipe->id = $this->_db->getLastInsertId();
            
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
    
    private static function buildParams($recipe, array $ignoredFields=null) {
        $params = array(
            ':parentId' => self::nvl($recipe, 'parentId'),
            ':name' => $recipe->name,
            ':description' => $recipe->description,
            ':notes' => self::nvl($recipe, 'notes'),
            ':estimated_time' => nvl($recipe, 'estimatedTime'),
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