<?php namespace Microblog\Db;

use \RuntimeException;
use Microblog\Models\Model;

/**
 * Mapper class
 */
class UserMapper extends Mapper
{

    /**
     * @var Microblog\Db\UserSessionTable
     */
    protected $dbTable;

    /**
     * Constructor
     * 
     * @param null|Microblog\Db\DbTable $dbTable
     */
    public function __construct(UserTable $dbTable)
    {
        $this->setDbTable($dbTable);
    }

    /**
     * Sets the DbTable
     * 
     * @param \Microblog\Db\UserSessionTable $dbTable
     * @return \Microblog\Db\Mapper
     * in Bootstrap.php)
     * @see Bootstrap.php
     * @see Microblog\Db\Mapper
     * @todo Fix the inheritance here. I wanted to type hint (see the injection
     */
    public function setDbTable(UserTable $dbTable)
    {
        $this->dbTable = $dbTable;
        return $this;
    }

}