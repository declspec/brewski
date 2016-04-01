<?php
class BrewService {
    private $_db;
    
    public function __construct($Database) {
        $this->_db = $Database;   
    }  
    
    public function create($recipeId) {
        $insertSql = 'INSERT INTO brew (recipe_id, date_created) VALUES(:recipeId, NOW())';
        $selectSql = 'SELECT 1 FROM recipe WHERE id = :recipeId';
        
        $this->_db->begin();
        
        try {
            $params = array(':recipeId' => $recipeId);
            $recipe = $this->_db->querySingle($selectSql, $params);
            
            if ($recipe === null) 
                throw new CheckedException('The recipe you have chosen does not exist.');

            $this->_db->execute($insertSql, $params);
        }
        catch(Exception $ex) {
            $this->_db->rollback();
            throw $ex;
        }
    }
    
    public function completeBrew($brewId, $initialSg) {
        $sql = 'UPDATE brew SET initial_sg = :initialSg, date_brewed = NOW(), current_step = \'bottle\' 
                WHERE id = :id';
        
        return $this->_db->execute($sql, array(':initialSg' => $initialSg, ':id' => $brewId)); 
    }
    
    public function find($id) {
        $sql = 'SELECT id, recipe_id AS recipeId, current_step AS currentStep,
                notes, initial_sg AS initialSg, final_sg AS finalSg,
                date_brewed AS dateBrewed, date_bottled AS dateBottled
                FROM brew
                WHERE id = :id'; 
       
        return $this->_db->querySingle($sql, array(':id' => $id));
    }
};