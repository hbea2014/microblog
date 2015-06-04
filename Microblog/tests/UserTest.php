<?php namespace Microblog\Test;

use Microblog\User;

class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @covers Microblog\User::populate
     */
    public function populate_sets_user_properties_correctly()
    {
        $userId = 1;
        $username = 'johndoe';
        $email = 'johndoe@mail.com';
        $password = 'secretPassword1234';
        $data = [
            'userId' => $userId,
            'username' => $username,
            'email' => $email,
            'password' => $password
        ];

        $user = new User($data);

        $this->assertEquals($userId, $user->getUserId());
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($password, $user->getPassword());
    }

    /**
     * @test
     * @covers Microblog\User::toArray
     */
    public function toArray_returns_user_properties_correctly()
    {
        $userId = 1;
        $username = 'johndoe';
        $email = 'johndoe@mail.com';
        $password = 'secretPassword1234';
        $data = [
            'userId' => $userId,
            'username' => $username,
            'email' => $email,
            'password' => $password
        ];

        $user = new User($data);
        $userDataAsArray = $user->toArray();

        $this->assertEquals($userId, $userDataAsArray['userId']);
        $this->assertEquals($username, $userDataAsArray['username']);
        $this->assertEquals($email, $userDataAsArray['email']);
        $this->assertEquals($password, $userDataAsArray['password']);
    }
}