<?php

interface UserDetailInterface
{
    public function getName(): string;
    public function getEmail(): string;
}

class Candidate implements UserDetailInterface
{
    private $name;
    private $email;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function getName(): string
    {
        return 'Candidate with name: ' . $this->name;
    }

    public function getEmail(): string
    {
        return 'Candidate with email: ' . $this->email;
    }
}

class Employer implements UserDetailInterface
{
    private $name;
    private $email;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function getName(): string
    {
        return 'Employer with name: ' . $this->name;
    }

    public function getEmail(): string
    {
        return 'Employer with email: ' . $this->email;
    }
}

interface UserCreateInterface
{
    public static function create($type, $name, $email);
}

class UserCreateFactory implements UserCreateInterface
{
    public static function create($type, $name, $email)
    {
        switch ($type) {
            case 'Candidate':
                return new Candidate($name, $email);
                break;
            case 'Employer':
                return new Employer($name, $email);
                break;
            default:
                throw new Exception('This user role is not valid');
        }
    }
}

$user1 = UserCreateFactory::create('Candidate', 'behroz', 'behroz@gmail.com');
echo $user1->getName();
echo ' - ';
echo $user1->getEmail();

echo '<br />';

$user2 = UserCreateFactory::create('Employer', 'hamid', 'hamid@gmail.com');
echo $user2->getName();
echo ' - ';
echo $user2->getEmail();

// Output:
// Candidate with name: behroz - Candidate with email: behroz@gmail.com
// Employer with name: hamid - Employer with email: hamid@gmail.com