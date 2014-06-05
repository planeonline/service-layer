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

use Phalcon\Mvc\Controller,
    RESTFulPhalcon\RestRequest as RestRequest,
    RESTFulPhalcon\RestResponse\RestResponseResult;

/**
 * Class RestController
 *
 * PHP version 5.4
 *
 * @category RESTFul_API
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */
class RestController extends Controller
{

    /**
     *
     * @var array
     */
    protected $restRequest;

    /**
     *
     * @var RestResponse
     */
    protected $restResponse;

    /**
     *
     * @var string
     */
    protected $defaultModelName;

    /**
     * To response to a GET request
     *
     * @return null
     */
    public function indexAction()
    {
        $this->setHeader();

        $genericModel = $this->getDefaultModel();


        $result = new RestResponseResult($this->getRestRequest()->getMethod());
        $result->setModel($this->getDefaultModel(true));
        $result->setCriteria($this->getRestRequest()->getCriteria());

        try {

            $params = $this->getRestRequest()->getCriteria()->getParams();

            $models = $genericModel->find($params);

            $result->setTotal($genericModel->count($params));
            $result->setCount(count($models));
            $result->setResult($models->toArray());


            $result->setCode(200);
            $result->setStatus('OK');
        } catch (\Exception $e) {

            $result->setResult([$e->getMessage()]);

            $result->setCode(400);
            $result->setStatus('Bad request');
        }
        $this->getRestResponse()->addResult($result);

        echo $this->getRestResponse();

    }

    /**
     * To response to a POST request and create model(s)
     *
     * @return null
     */
    public function postAction()
    {

        $this->setHeader();

        $request = $this->getRestRequest();

        $dataSet = $request->getParams(true);

        if (!is_array($dataSet)) {
            $dataSet = array($dataSet);
        }

        foreach ($dataSet as $data) {
            $model = $this->getDefaultModel();

            $model->assign((array)$data);

            $result = new RestResponseResult($this->getRestRequest()->getMethod());
            $result->setModel($this->getDefaultModel(true));
            if ($model->create()) {
                $result->setCode("201");
                $result->setStatus('created');
                $result->setResult($model->dump());
            } else {
                $result->setCode("400");
                $result->setStatus('bad request');
                $result->setResult($model->getValidators()->getMessages());
            }
            $this->getRestResponse()->addResult($result);
        }

        echo $this->getRestResponse();
        die();
    }

    /**
     * To response to a PUT request and update model(s)
     *
     * @return null
     */
    public function putAction()
    {

        $this->setHeader();

        $modelName = $this->getDefaultModel(true);

        $request = $this->getRestRequest();

        $dataSet = $request->getParams(true);

        if (!is_array($dataSet)) {
            $dataSet = array($dataSet);
        }

        foreach ($dataSet as $data) {

            $result = new RestResponseResult($this->getRestRequest()->getMethod());

            $result->setModel($modelName);

            if (!isset($data->id)) {

            } else {

                $genericModel = $this->getDefaultModel();
                $model = $genericModel->findFirst("id = $data->id");

                foreach ($data as $field => $value) {

                    if ($field == 'id') {
                        continue;
                    }


                    if (!isset($model->$field)) {
                        $result->setCode("400");
                        $result->setStatus('bad request');
                        $result->setResult(
                            ["Field $field is not exists in $modelName model"]
                        );
                        continue(2);
                    } else {
                        $model->$field = $value;
                    }
                }

                if ($model->save()) {
                    $result->setCode("200");
                    $result->setStatus('updated');
                    $result->setResult($model->dump());
                } else {
                    $result->setCode("400");
                    $result->setStatus('bad request');
                    $result->setResult($model->getValidators()->getMessages());
                }

            }
            $this->getRestResponse()->addResult($result);
        }

        echo $this->getRestResponse();
        die();
    }

