<?php namespace Microblog\Models;

use \InvalidArgumentException;

/**
 * Model abstract class
 */
abstract class Model implements ModelInterface
{
    /**
     * @var array The parameters required to populate the model
     */
    protected $required = [];

    /**
     * Constructor
     * 
     * @param null|array $data
     */
    public function __construct($data = null)
    {
        if (null !== $data) {
            $this->populate($data);
        }
    }

    /**
     * Populates the model with row data
     *
     * @param array $row
     * @return $this
     */
    abstract public function populate(array $row);

    /**
     * Converts the model to an array
     *
     * @return array
     */
    abstract public function toArray();

    /**
     * Checks if the given array has the required parameters (ie. keys)
     * 
     * @param array $row The array to be checked
     * @return boolean
     * @throws InvalidArgumentException
     * @todo Is it logical to do this here? Is it necessary to have this check
     * in all models?
     */
    protected function hasRequiredParams($row)
    {
        foreach ($this->required as $param) {
            if ( !array_key_exists($param, $row) ) {
                throw new InvalidArgumentException('Missing required parameter "' . $param . '".');
            }
        }

        return true;
    }
}

