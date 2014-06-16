<?php
namespace RESTFulPhalcon;

use RESTFulPhalcon\RestModel\Exception as RestModelException;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-05-26 at 15:47:11.
 */
class RestControllerTest extends \DBUnitTestCase
{
    /**
     * @var RestController
     */
    protected $object;

    /**
     * Controller's url, to address default model based on
     * @var string
     */
    protected $_url = '/plane';

    protected function getFixture(){
        return dirname(__FILE__)."/fixtures/planes.yml";
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp(\Phalcon\DiInterface $di = NULL, \Phalcon\Config $config = NULL)
    {
        $config = new \Phalcon\Config\Adapter\Ini(CONFIG_PATH);
        parent::setUp($di,$config);

        $this->object = new RestController;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers RESTFulPhalcon\RestController::onConstruct
     * @todo   Implement testOnConstruct().
     */
    public function testOnConstruct()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers RESTFulPhalcon\RestController::indexAction
     */
    public function testIndexAction()
    {

        $expectedPlanesFixture  = $this->getDataSet()->getTable('plane');

        $expectedPlanes = array();
        for($r = 0 ; $r < $expectedPlanesFixture->getRowCount(); $r++){
            $plane = $expectedPlanesFixture->getRow($r);

            $plane['created'] = date('Y-m-d H:i:s');
            $plane['updated'] = '0000-00-00 00:00:00';

            $expectedPlanes[] = $plane;
        }

        $expectedResult = json_encode($expectedPlanes);

        $expectedOutput = '{"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"GET"},"results":[{"metadata":{"status":"OK","code":200,"model":"plane","criteria":{"limit":{"number":"10","offset":"0"}},"size":10,"start":0,"total":3,"count":3},'.
            '"result":'. $expectedResult .'}]}';

        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        $this->getDatabaseTester()->onSetUp();

        $_SERVER["REQUEST_METHOD"] = "GET";
        $_SERVER["HTTP_HOST"] = "service.planeonline.local";
        $_SERVER["REQUEST_URI"] = "/plane";

        $raw = array("_url" => $this->_url);

        $this->object->setDefaultModelName('plane');
        $guessedModelName = $this->object->getDefaultModel(true);
        $this->assertEquals('plane',$guessedModelName);

        $mockRestRequest = $this->getMock("RESTFulPhalcon\RestRequest", array("getParams"));

        $mockRestRequest->expects($this->once())
            ->method("getParams")
            ->will($this->returnValue($raw));

        $this->object->setRestRequest($mockRestRequest);

        $this->object->indexAction();
        $this->expectOutputString($expectedOutput);

    }

    /**
     * @covers RESTFulPhalcon\RestController::indexAction
     */
    public function testIndexActionOnEmptyTable()
    {

        $this->truncate('plane');

        $_SERVER["REQUEST_METHOD"] = "GET";
        $_SERVER["HTTP_HOST"] = "service.planeonline.local";
        $_SERVER["REQUEST_URI"] = "/plane";

        $raw = array("_url" => $this->_url);

        $this->object->setDefaultModelName('plane');
        $guessedModelName = $this->object->getDefaultModel(true);
        $this->assertEquals('plane',$guessedModelName);

        $mockRestRequest = $this->getMock("RESTFulPhalcon\RestRequest", array("getParams"));

        $mockRestRequest->expects($this->once())
            ->method("getParams")
            ->will($this->returnValue($raw));


        $this->object->setRestRequest($mockRestRequest);

        $this->object->indexAction();
        $this->expectOutputString('{"metadata":{"url":"service.planeonline.local","endpoint":"\/plane","method":"GET"},"results":[{"metadata":{"status":"OK","code":200,"model":"plane","criteria":{"limit":{"number":"10","offset":"0"}},"size":10,"start":0,"total":0,"count":0},"result":[]}]}');

    }

    /**
     * @covers RESTFulPhalcon\RestController::postAction
     * @todo   Implement testPostAction().
     */
    public function testPostAction()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers RESTFulPhalcon\RestController::putAction
     * @todo   Implement testPutAction().
     */
    public function testPutAction()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers RESTFulPhalcon\RestController::deleteAction
     * @todo   Implement testDeleteAction().
     */
    public function testDeleteAction()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers RESTFulPhalcon\RestController::getRestResponse
     * @todo   Implement testGetRestResponse().
     */
    public function testGetRestResponse()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers RESTFulPhalcon\RestController::setRestRequest
     * @todo   Implement testSetRestRequest().
     */
    public function testSetRestRequest()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers RESTFulPhalcon\RestController::getRestRequest
     * @todo   Implement testGetRestRequest().
     */
    public function testGetRestRequest()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers RESTFulPhalcon\RestController::setDefaultModelName
     * @todo   Implement testSetDefaultModelName().
     */
    public function testSetDefaultModelName()
    {
        $this->object->setDefaultModelName('ModelX');
        $this->assertEquals('ModelX',$this->object->getDefaultModel(true));
    }

    /**
     * @covers RESTFulPhalcon\RestController::getDefaultModel
     */
    public function testGetDefaultModel()
    {
        $this->setExpectedException('RESTFulPhalcon\RestModel\Exception','Guessed model "RESTFulPhalcon\Rest" is not exists');
        $guessedModelName = $this->object->getDefaultModel(true);
        $this->assertEquals('Rest',$guessedModelName);
    }
}
