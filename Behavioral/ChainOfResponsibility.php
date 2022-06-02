<?php
/*
 * The Handler interface
 */
interface Handler
{
    public function setNext(Middleware $middleware): Middleware;

    public function handle(string $email, string $password): bool;
}

/*
 * The base Middleware class
 */
abstract class Middleware implements Handler
{
    private ?Middleware $next = NULL;

    public function setNext(Middleware $middleware): Middleware
    {
        return $this->next = $middleware;
    }

    public function handle(string $email, string $password): bool
    {
        if(is_null($this->next)) {
            return true;
        }
        return $this->next->handle($email, $password);
    }
}

/*
 * Concrete Middleware - Checks Authentication
 */
class AuthCheckMiddleware extends Middleware
{
    private Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function handle(string $email, string $password): bool
    {
        if(!$this->application->authCheck($email)) {
            echo 'AuthCheckMiddleware: User is not register' . '<br>';
            return false;
        }

        return parent::handle($email, $password);
    }
}

/*
 * Concrete Middleware - Checks Admin
 */
class AdminCheckMiddleware extends Middleware
{
    private Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function handle(string $email, string $password): bool
    {
        if(!$this->application->adminCheck($email)) {
            echo 'AdminCheckMiddleware: User is not admin' . '<br>';
            return false;
        }

        return parent::handle($email, $password);
    }
}

/*
 * Application class
 */
class Application
{
    private array $users = [
        [
            'id' => 1,
            'email' => 'admin@gmail.com',
            'password' => 'admin',
            'is_admin' => 1
        ],
    ];

    private Middleware $middleware;

    public function setMiddleware(Middleware $middleware): void
    {
        $this->middleware = $middleware;
    }

    public function getUsers(string $email, string $password): array|string
    {
        if ($this->middleware->handle($email, $password)) {
            return $this->users;
        }

        return 'redirect: back()' . '<br>';
    }

    public function register(string $email, string $password): void
    {
        $this->users[] = [
            'id' => uniqid(),
            'email' => $email,
            'password' => $password,
            'is_admin' => 0,
        ];
    }

    public function authCheck(string $email): bool
    {
        foreach($this->users as $user) {
            if($user['email'] === $email) {
                return true;
            }
        }
        return false;
    }

    public function adminCheck(string $email): bool
    {
        foreach($this->users as $user) {
            if($user['email'] === $email) {
                return $user['is_admin'];
            }
        }
        return false;
    }
}

/*
 * Client:
 */
$application = new Application();
$application->register('mohammad@gmail.com', 'mohammad');
$application->register('sara@gmail.com', 'sara');
$application->register('mehdi@gmail.com', 'mehdi');

$middleware = new AuthCheckMiddleware($application);
$middleware->setNext(new AdminCheckMiddleware($application));

$application->setMiddleware($middleware);

print_r($application->getUsers('hamid@gmail.com', 'hamid'));
echo '<br/ > ------------------------------- <br/ >';
print_r($application->getUsers('mohammad@gmail.com', 'mohammad'));
echo '<br/ > ------------------------------- <br/ >';
print_r($application->getUsers('admin@gmail.com', 'admin'));

/*
 * Output:
 *
 * AuthCheckMiddleware: User is not register
 * redirect: back()
 *
 * -------------------------------
 * AdminCheckMiddleware: User is not admin
 * redirect: back()
 *
 * -------------------------------
 * Array (
 *          [0] => Array ( [id] => 1             [email] => admin@gmail.com    [password] => admin [is_admin] => 1    )
 *          [1] => Array ( [id] => 62989eb8bbc63 [email] => mohammad@gmail.com [password] => mohammad [is_admin] => 0 )
 *          [2] => Array ( [id] => 62989eb8bbc69 [email] => sara@gmail.com     [password] => sara [is_admin] => 0     )
 *          [3] => Array ( [id] => 62989eb8bbc6a [email] => mehdi@gmail.com    [password] => mehdi [is_admin] => 0    )
 *       )
 */

