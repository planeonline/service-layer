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

use RESTFulPhalcon\RestResponse\RestResponseResult;

/**
 * Class RestResponse
 *
 * PHP version 5.4
 *
 * @category RESTFul_API
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */
class RestResponse
{

    /**
     * To hold response's metadata for time being
     * It might need to convert to an object itself
     *
     * @var array
     */
    protected $metadata = array(
        "url" => null,
        "endpoint" => null,
        "method" => null,
        "results" => 0,
        "success" => 0,
        "failed" => 0
    );


    /**
     * A container for the fetched results
     *
     * @var array
     */
    protected $results = array();

    /**
     * Constructor
     *
     * @param null|string $url      the API's url
     * @param null|string $endpoint the path responding to the call
     * @param null|string $method   Any of restful accepted methods
     */
    public function __construct($url = null, $endpoint = null, $method = null)
    {
        if (!is_null($url)) {
            $this->setUrl($url);
        }
        if (!is_null($endpoint)) {
            $this->setEndPoint($endpoint);
        }
        if (!is_null($method)) {
            $this->setMethod($method);
        }
    }

    /**
     * Sets metadata's URL
     *
     * @param string $url the API's url
     *
     * @return null
     */
    public function setUrl($url)
    {
        $this->metadata['url'] = $url;
    }

    /**
     * Gets metadata's URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->metadata['url'];
    }

    /**
     * Sets metadata's End-point
     *
     * @param string $endpoint the path responding to the call
     *
     * @return null
     */
    public function setEndpoint($endpoint)
    {
        $endpoint = explode('?', $endpoint)[0];
        $this->metadata['endpoint'] = $endpoint;
    }

    /**
     * Gets metadata's End-point
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->metadata['endpoint'];
    }

    /**
     * Adds a result to this response's result-set
     *
     * @param RestResponseResult $result An individual result
     *
     * @return null
     */
    public function addResult(RestResponseResult $result)
    {
        $this->results[] = $result;
        $this->calculateCounts($result);

        if (is_null($this->getMethod())) {
            $this->setMethod($result->getMethod());
        }
    }

    /**
     * Returns all results after converting them to an array
     *
     * @return array
     */
    public function getResults()
    {
        $results = array();
        foreach ($this->results as $result) {
            $results[] = $result->toArray();
        }
        return $results;
    }

    /**
     * Sets metadata's method and based on that
     * include or exclude some metadata fields
     * i.e. removing status of the post, pot or delete functionality from a get
     *
     * @param string $method Any of restful accepted methods
     * e.g. GET | POST | PUT | DELETE
     *
     * @return null
     */
    public function setMethod($method)
    {
        $this->metadata['method'] = $method;

        if ($method == 'GET') {
            unset($this->metadata['results']);
            unset($this->metadata['success']);
            unset($this->metadata['failed']);
        } else {
            $this->metadata['results'] = 0;
            $this->metadata['success'] = 0;
            $this->metadata['failed'] = 0;
        }
    }

    /**
     * Returns metadata's method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->metadata['method'];
    }

    /**
     * Calculate and update counters on this response
     * e.g. how many success, failed and in total results are there
     *
     * @param RestResponseResult $result An individual result
     *
     * @return null
     */
    protected function calculateCounts(RestResponseResult $result)
    {

        if (!isset($this->metadata['success'])
            || !isset($this->metadata['failed'])
            || !isset($this->metadata['results'])
        ) {
            return;
        }

        if ($result->isSuccess()) {
            $this->metadata['success'] = $this->metadata['success'] + 1;
        } else {
            $this->metadata['failed'] = $this->metadata['failed'] + 1;
        }

        $this->metadata['results'] = $this->metadata['failed'] +
            $this->metadata['success'];
    }

    /**
     * To convert this object its equivalent JSON
     *
     * @return string
     */
    public function __toString()
    {

        $resultSet = array(
            'metadata' => $this->metadata,
            'results' => $this->getResults(true)
        );

        return json_encode($resultSet);
    }

}
