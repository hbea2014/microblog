<?php namespace Microblog\Db;

use \RuntimeException;
use Microblog\Models\Model;

/**
 * Mapper class
 */
abstract class Mapper
{

    /**
     * Fetches the DbTable
     * 
     * @return \Microblog\Db\DbTable
     * @throws RuntimeException
     */
    public function getDbTable()
    {
        if (null === $this->dbTable) {
            throw new RuntimeException('DbTable was not set.');
        }

        return $this->dbTable;
    }

    /**
     * Finds results matching a given primary key
     * 
     * @param \Microblog\Models\Model $model
     * @param string $value The value of the key
     * @param string $primaryKey The name of the key, set to 'id' by default
     * @see DbTable::find()
     * @return false|array An array containing the objects matching the criteria,
     * false on failure
     */
    public function find(Model $model, $value, $primaryKey = 'id')
    {
        $result = $this->getDbTable()->find($value, $primaryKey);
        $resultSet = [];

        if ($result) {
            foreach ($result as $row) {
                $obj = clone $model;
                $resultSet[] = $obj->populate($row);
            }
    
            return $resultSet;
        }

        return false;

    }

    /**
     * Finds a single occurance
     * 
     * @param \Microblog\Models\Model $model
     * @param null|string $where
     * @param null|string $order
     * @see DbTable::findRow()
     * @return false|\Microblog\Models\Model The model on success, false on failure
     */
    public function findRow(Model $model, $where = null, $order = null)
    {
        $result = $this->getDbTable()->findRow($where, $order);

        if ($result) {
            $model->populate($result);
            return $model;
        }

        return false;
    }

    /**
     * Finds all results matching given optional conditions
     * 
     * @param \Microblog\Models\Model $model
     * @param null|string $where
     * @param null|string $order
     * @param null|string $limit
     * @param null|string $offset
     * @see DbTable::findAll()
     * @return false|array
     */
    public function findAll(Model $model, $where = null, $order = null, $limit = null, $offset = null)
    {
        $result = $this->getDbTable()->findAll($where, $order, $limit, $offset);
        $resultSet = [];

        if ($result) {
            foreach ($result as $row) {
                $obj = clone $model;
                $resultSet[] = $obj->populate($row);
                //die(var_dump($row, $obj));
            }

            return $resultSet;
        }

        return false;
    }

    /**
     * Updates one or more rows
     * 
     * @param \Microblog\Models\Model $model
     * @param string $set
     * @param string $where
     * @see DbTable::update()
     */
    public function update(Model $model, $set, $where)
    {
        $this->getDbTable()->update($set, $where);
    }

    /**
     * Deletes one or more rows
     * 
     * @param \Microblog\Models\Model $model
     * @param null|string $where
     * @see DbTable::delete()
     */
    public function delete(Model $model, $where = null)
    {
        $this->getDbTable()->delete($where);
    }

    /**
     * Inserts data
     * 
     * @param \Microblog\Models\Model $model
     * @param string $columnNames
     * @param string $values
     * @see DbTable::insert()
     */
    public function insert(Model $model, $columnNames, $values)
    {
        $this->getDbTable()->insert($columnNames, $values);
    }

}