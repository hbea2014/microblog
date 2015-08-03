<?php namespace Microblog\Models;

class User extends Model
{

    /**
     * @var int The ID of the user
     */
    private $id;

    /**
     * @var string The username of the user
     */
    private $username;

    /**
     * @var string The password of the user
     */
    private $password;

    /**
     * @var string The password salt of the user
     */
    private $salt;

    /**
     * @var string The email address of the user
     */
    private $email;

    /**
     * @param int $userId The ID of the user
     */
    public function setId($Id)
    {
        $this->id = $id;
    }

    /**
     * @return int The ID of the user
     */
    public function getId()
    {
        return $this->id;
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
     * @param string $salt The password salt of the user
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string The password salt of the user
     */
    public function getSalt()
    {
        return $this->salt;
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
     * @param string $joined The date when the user joined
     */
    public function setJoined($joined)
    {
        $this->joined = $joined;
    }

    /**
     * @return string $joined The date when the user joined
     */
    public function getJoined()
    {
        return $this->joined;
    }

    /**
     * Populate user with data
     * 
     * @param array $row
     */
    public function populate(array $row) {
        $this->id = $row['id'];
        $this->username = $row['username'];
        $this->password = $row['password'];
        $this->salt = $row['salt'];
        $this->email = $row['email'];
        $this->joined = $row['joined'];
    }

    /**
     * Return the user data in an array
     * 
     * @return array The user data
     */
    public function toArray() {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'salt' => $this->getSalt(),
            'email' => $this->getEmail(),
            'joined' => $this->getJoined()
        ];
    }
}