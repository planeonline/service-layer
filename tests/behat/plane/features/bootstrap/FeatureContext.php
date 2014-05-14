<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{

    protected $_records;

    protected $_debug = true;

    protected $_output;    

    protected $_params;

    /**
     * Initializes context.
     *
     * Every scenario gets it's own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct()
    {
    	$this->_params = $parameters;
    }

    /**
     * @Given having the following list:
     */
    public function havingTheFollowingList(TableNode $table)
    {
        $this->_records = json_encode($table->getHash());  
    }

    /**
     * @When I curl it as json using :method to :url
     */
    public function iCurlItAsJsonUsingTo($method, $url)
    {
        // $url = str_replace('http://sl-env.planeonline.net', $this->getParam('curl_url'), $url);
        
        $cmd = 'curl -H "Accept: application/json" -H "Content-type: application/json" -X ' . $method;
        $cmd .= " -d '$this->_records' ";
        $cmd .= $url;

        if ($this->_debug) {
            print_r($cmd);
        }

        exec($cmd, $this->_output);
                
        
    }

    /**
     * @Then I should get:
     */
    public function iShouldGet(PyStringNode $string)
    {
        $output = $this->_replaceDynamicFieldValues((string) $this->_output[0]);
        if ($output !== (string) $string->getRaw()) {
            throw new Exception("Actual output is:\n" . $output);
        }
    }

    protected function getParam($name){
        return $this->_params[$name];
    }
    
    protected function _replaceDynamicFieldValues($content){
        
        $pattern = '/"id":"\d+"/'; // "id":"30"
        $replacement = '"id":"???"';
        $result = preg_replace($pattern, $replacement, $content);
        
        return $result;
    }
}
