<?php

use RESTFulPhalcon\RestModel;
use Phalcon\Mvc\Model\Validator\Numericality as NumericalityValidator;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Plane
 *
 * @author ABN
 */
class Plane extends RestModel{
        
    protected function columnMap()
    {
        return array(
            'id' => 'id',
            'user' => 'user',
            'make' => 'make',
            'title' => 'title',
            'model' => 'model',
            'description' => 'description',
            'lastupdated' => 'lastupdated',
            'status' => 'status',
            'image' => 'image'
        );
    }
    
    public function _setDynamicFields()
    {
        $this->image = $this->Image->count();
    }
    
    public function initialize()
    {   
        $this->belongsTo('make', 'Make', 'id');        
        $this->belongsTo('user', 'User', 'id');        
        $this->hasMany('id', 'Image', 'plane', array(
            'foreignKey' => array(
                'message' => 'Plane cannot be deleted because it has Images'
            )
        ));
        
        $this->_setSkips();
        
    }
    
    
    protected function _setSkips() {
        $this->skipAttributesOnCreate(array('lastupdated'));
        $this->skipAttributesOnUpdate(array('lastupdated'));
    }


    /**
     *
     * @return Validation
     */
    public function getValidators() {

        if (is_null($this->_validators)) {
            $this->_validators = new \PlaneValidator();
        }

        return $this->_validators;
    }
    
    
}
