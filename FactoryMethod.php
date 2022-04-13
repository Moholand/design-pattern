<?php
// User interface
interface UserDetailInterface
{
    public function getName(): string;
    public function getEmail(): string;
}

// User classes - with Admin - Candidate - Employer Roles
class Admin implements UserDetailInterface
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
        return 'Admin with name: ' . $this->name;
    }

    public function getEmail(): string
    {
        return 'Admin with email: ' . $this->email;
    }
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

// Interface for factory class
interface UserCreateInterface
{
    public static function create($type, $name, $email);
}

// Factory class for create user
class UserCreateFactory implements UserCreateInterface
{
    public static function create($type, $name, $email)
    {
        switch ($type) {
            case 'Admin':
                return new Admin($name, $email);
                break;
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

// Client code:
$users = [];

array_push($users, UserCreateFactory::create('Admin', 'sara', 'sara@gmail.com'));
array_push($users, UserCreateFactory::create('Candidate', 'behroz', 'behroz@gmail.com'));
array_push($users, UserCreateFactory::create('Employer', 'hamid', 'hamid@gmail.com'));

foreach($users as $user) {
    echo $user->getName();
    echo ' - ';
    echo $user->getEmail();

    echo '<br />';
}

// Output:
// Admin with name: sara - Admin with email: sara@gmail.com
// Candidate with name: behroz - Candidate with email: behroz@gmail.com
// Employer with name: hamid - Employer with email: hamid@gmail.com