<?php

use RESTFulPhalcon\Model\Validator,
    Phalcon\Mvc\Model\Validator\StringLength;

class PlaneValidator extends Validator {
    
    public function addValidators() {
        $this->add(new StringLength(array(
            'field'=> 'title', 
            'max' => 45,
            'min' => 4,
            'messageMaximum' => 'We don\'t like really long names',
            'messageMinimum' => 'Minimum number of charcters for title is 4')));
    }

}