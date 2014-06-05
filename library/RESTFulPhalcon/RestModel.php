<?php
/**
 * Copyright 2014 Takeaway IT Ltd.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP version 5.4
 *
 * @category RESTFul_API
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */

namespace RESTFulPhalcon;

use Phalcon\Mvc\Model\Exception;
use RESTFulPhalcon\RestModel\Validator;

/**
 * Class RestModel
 *
 * PHP version 5.4
 *
 * @category RESTFul_API
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */
class RestModel extends \Phalcon\Mvc\Model
{

    protected $validators;

    const STATUS_PENDING = null;
    const STATUS_DELETED = 0;
    const STATUS_LOCKED = 1;
    const STATUS_ACTIVE = 2;

    /**
     * Tis method will receive an array and validated its values
     * against available validator
     * Validator(s) has to be define in your model's class
     * implementing getValidators abstract method
     *
     * @param array|null $data array of key/value pairs to be validate
     *
     * @return bool
     */
    public function validation($data = null)
    {

        if (!method_exists($this, 'getValidators')) {
            return true;
        }

        if (!is_null($data)) {
            $this->assign($data);
        }

        foreach ($this->getValidators() as $validator) {
            $this->validate($validator);
        }

        return $this->validationHasFailed() != true;
    }


    /**
     * This is a wrapper for the phalcon's original create method
     * to allow us to treat potential Exceptions in manner which suits our goal
     *
     * @param array $data      array of key/value pairs to be created
     * @param array $whiteList list of fields that are white listed
     *
     * @return bool
     */
    public function create($data = null, $whiteList = null)
    {

        if ($this->validation()) {
            try {
                $result = parent::create($data, $whiteList);
                return $result;
            } catch (Exception $e) {
                print_r($e->getMessage());
                die();
            }
        } else {
            return false;
        }
    }

    /**
     * Overwriting this instead of pahlcon's model constructor
     *
     * @throws \Phalcon\Mvc\Model\Exception
     *
     * @return null
     */
    public function initialize()
    {
        try {
            $this->setSkips();
        } catch (Exception $e) {

            $pattern = "doesn't exist on database when dumping meta-data for";
            $addendum = '<br> You might forgot to run the migration ,
                for more detail check <a href=\'
                https://github.com/planeonline/service-layer/wiki/Migration\'>
                https://github.com/planeonline/service-layer/wiki/Migration</a>';
            if (strpos($e->getMessage(), $pattern)) {
                throw new Exception(
                    $e->getMessage() . $addendum,
                    $e->getCode(),
                    $e->getPrevious()
                );
            }
        }
    }

    /**
     * To define auto generating fields during insert and/or update
     *
     * @return null
     */
    protected function setSkips()
    {
        $this->skipAttributesOnCreate(array('created'));
        $this->skipAttributesOnCreate(array('updated'));
        $this->skipAttributesOnUpdate(array('updated'));
    }

}
