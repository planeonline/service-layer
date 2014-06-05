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

namespace RESTFulPhalcon\RestModel;

/**
 * Class Validator
 *
 * PHP version 5.4
 *
 * @category RESTFul_API
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */
abstract class Validator implements \Iterator
{

    /**
     * @var string
     */
    protected $modelName;

    /**
     * @var string
     */
    protected $validators;

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addValidators();
    }

    /**
     * Needs to be implemented in the child class
     * to handel adding new validators
     *
     * @return mixed
     */
    abstract public function addValidators();

    /**
     * Returns model's name that the validator is belong to
     *
     * @return string
     */
    protected function getModelName()
    {
        if (is_null($this->modelName)) {
            $this->modelName = str_replace('Validator', '', get_class($this));
        }
        return $this->modelName;
    }

    /**
     * Adding provided validator to the list
     *
     * @param Phalcon\Validation\Validator\* $validator Any phalconPHP validator
     * available in Phalcon\Validation\Validator\ namespace
     *
     * @return null;
     */
    Public function add($validator)
    {
        $this->validators[] = $validator;
    }


    /**
     * Get All available messages
     *
     * @return array list of all validation messages gathered during process
     */
    public function getMessages()
    {
        $messages = array();

        if (!is_null($this->validators)) {
            foreach ($this->validators as $validator) {

                $validatorMessages = array();
                if (!is_null($validator->getMessages())) {
                    foreach ($validator->getMessages() as $message) {
                        $validatorMessages[$message->getField()]
                            = $message->getMessage();
                    }
                }
                $messages[] = $validatorMessages;
            }
        }

        return array_values(array_filter($messages));
    }

    /**
     * Implemented method in the Iterator's interface
     *
     * @return Phalcon\Validation\Validator\*
     */
    public function current()
    {
        return $this->validators[$this->index];
    }

    /**
     * Implemented method in the Iterator's interface
     *
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Implemented method in the Iterator's interface
     *
     * @return null
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * Implemented method in the Iterator's interface
     *
     * @return null
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Implemented method in the Iterator's interface
     *
     * @return bool
     */
    public function valid()
    {
        return (isset($this->validators[$this->index]));
    }


}
