<?php

use RESTFulPhalcon\RestRequest;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-05-15 at 19:06:09.
 */
class RestRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RestRequest
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new RestRequest;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->object);
    }

    /**
     * @covers RESTFulPhalcon\RestRequest::setCriteria
     * @todo   Implement testSetCriteria().
     */
    public function testSetCriteria()
    {
        $this->object->setCriteria('asd');
        $this->assertEquals('asd', $this->object->getCriteria());
    }

    /**
     * @covers RESTFulPhalcon\RestRequest::getCriteria
     * @todo   Implement testGetCriteria().
     */
    public function testGetCriteria()
    {
        $this->assertInstanceOf('RESTFulPhalcon\RestRequest\RestCriteria', $this->object->getCriteria());
    }

    /**
     * @covers RESTFulPhalcon\RestRequest::setExpands
     * @todo   Implement testSetExpands().
     */
    public function testSetExpands()
    {
        $this->object->setExpands('make,user/title,engine');
        $expectedExpandArray = array(
            'make' => array(),
            'user' => array(
                'title' => array()
            ),
            'engine' => array(),
        );
        $this->assertEquals($expectedExpandArray, $this->object->getExpands());
    }

    /**
     * @covers RESTFulPhalcon\RestRequest::getExpands
     * @todo   Implement testGetExpands().
     */
    public function testGetExpands()
    {
        $this->assertEmpty($this->object->getExpands());
    }

    /**
     * @covers RESTFulPhalcon\RestRequest::setParams
     * @todo   Implement testSetParams().
     */
    public function testSetParams()
    {
        $this->assertEmpty($this->object->getParams());
        $this->object->setParams('param');
        $this->assertEquals('param', $this->object->getParams());
    }

    /**
     * @covers RESTFulPhalcon\RestRequest::getParams
     * @todo   Implement testGetParams().
     */
    public function testGetParams()
    {
        $this->assertEmpty($this->object->getParams());
        $_SERVER["REQUEST_METHOD"] = "GET";
        $_GET['_url'] = '/plane';
        $_GET['user'] = '[12,23,45]';
        $_GET['make'] = '|asd';
        $_GET['title'] = 'asd';
        $_GET['model'] = '4';
        $_GET['description'] = '5';
        $_GET['lastupdated'] = '14-05-01';
        $_GET['status'] = '7';
        $_GET['order'] = '[id desc,name,test]';
        $this->setUp();

        $params = ['_url' => '/plane',
            'user' => '[12,23,45]',
            'make' => '|asd',
            'title' => 'asd',
            'model' => '4',
            'description' => '5',
            'lastupdated' => '14-05-01',
            'status' => '7',
            'order' => '[id desc,name,test]',
            ];

        $this->assertEquals($params,$this->object->getParams());
        unset($params['_url']);
        $this->assertEquals($params,$this->object->getParams(true));


    }
}
