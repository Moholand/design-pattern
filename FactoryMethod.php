<?php
// User interface
interface UserDetailInterface
{
    public function setName(string $name): void;
    public function setEmail(string $email): void;
    public function getName(): string;
    public function getEmail(): string;
}

// User classes - with Admin - Candidate - Employer Roles
class Admin implements UserDetailInterface
{
    private $name;
    private $email;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
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
    public static function create(string $role): UserDetailInterface;
}

// Factory class for create user
class UserCreateFactory implements UserCreateInterface
{
    public static function create(string $role): UserDetailInterface
    {
        switch ($role) {
            case 'Admin':
                return new Admin();
                break;
            case 'Candidate':
                return new Candidate();
                break;
            case 'Employer':
                return new Employer();
                break;
            default:
                throw new Exception('This user role is not valid');
        }
    }
}

// Client code:
$users = [];

$usersList = [
    [
        'name' => 'sara',
        'email' => 'sara@gmail.com',
        'role' => 'Admin'
    ],
    [
        'name' => 'behroz',
        'email' => 'behroz@gmail.com',
        'role' => 'Candidate'
    ],
    [
        'name' => 'hamid',
        'email' => 'hamid@gmail.com',
        'role' => 'Employer'
    ],
];

foreach($usersList as $userListItem) {
    $user = UserCreateFactory::create($userListItem['role']);
    $user->setName($userListItem['name']);
    $user->setEmail($userListItem['email']);

    array_push($users, $user);
}

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