    /**
     * To response to a DELETE request and remove model(s)
     *
     * @return null
     */
    public function deleteAction()
    {

        $this->setHeader();

        $modelName = $this->getDefaultModel(true);

        $request = $this->getRestRequest();

        $dataSet = $request->getParams(true);

        if (!is_array($dataSet)) {
            $dataSet = array($dataSet);
        }

        foreach ($dataSet as $data) {

            $result = new RestResponseResult($this->getRestRequest()->getMethod());

            $result->setModel($modelName);

            if (isset($data->id)) {

                $genericModel = $this->getDefaultModel();
                $model = $genericModel->findFirst("id = $data->id");

                if ($model) {

                    $model->status = RestModel::STATUS_DELETED;

                    if ($model->save()) {
                        $result->setCode("200");
                        $result->setStatus('deleted');
                        $result->setResult($model->dump());
                    } else {
                        $result->setCode("400");
                        $result->setStatus('bad request');
                        $result->setResult($model->getValidators()->getMessages());
                    }
                } else {
                    $result->setCode("404");
                    $result->setStatus('Not Found');
                    $result->setResult(
                        ["There is no $modelName with id $data->id avilable"]
                    );
                }

            }
            $this->getRestResponse()->addResult($result);
        }

        echo $this->getRestResponse();
        die();
    }

    /**
     * To set the response header
     *
     * @return null
     */
    protected function setHeader()
    {
        @header('Content-Type: application/json');
    }

    /**
     * To initiate a new RestRequest to RestController::_restRequest
     *
     * @return null
     */
    protected function initRestRequest()
    {
        $this->setRestRequest(new RestRequest());
    }

    /**
     * To prepare (if needed) and return a rest response
     *
     * @return RestResponse
     */
    public function getRestResponse()
    {

        if (is_null($this->restResponse)) {
            $this->restResponse = new RestResponse(
                $this->getRestRequest()->getHttpHost(),
                $this->getRestRequest()->getURI(),
                $this->getRestRequest()->getMethod()
            );
        }

        return $this->restResponse;
    }

    /**
     * Setter method for the rest response
     *
     * @param \RESTFulPhalcon\RestRequest $request a rest request to be set
     *
     * @return \RESTFulPhalcon\RestRequest
     */
    public function setRestRequest(RestRequest $request)
    {
        $this->restRequest = $request;
    }

    /**
     * Getter method for the rest request
     *
     * @param boolean $init if true initiate new instance
     * even if its already initiated
     *
     * @return RestRequest
     */
    public function getRestRequest($init = false)
    {

        if (is_null($this->restRequest) || $init) {
            $this->initRestRequest();
        }
        return $this->restRequest;
    }

    /**
     * This method will try to get the corresponding model name
     *
     * @throws Exception
     *
     * @return string
     */
    protected function guessModelName()
    {

        $className = get_class($this);
        $modelName = str_replace('Controller', '', $className);

        if (!class_exists($modelName)) {
            throw new Exception("Guessed model \"$modelName\" is not exists");
        }

        return $modelName;
    }

    /**
     * Setter method for the default model's name
     *
     * @param string $name The model name
     *
     * @return null
     */
    public function setDefaultModelName($name)
    {
        $this->defaultModelName = $name;
    }

    /**
     * Getter method for the default name or an instance of it
     * If the default method is not set manually it will try to guess its name
     *
     * @param type $nameOnly if true return the model's name as string
     *
     * @return \RESTFulPhalcon\REstModel|string
     */
    public function getDefaultModel($nameOnly = false)
    {
        if (is_null($this->defaultModelName)) {
            $this->setDefaultModelName($this->guessModelName());
        }
        if ($nameOnly) {
            return $this->defaultModelName;
        } else {
            return new $this->defaultModelName;
        }
    }

    /**
     * To unset the default model's name
     *
     * @return null
     */
    protected function unsetModel()
    {
        $this->defaultModelName = null;
    }
}
