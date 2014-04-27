<?php

use RESTFulPhalcon\RestController;

/**
 * Description of PlaneController
 *
 * @author ABN
 */
class PlaneController extends RestController {

    //put your code here

    public function indexAction() {

        $params = $this->getRestRequest()->getParams();

        var_dump($params, __FILE__ . ' : ' . __LINE__);
    }

}
