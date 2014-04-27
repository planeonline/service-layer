<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Image
 *
 * @author ABN
 */
class Image extends RestModel {

    public function initialize() {
        $this->belongsTo('plane', 'Plane', 'id');

        $this->_setSkips();
    }

    protected function _setSkips() {
        $this->skipAttributesOnCreate(array('lastupdated'));
        $this->skipAttributesOnUpdate(array('lastupdated'));
    }

}
