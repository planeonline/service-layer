<?php
use Phalcon\DI,
    Phalcon\DI\FactoryDefault,
    Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter,
    \Phalcon\Test\DBUnitTestCase as DBPhalconTestCase;

abstract class DBUnitTestCase extends DBPhalconTestCase {

    /**
     * @var \Voice\Cache
     */
    protected $_cache;

    /**
     * @var \Phalcon\Config
     */
    protected $config;

    static private $pdo = null;

    protected $conn;

    /**
     * @var bool
     */
    private $_loaded = false;

    public function setUp(Phalcon\DiInterface $di = NULL, Phalcon\Config $config = NULL) {

        $this->config = $config;
        // Load any additional services that might be required during testing
        $di = DI::getDefault();

        $di = new FactoryDefault();  
        // get any DI components here. If you have a config, be sure to pass it to the parent
        
        $di->set('db', function() use ($config) {
            return new DbAdapter(array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname
            ));
        });
        
        //parent::setUp($di);



        $this->_loaded = true;
        
    }

    /**
     * Check if the test case is setup properly
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct() {
        if(!$this->_loaded && 1==2) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }
    }


    /**
     * @return PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    public function getConnection(){

        if ($this->conn === null) {

            if (self::$pdo == null) {

                $dsn = 'mysql:host=localhost;dbname=' . $this->config->database->dbname ;

                self::$pdo = new PDO( $dsn, $this->config->database->username, $this->config->database->password );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $this->config->database->dbname);
        }

        return $this->conn;

    }

    abstract protected function getFixture();

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet|PHPUnit_Extensions_Database_DataSet_YamlDataSet
     */
    protected function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            $this->getFixture()
        );
    }

    protected function truncate($tableName){
        $this->getConnection()->getConnection()->exec("TRUNCATE $tableName");
    }
}