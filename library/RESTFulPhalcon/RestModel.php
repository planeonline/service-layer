<?php

namespace RESTFulPhalcon;

use Phalcon\Mvc\Model\Validator\Exclusionin;

/**
 * Description of RestModel
 *
 * @author Ali Bahman <abn@webit4.me>
 */
class RestModel extends \Phalcon\Mvc\Model {

    protected $_validators;

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

    public function validation($data = null) {

        if (!is_null($data)) {
            $this->assign($data);
        }

        foreach ($this->getValidators() as $validator) {
            $this->validate($validator);
        }

        return $this->validationHasFailed() != true;
    }

    public function create($data = null, $whiteList = null) {

        if ($this->validation()) {
            try {
                parent::create($data, $whiteList);
                return true;
            } catch (Exception $e) {
                print_r($e->getMessage());
                die();
            }
        } else {
            return false;
        }
    }

}
