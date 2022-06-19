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
            $observer->update($user, $event);
        }
    }
}

class User
{
    public string $name;
    public string $email;
}

/*
 * Observer interface
 */
interface Observer
{
    public function update(Observable $observable, string $event);
}

/*
 * Concrete observer classes
 */
class Email implements Observer
{
    public function update(Observable $observable, User $user, string $event)
    {
        if ($event === 'User:Registered') {
            $this->sendWelcomeEmail($user->email);
            $this->sendAdminEmail($observable, $event);
        } elseif ($event === 'User:Deleted') {
            $this->sendByeEmail($user->email);
            $this->sendAdminEmail($observable, $event);
        }
    }

    public function sendNotification()
    {

    }

    public function getTemplate(string $name, string $email)
    {

    }
}
