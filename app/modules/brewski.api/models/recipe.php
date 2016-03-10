<?php
require_once(__DIR__ . '/../../../vendor/modelr/Model.php');
require_once(__DIR__ . '/../../../vendor/modelr/Schema.php');

class RecipeModel extends Model {
    protected static $_schema = null;
    
    protected function getSchema() {
        if (self::$_schema !== null)
            return self::$_schema;
            
        return (self::$_schema = Schema::compile(array(
            'fields' => array(
                'id' => 'Recipe Id',
                'name' => 'Recipe name',
                'description' => 'Description',
                'ingredients' => 'Ingredients',
                'steps' => 'Steps',
                'notes' => 'Notes',
                'estimatedTime' => 'Estimated completion time'
            ),
            "rules" => array(
                array(
                    'validator' => 'required',
                    'fields' => array('name','description','steps','ingredients')
                ),
                array(
                    'validator' => 'length',
                    'fields' => 'name',
                    'args' => array('max' => 256)
                ),
                array(
                    'validator' => 'length',
                    'fields' => 'estimatedTime',
                    'args' => array('max' => 64)
                ),
                array(
                    'validator' => 'length',
                    'fields' => 'notes',
                    'args' => array('max' => 2048)
                )
            )
        )));
    }
};