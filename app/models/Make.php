<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Make
 *
 * @author ABN
 */
class Make extends RestModel{
 
    public function initialize()
    {
        $this->hasMany('id', 'plane', 'make', array(
            'foreignKey' => array(
                'message' => 'Make cannot be deleted because it\'s used on Aircrafts'
            )
        ));
           
        $this->_setSkips();
        
    }
        
    protected function _setSkips() {
        $this->skipAttributesOnCreate(array('lastupdated'));
        $this->skipAttributesOnUpdate(array('lastupdated'));
    }
    
    public function _setDynamicFields() {
        $this->plane = $this->Plane->count();
    }
    
}
