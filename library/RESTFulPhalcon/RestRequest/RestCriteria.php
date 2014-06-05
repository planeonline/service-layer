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

namespace RESTFulPhalcon\RestRequest;

use Phalcon\Mvc\Model\Criteria;

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
class RestCriteria extends Criteria
{

    protected $restRequest;
    protected $originalParameters;
    protected $whereParams;

    protected $defaultStart = "0";
    protected $defaultSize = "10";

    protected $reservedFields = array('_url', 'order', 'expand', 'start', 'size');
    /**
     * Constructor
     *
     * @param RESTFulPhalcon\RestRequest $restRequest
     * the received rest request from the call
     */
    public function __construct($restRequest)
    {

        $this->restRequest = $restRequest;
        $this->originalParameters = $restRequest->getParams(true);

        $this->processOrders();
        $this->processWheres();
        $this->processLimit();

    }

    /**
     * Processing required where clause
     *
     * @return null;
     */
    protected function processWheres()
    {
        $this->processSingularWheres();
        $this->processInWheres();
        $this->processBetweenWheres();
    }

    /**
     * Processing required limit clause
     *
     * @return null;
     */
    protected function processLimit()
    {
        $offset = isset($this->originalParameters['start'])
            ? $this->originalParameters['start'] : $this->defaultStart;
        $limit = isset($this->originalParameters['size'])
            ? $this->originalParameters['size'] : $this->defaultSize;

        $this->limit($limit, $offset);
    }

    /**
     * To construct a single where clause
     *
     * @return null
     */
    protected function processSingularWheres()
    {

        $singularConditions = array();
        foreach ($this->getWhereParamsOnly() as $key => $value) {
            if (strpos($value, '[') === false) {
                $singularConditions[$key] = $value;
            }
        }

        foreach ($singularConditions as $key => $value) {

            if (substr($value, 0, 1) == '|') {
                $this->processOrWhere($key, substr($value, 1));
            } else {
                $this->processAndWhere($key, $value);
            }

        }

    }


    /**
     * Attaches a condition to the where clause using AND
     *
     * @param string $key   column's name
     * @param string $value keyword to search
     *
     * @return null
     */
    protected function processAndWhere($key, $value)
    {
        $condition = $this->buildCondition($key, $value);
        $this->andWhere($condition);
    }

    /**
     * Attaches a condition to the where clause using OR
     *
     * @param string $key   column's name
     * @param string $value keyword to search
     *
     * @return null
     */
    protected function processOrWhere($key, $value)
    {
        $condition = $this->buildCondition($key, $value);
        $this->orWhere($condition);
    }

    /**
     * Builds string sql conditions based on the received operator in the request
     * the oprator will be the very first character after (=) sign
     * in the query string e.g.
     * user=25   => user = 25
     * user=<25  => user < 25
     * user=>25  => user > 25
     * user=!25  => user <> 25
     *
     * @param string $key   column's name
     * @param string $value keyword to search
     *
     * @return null
     */
    protected function buildCondition($key, $value)
    {

        $condition = $key;
        switch (substr($value, 0, 1)) {
        case '!':
            $condition .= ' <> "' . substr($value, 1) . '"';
            break;
        case '<':
            $condition .= ' < "' . substr($value, 1) . '"';
            break;
        case '>':
            $condition .= ' > "' . substr($value, 1) . '"';
            break;
        default:
            $condition .= ' = "' . $value . '"';
        }

        return $condition;

    }

    /**
     * Or normal key,value paires in the string query
     * e.g.
     * user IN (23, 2, 10)
     *
     * @return null
     */
    protected function processInWheres()
    {

        $multipleConditions = array();
        foreach ($this->getWhereParamsOnly() as $key => $value) {
            if (strpos($value, '[') !== false
                && !preg_match('/\-/', $value)
            ) {
                $multipleConditions[$key] = $value;
            }
        }

        foreach ($multipleConditions as $key => $value) {
            preg_match('/\[(.*)\]/', $value, $trimedValue);
            $trimedValue = $trimedValue[1];
            if (substr($value, 0, 1) == '!') {
                $items = explode(',', $trimedValue);
                $this->notInWhere($key, $items);
            } else {
                $items = explode(',', $trimedValue);
                $this->inWhere($key, $items);
            }

        }

    }

    /**
     * Or normal key,value paires in the string query
     * e.g.
     * user BETWEEN 10 AND 15
     *
     * @return null
     */
    protected function processBetweenWheres()
    {

        $multipleConditions = array();
        foreach ($this->getWhereParamsOnly() as $key => $value) {
            if (strpos($value, '[') !== false
                && preg_match('/\-/', $value)
            ) {
                $multipleConditions[$key] = $value;
            }
        }

        foreach ($multipleConditions as $key => $value) {
            preg_match('/\[(.*)\]/', $value, $trimedValue);
            if (substr($value, 0, 1) == '!') {
                $items = explode('-', $trimedValue);
                $this->notBetweenWhere($key, $items[0], $items[1]);
            } else {
                $items = explode('-', $trimedValue[1]);
                $this->betweenWhere($key, $items[0], $items[1]);
            }

        }
    }

    /**
     * Returns only condition's important parameter
     * By ignoring reserved fields
     *
     * @return array
     */
    protected function getWhereParamsOnly()
    {

        if (is_null($this->whereParams)) {
            $this->whereParams = array();

            foreach ($this->originalParameters as $key => $value) {
                if (!in_array($key, $this->reservedFields)) {
                    $this->whereParams[$key] = $value;
                }
            }
        }

        return $this->whereParams;
    }

    /**
     * Prepares sql's order clause
     *
     * @return null
     */
    protected function processOrders()
    {

        $order = isset($this->originalParameters['order'])
            ? $this->originalParameters['order'] : false;

        $pattern = "/\[(.*?)\]/";

        if (preg_match($pattern, $order, $matches)) {
            $order = $matches[1];
        }

        if ($order !== false) {
            $this->orderBy($order);
        }
    }

}
