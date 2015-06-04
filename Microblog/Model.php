<?php namespace Microblog;

abstract class Model
{

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
    abstract public function populate($row);

    /**
     * Converts the model to an array
     *
     * @return mixed
     */
    abstract public function toArray();
}

