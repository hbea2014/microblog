<?php namespace Microblog\Db;

use \ArrayObject;
use \Exception;
use \InvalidArgumentException;
use \PDO;
use \PDOException;
use \RuntimeException;
use Microblog\Config;

abstract class DbTable
{

    /**
     * @var array The required parameters
     */
    protected $required = ['host', 'dbname', 'username', 'password'];

    /**
     * @var Microblog\Config The configuration for the db table
     */
    protected $config;

    /**
     * @var string The name of the table
     */
    protected $tableName;

    /**
     * @var \PDO The db adapter
     */
    protected $dbAdapter;

    /**
     * Constructor
     * 
     * @param null|array $config
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);
    }

    /**
     * Checks if the config contains all the required parameters
     * 
     * @param \Microblog\Config $config
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function hasRequiredParameters(Config $config)
    {
        foreach ($this->required as $param) {
            if ( ! $config->get($param) ) {
                throw new InvalidArgumentException('Missing required parameter "'. $param . '".');
            }
        }

        return true;
        
    }

    /**
     * Sets the configuration class
     * 
     * @param \Microblog\Config $config
     */
    public function setConfig(Config $config)
    {
        if ($this->hasRequiredParameters($config)) {
            $this->config = $config;
        }
    }

    /**
     * Retrieves a given parameter from the configuration
     * 
     * @param string $param
     * @return string
     */
    public function getConfigValue($param)
    {
        return $this->config->get($param);
    }

    /**
     * Sets up the connection
     * 
     * @return \Microblog\DB\DbTable
     * @throws RuntimeException
     * @throws Exception
     * @todo Is it a good place to set the charset? Should it be set in the 
     * config array instead (utf8 is pretty standard so I thought it could 
     * belong here)?
     */
    protected function setConnection()
    {
        // Data source name
        $dsn = sprintf('mysql:dbname=%s;host=%s;charset=utf8', 
            $this->getConfigValue('mysql/dbname'), $this->getConfigValue('mysql/host'));


        // PDO
        try {
            $pdo = new PDO($dsn, $this->getConfigValue('mysql/username'), $this->getConfigValue('mysql/password'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->dbAdapter = $pdo;

            return $this;
        } catch (PDOException $e) {
            throw new Exception('Conntection error ("' . $e->getMessage() . '").');
        }
    }

    /**
     * Returns the connection resource
     * 
     * @return \PDO
     */
    protected function getConnection()
    {
        if (null === $this->dbAdapter) {
            $this->setConnection();
        }

        return $this->dbAdapter;
    }

    /**
     * Finds results matching a given primary key
     * 
     * @param null|string $value
     * @param null|string $primaryKey Set to 'id' by default
     * @return false|array Returns the result in an array, false on failure
     * @throws Exception
     */
    public function find($value, $primaryKey = 'id')
    {
        $stmt = $this->getConnection()->prepare(sprintf(
            'SELECT * FROM %s WHERE %s = ?', $this->tableName, $primaryKey));

        try {
            $stmt->execute($value);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e) {
            throw new Exception('Error executing statement ("' 
                . $e->getMessage() . '").');
        }
    }

    /**
     * Finds a single occurance
     * 
     * @param null|string $where
     * @param null|string $order
     * @return false|array Returns the result in an array, false on failure
     * @throws Exception
     */
    public function findRow($where = null, $order = null)
    {
        $sql = sprintf('SELECT * FROM %s', $this->tableName);

        if (null !== $where) {
            $sql .= ' WHERE ' . $where;
        }

        if (null !== $order) {
            $sql .= ' ORDER BY ' . $order;
        }

        $stmt = $this->getConnection()->prepare($sql);
            
        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e) {
            throw new Exception('Error executing statement ("' 
                . $e->getMessage() . '").');
        }
    }

    /**
     * Finds all results matching given optional conditions
     * 
     * @param null|string $where
     * @param null|string $order
     * @param null|integer $limit
     * @param null|integer $offset
     * @return false|array Returns the result in an array, false on failure
     * @throws Exception
     */
    public function findAll($where = null, $order = null, $limit = null, 
        $offset = null)
    {
        $sql = sprintf('SELECT * FROM %s', $this->tableName);

        if (null !== $where) {
            $sql .= ' WHERE ' . $where;
        }

        if (null !== $order) {
            $sql .= ' ORDER BY ' . $order;
        }

        if (null !== $limit) {
            if (null === $offset) {
                $offset = 0;
            }

            $sql .= ' LIMIT ' . $limit . ',' . $offset;
        }

        $stmt = $this->getConnection()->prepare($sql);
            
        try {
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch(PDOException $e) {
            throw new Exception('Error executing statement ("' 
                . $e->getMessage() . '").');
        }
    }

    /**
     * Updates one or more rows
     * 
     * @param string $set
     * @param string $where
     * @throws Exception
     */
    public function update($set, $where)
    {
        $sql = sprintf('SELECT * FROM %s', $this->tableName);

        if (null === $set) {
            throw new Exception('Missing column(s) to be modified and their corresponding value(s).');
        } else {
            $sql .= ' SET ' . $set;
        }

        if (null !== $where) {
            $sql .= ' WHERE ' . $where;
        }

        $stmt = $this->getConnection()->prepare($sql);

        try {
            $stmt->execute();
        } catch(PDOException $e) {
            throw new Exception('Error executing statement ("' 
                . $e->getMessage() . '").');
        }
    }

    /**
     * Deletes one or more rows
     * 
     * @param null|string $where
     * @throws Exception
     */
    public function delete($where = null)
    {
        $sql = sprintf('DELETE FROM %s', $this->tableName);

        if (null !== $where) {
            $sql .= ' WHERE ' . $where;
        }

        $stmt = $this->getConnection()->prepare($sql);

        try {
            $stmt->execute();
        } catch(PDOException $e) {
            throw new Exception('Error executing statement ("' 
                . $e->getMessage() . '").');
        }
    }

    /**
     * Inserts data in the table
     * 
     * @param string $columnNames
     * @param string $values
     * @throws Exception
     */
    public function insert($columnNames, $values)
    {
        $sql = sprintf('INSERT INTO %s', $this->tableName);

        if (null === $columnNames) {
            throw new Exception('Missing column names for data to be inserted in.');
        } else {
            if (null === $values) {
                throw new Exception('Missing values to be inserted');
            } else {
                $sql .= sprintf(' (%s) VALUES (%s)', $columnNames, $values);
            }
        }

        $stmt = $this->getConnection()->prepare($sql);
        
        try {
            $stmt->execute();
        } catch(PDOException $e) {
            throw new Exception('Error executing statement ("' 
                . $e->getMessage() . '").');
        }
    }
}