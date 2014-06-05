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

use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Criteria;
use RESTFulPhalcon\RestRequest\RestCriteria;

/**
 * Class RestRequest
 *
 * PHP version 5.4
 *
 * @category RESTFul_API
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */
class RestRequest extends Request
{

    protected $criteria;

    protected $expands;

    protected $params;

    /**
     * Class constructor
     *
     * @param null|array $params list of received parameters
     *
     * @return null
     */
    public function __construct($params = null)
    {

        if (is_null($params)) {
            $this->initParams();
        } else {
            $this->setParams($params);
        }
    }

    /**
     * To instantiate a rest criteria object
     *
     * @return null
     */
    protected function initCriteria()
    {
        $this->criteria = new RestCriteria($this);

        // $this->_criteria->where("id = :id:");
        // $this->_criteria->inWhere('id', [1, 2, 3]);
        // $this->_initCriteriaOrders();
    }

    /**
     * To set this request's criteria's order
     *
     * @return null
     */
    protected function initCriteriaOrders()
    {
        $order = $this->params['order'];
        $this->criteria->orderBy($order);
    }

    /**
     * Setter method for this request's criteria
     *
     * @param null|RESTFulPhalcon/RestCriteria $criteria a Criteria to
     * be attached to this this request
     *
     * @return null
     */
    public function setCriteria($criteria = null)
    {

        if (is_null($criteria)) {
            $this->initCriteria();
        } else {
            $this->criteria = $criteria;
        }

    }

    /**
     * Getter method for this request's criteria
     *
     * @return RESTFulPhalcon/RestCriteria
     */
    public function getCriteria()
    {
        if (is_null($this->criteria)) {
            $this->setCriteria();
        }

        return $this->criteria;
    }

    /**
     * To initialise request's expands parameters
     *
     * @return null
     */
    protected function initExpands()
    {
        $this->expands = array();
    }

    /**
     * Setter method for this request's expands
     * It will receive and string and extract expandable fields from it
     *
     * @param string $expands   List of expandable fields
     * @param bool   $recursive To define if it requires recursive action
     *
     * @return array
     */
    public function setExpands($expands, $recursive = true)
    {

        $paths = explode(',', $expands);
        if (!$recursive) {
            return $paths;
        }
        $expand = array();
        foreach ($paths as $path) {
            $parts = explode('/', $path);
            $e = & $expand;
            while (count($parts) > 0) {
                $part = array_shift($parts);
                $part = str_replace(';', ',', $part);
                if (!isset($e[$part])) {
                    $e[$part] = array();
                }


                $e = & $e[$part];
            }
        }

        $this->expands = $expand;

    }

    /**
     * Getter method for this request's expand params
     *
     * @return array
     */
    public function getExpands()
    {
        if (is_null($this->expands)) {
            $this->initExpands();
        }
        return $this->expands;
    }

    /**
     * To gather appropriate set of parameters based on the received
     * request's method and send it to be set
     *
     * @return null
     */
    protected function initParams()
    {
        switch ($this->getMethod()) {
        case 'GET':
                $params = $this->getQuery();
            break;
        case 'POST' || 'PUT':
                $params = (array)$this->getJsonRawBody();
            break;
        default:
                $params = array();
        }

        $this->setParams($params);
    }

    /**
     * Setter method for this request's params
     *
     * @param array $params parameters to set
     *
     * @return null
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Getter method for this request's params
     *
     * @param bool $publicOnly to indicate if we need to hide general prams
     * e.g. _url
     *
     * @return array
     */
    public function getParams($publicOnly = false)
    {
        if (is_null($this->params)) {
            $this->initParams();
        }

        $params = $this->params;

        if ($publicOnly) {
            array_walk(
                $params,
                function (&$value, $key) use (&$params) {
                    if (substr($key, 0, 1) == '_') {
                        unset($params[$key]);
                    }
                }
            );
        }

        return $params;

    }

}
