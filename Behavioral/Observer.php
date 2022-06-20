<?php
/*
 * Observable interface
 */
interface Observable
{
    public function attach(Observer $observer): void;
    public function detach(Observer $observer): void;
    public function notify(User $user, string $event): void;
}

/*
 * Concrete observable interface
 */
class UserRepository implements Observable
{
    private array $observers = [];
    private array $users = [];

    public function register(array $request)
    {
        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];

        $this->users[] = $user;
        $this->notify($user, 'User:Registered');
    }

    public function destroy(string $email)
    {
        foreach ($this->users as $key => $user) {
            if ($user->email == $email) {
                $deletedUser = $this->users[$key];
                unset($this->users[$key]);
            }
        }

        $this->notify($deletedUser, 'User:Deleted');
    }

    public function attach(Observer $observer): void
    {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer): void
    {
        foreach ($this->observers as $key => $value) {
            if ($value === $observer) {
                unset($this->observers[$key]);
            }
        }
    }

    public function notify(User $user, string $event): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this, $user, $event);
        }
    }

    public function getUserCount(): int
    {
        return count($this->users);
    }
}

class User
{
    public string $name;
    public string $email;

    public function getInfo(): string
    {
        return $this->name . '(' . $this->email . ')';
    }
}

/*
 * Observer interface
 */
interface Observer
{
    public function update(Observable $observable, User $user, string $event);
}

/*
 * Concrete observer classes
 */
class Email implements Observer
{
    public function update(Observable $observable, User $user, string $event): void
    {
        if ($event === 'User:Registered') {
            $this->sendWelcomeEmail($user);
        } elseif ($event === 'User:Deleted') {
            $this->sendByeEmail($user);
        }

        $this->sendAdminEmail($observable, $user, $event);
    }

    public function sendWelcomeEmail(User $user): void
    {
        $html = "<h3>Email to: {$user->email}</h3>" ;
        $html .= "<h5>Welcome Dear {$user->name}!</h5>";
        $html .= "Have a nice time in IranTalent.<br>";

        echo $html;
    }

    public function sendByeEmail(User $user): void
    {
        $html = "<h3>Email to: {$user->email}</h3>" ;
        $html .= "<h5>Dear {$user->name}!</h5>";
        $html .= "IranTalent will miss you.<br>";
        $html .= "Good luck!<br>";

        echo $html;
    }

    public function sendAdminEmail(Observable $observable, User $user, string $event): void
    {
        $html = "<h3>Email to: Admin</h3>" ;
        $html .= "<h5>Dear Admin!</h5>";

        if ($event === 'User:Registered') {
            $html .= "User by email : {$user->email} Registered.<br>";
        } elseif ($event === 'User:Deleted') {
            $html .= "User by email : {$user->email} Deleted.<br>";
        }

        $html .= "We have {$observable->getUserCount()} active user right now.<br>";
        $html .= "From IranTalent!<br>";

        echo $html;
    }
}

$userRepository = new UserRepository();
$userRepository->attach(new Email());
$userRepository->register([
    'name' => 'mohammad',
    'email' => 'naderi@gmail.com'
]);
$userRepository->register([
    'name' => 'sadegh',
    'email' => 'hosseini@gmail.com'
]);
$userRepository->destroy('hosseini@gmail.com');

/*
 * Output:
 *
 * Email to: naderi@gmail.com
 * Welcome Dear mohammad!
 * Have a nice time in IranTalent.
 *
 *
 * Email to: Admin
 * Dear Admin!
 * User by email : naderi@gmail.com Registered.
 * We have 1 active user right now.
 * From IranTalent!
 *
 *
 * Email to: hosseini@gmail.com
 * Welcome Dear sadegh!
 * Have a nice time in IranTalent.
 *
 *
 * Email to: Admin
 * Dear Admin!
 * User by email : hosseini@gmail.com Registered.
 * We have 2 active user right now.
 * From IranTalent!
 *
 *
 * Email to: hosseini@gmail.com
 * Dear sadegh!
 * IranTalent will miss you.
 * Good luck!
 *
 *
 * Email to: Admin
 * Dear Admin!
 * User by email : hosseini@gmail.com Deleted.
 * We have 1 active user right now.
 * From IranTalent!
 */

