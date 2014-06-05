<?php

use RESTFulPhalcon\RestModel\Validator,
    Phalcon\Mvc\Model\Validator\StringLength,
    Phalcon\Mvc\Model\Validator\Numericality;

class PlaneValidator extends Validator {
    
    public function addValidators() {

        $this->add(new Numericality(array(
            'field'=> 'make')));

        $this->add(new StringLength(array(
            'field'=> 'make',
            'max' => 11,
            'min' => 1,
            'messageMaximum' => 'Maximum length for make id is (11)',
            'messageMinimum' => 'Minimum length for make id is (1)')));

        $this->add(new StringLength(array(
            'field'=> 'title',
            'max' => 45,
            'min' => 4,
            'messageMaximum' => 'We don\'t like really long names',
            'messageMinimum' => 'Minimum number of characters for title is 4')));

        $this->add(new StringLength(array(
            'field'=> 'description',
            'min' => 10,
            'messageMaximum' => 'We don\'t like really long names',
            'messageMinimum' => 'Minimum number of characters for description is 10')));

        $this->add(new StringLength(array(
            'field'=> 'description',
            'min' => 5,
            'messageMaximum' => 'We don\'t like really long names',
            'messageMinimum' => 'Minimum number of characters for description is 5')));
    }

}