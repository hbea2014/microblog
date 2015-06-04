<?php namespace Microblog;

class User extends Model
{

    /**
     * @var int The ID of the user
     */
    private $userId;

    /**
     * @var string The username of the user
     */
    private $username;

    /**
     * @var string The email address of the user
     */
    private $email;

    /**
     * @var string The password of the user
     */
    private $password;

    /**
     * @param int $userId The ID of the user
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int The ID of the user
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $username The username of the user
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string The username of the user
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $email The email of the user
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string The email of the user
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $password The password of the user
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string The password of the user
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Populate user with data
     * 
     * @param array $row
     */
    public function populate(array $row) {
        $this->userId = $row['userId'];
        $this->username = $row['username'];
        $this->email = $row['email'];
        $this->password = $row['password'];
    }

    /**
     * Return the user data in an array
     * 
     * @return array The user data
     */
    public function toArray() {
        return [
            'userId' => $this->getUserId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword()
        ];
    }
}