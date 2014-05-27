<?php

namespace RESTFulPhalcon;

use Phalcon\Mvc\Controller,
    RESTFulPhalcon\RestRequest as RestRequest;

/**
 * A RESTFul implementation of 
 *
 * @author Ali Bahman abn@webit4.me
 */
class RestController extends Controller {

    /**
     *
     * @var array 
     */
    protected $_restRequest;

    /**
     *
     * @var RestResponse 
     */
    protected $_restResponse;

    /**
     *
     * @var string 
     */
    protected $_defaultModelName;

    public function onConstruct() {
        
    }

    public function indexAction() {
        $this->_setHeader();
        
        $genericModel = $this->getDefaultModel();

        
        $result = new Response\RestResponseResult($this->getRestRequest()->getMethod());
        $result->setModel($this->getDefaultModel(true));
        $result->setCriteria($this->getRestRequest()->getCriteria());
        
        try{
            
            $params = $this->getRestRequest()->getCriteria()->getParams();
            
            $models = $genericModel->find($params);

            $result->setTotal($genericModel->count($params));
            $result->setCount(count($models));
            $result->setResult($models->toArray());
            
            
            $result->setCode(200);
            $result->setStatus('OK');
        }  catch (\Exception $e){
            
            $result->setResult([$e->getMessage()]);

            $result->setCode(400);
            $result->setStatus('Bad request');
        }
        $this->getRestResponse()->addResult($result);
                
        echo $this->getRestResponse();
//        die();        

    }

    public function postAction() {

        $this->_setHeader();

        $request = $this->getRestRequest();

        $dataSet = $request->getParams(true);

        if (!is_array($dataSet)) {
            $dataSet = array($dataSet);
        }

        foreach ($dataSet as $data) {
            $model = $this->getDefaultModel();

            $model->assign((array) $data);            
            
            $result = new Response\RestResponseResult($this->getRestRequest()->getMethod());
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

    public function putAction() {

        $this->_setHeader();

        $modelName = $this->getDefaultModel(true);
        
        $request = $this->getRestRequest();

        $dataSet = $request->getParams(true);

        if (!is_array($dataSet)) {
            $dataSet = array($dataSet);
        }
                
        foreach ($dataSet as $data) {
            
            $result = new Response\RestResponseResult($this->getRestRequest()->getMethod());
            
            $result->setModel($modelName);
                
            if(!isset($data->id)){
                
            }else{
                
               $genericModel = $this->getDefaultModel(); 
               $model = $genericModel->findFirst("id = $data->id");
               
               foreach($data as $field => $value){
                   
                  if($field == 'id') continue;
                                 
                  
                  if(!isset($model->$field)){
                    $result->setCode("400");
                    $result->setStatus('bad request');
                    $result->setResult(["Field $field is not exists in $modelName model"]); 
                    continue(2);
                  }else{
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
    
    public function deleteAction() {

        $this->_setHeader();

        $modelName = $this->getDefaultModel(true);
        
        $request = $this->getRestRequest();

        $dataSet = $request->getParams(true);

        if (!is_array($dataSet)) {
            $dataSet = array($dataSet);
        }
                
        foreach ($dataSet as $data) {
            
            $result = new Response\RestResponseResult($this->getRestRequest()->getMethod());
            
            $result->setModel($modelName);
                
            if(isset($data->id)){
                
                $genericModel = $this->getDefaultModel(); 
                $model = $genericModel->findFirst("id = $data->id");
               
                if($model){
                    
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
                }else{
                        $result->setCode("404");
                        $result->setStatus('Not Found');
                        $result->setResult(["There is no $modelName with id $data->id avilable"]);
                }
               
            }
            $this->getRestResponse()->addResult($result);
        }

        echo $this->getRestResponse();
        die();
    }
    
    protected function _setHeader() {
        //@header('Content-Type: application/json');
    }

    /**
     * To initiate a new RestRequest to RestController::_restRequest
     */
    protected function _initRestRequest() {
        $this->setRestRequest(new RestRequest());
    }

    /**
     * 
     * @return RestResponse
     */
    public function getRestResponse() {
        
        if (is_null($this->_restResponse)) {
            $this->_restResponse = new RestResponse(
                    $this->getRestRequest()->getHttpHost(),
                    $this->getRestRequest()->getURI(),
                    $this->getRestRequest()->getMethod()
                    );            
        }

        return $this->_restResponse;
    }

    /**
     * 
     * @param \RESTFulPhalcon\RestRequest $request
     */
    public function setRestRequest(RestRequest $request) {
        $this->_restRequest = $request;
    }

    /**
     * 
     * @param boolean $init if true initiate new instance even if its already initiated
     * @return RestRequest
     */
    public function getRestRequest($init = false) {

        if (is_null($this->_restRequest) || $init) {
            $this->_initRestRequest();
        }
        return $this->_restRequest;
    }

    /**
     * 
     * @return string
     */
    protected function _guessModelName() {

        $className = get_class($this);
        $modelName = str_replace('Controller', '', $className);
        
        if (!class_exists($modelName)) {
            throw new Exception("Guessed model \"$modelName\" is not exists");
        }
        
        return $modelName;
    }

    /**
     * 
     * @param ResModel string $name
     */
    public function setDefaultModelName($name) {
        $this->_defaultModelName = $name;
    }

    /**
     * @param boolean $nameOnly if true reternthe model's name as string
     * @return ResModel
     */

    /**
     * 
     * @param type $nameOnly
     * @return \RESTFulPhalcon\REstModel
     */
    public function getDefaultModel($nameOnly = false) {
        if (is_null($this->_defaultModelName)) {
            $this->setDefaultModelName($this->_guessModelName());
        }
        if ($nameOnly) {
            return $this->_defaultModelName;
        } else {
            return new $this->_defaultModelName;
        }
    }

    
    protected function _unsetModel(){
        $this->_defaultModelName = null;
    }
}
