<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author ABN
 */
class User extends RestModel {

    public function _setDynamicFields() {
        $this->plane = $this->Plane->count();
    }

    public function initialize() {

        $this->hasMany('id', 'Plane', 'user', array(
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

}
