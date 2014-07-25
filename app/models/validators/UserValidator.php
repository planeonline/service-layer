<?php

use RESTFulPhalcon\RestModel\Validator,
    Phalcon\Mvc\Model\Validator\StringLength,
    Phalcon\Mvc\Model\Validator\Numericality;

class UserValidator extends Validator {
    
    public function addValidators() {

        $this->add(new StringLength(array(
            'field'=> 'description',
            'min' => 3,
            'messageMaximum' => 'We don\'t like really long names',
            'messageMinimum' => 'Minimum number of characters for description is 10')));

    }

}