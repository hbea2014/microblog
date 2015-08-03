<?php namespace Microblog\Db;

use \RuntimeException;
use Microblog\Models\Model;

/**
 * Mapper class
 */
class PageMapper extends Mapper
{

    /**
     * @var Microblog\Db\PageTable
     */
    protected $dbTable;

    /**
     * Constructor
     * 
     * @param null|Microblog\Db\DbTable $dbTable
     */
    public function __construct(PageTable $dbTable)
    {
        $this->setDbTable($dbTable);
    }

    /**
     * Sets the DbTable
     * 
     * @param \Microblog\Db\PageTable $dbTable
     * @return \Microblog\Db\Mapper
     * in Bootstrap.php)
     * @see Bootstrap.php
     * @see Microblog\Db\Mapper
     * @todo Fix the inheritance here. I wanted to type hint (see the injection
     */
    public function setDbTable(PageTable $dbTable)
    {
        $this->dbTable = $dbTable;
        return $this;
    }

}