<?php namespace Microblog\Models;

class UserSession extends Model
{

    /**
     * @var integer The ID of the user session
     */
    private $id;

    /**
     * @var integer The ID of the user
     */
    private $userId;

    /**
     * @var string The hash for the given user
     */
    private $hash;

    /**
     * @param integer The ID of the user session
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return integer The ID of the user session
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer The user ID of the user session
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return integer The user ID of the user session
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param integer The hash of the user session
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return integer The hash of the user session
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Populates the user session with data
     * 
     * @param array $row The row data to populate the user session with
     */
    public function populate(array $row)
    {
        if ( $this->hasRequiredParams($row) ) {
            $this->setId($row['id']);
            $this->setUserId($row['userId']);
            $this->setHash($row['hash']);
        }

        return $this;
    }

    /**
     * Return the user session as an array
     * 
     * @return array The user session data
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'hash' => $this->getHash()
        ];
    }
}