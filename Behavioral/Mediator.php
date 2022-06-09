<?php
/*
 * Mediator interface
 */
interface ChatMediator
{
    public function addMember(Member $member): void;
    public function sendMessage(string $message, Member $fromMember, Member $toMember): void;
}

/*
 * Concrete mediator class
 */
class Chatroom implements ChatMediator
{
    private array $members;

    public function addMember(Member $member): void
    {
        $this->members[] = $member;

        $member->setChatroom($this);
    }

    public function getMembers(): array
    {
        return $this->members;
    }

    public function sendMessage(string $message, Member $fromMember, Member $toMember): void
    {
        $toMember->receiveMessage($message, $fromMember);
    }

    public function sendAll(string $message, Member $fromMember): void
    {
        foreach ($this->members as $member) {
            if ($fromMember !== $member) {
                $this->sendMessage($message, $fromMember, $member);
            }
        }
    }
}

/*
 * Member class
 */
class Member
{
    private string $name;
    private ?ChatMediator $chatroom = NULL;
    private ?array $messages = NULL;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setChatroom(Chatroom $chatroom): void
    {
        $this->chatroom = $chatroom;
    }

    public function sendMessage(string $message, Member $toMember): void
    {
        $this->chatroom->sendMessage($message, $this, $toMember);

        $this->logMessages($message, $this, $toMember);
    }

    public function sendAll(string $message): void
    {
        $this->chatroom->sendAll($message, $this);

        foreach ($this->chatroom->getMembers() as $member) {
            if ($this !== $member) {
                $this->logMessages($message, $this, $member);
            }
        }
    }

    public function receiveMessage(string $message, Member $fromMember): void
    {
        $this->logMessages($message, $fromMember, $this);
    }

    public function getMessages(): string
    {
        $html = "<h3>{$this->name} messages:</h3>";

        return $html .= implode('', $this->messages);
    }

    public function logMessages(string $message, Member $fromMember, Member $toMember): void
    {
        $this->messages[] = "{$fromMember->getName()} to {$toMember->getName()}: {$message} <small>(" . date("Y-m-d h-i", time()) . ")</small><br>";
    }
}

/*
 * Client:
 */
$chatroom = new Chatroom();

$ali = new Member('ali');
$mohammad = new Member('mohammad');
$sara = new Member('sara');

$chatroom->addMember($ali);
$chatroom->addMember($mohammad);
$chatroom->addMember($sara);

$ali->sendMessage('hi! how are you doing?', $mohammad);
$mohammad->sendMessage('I`m good!', $ali);
$sara->sendAll('hey every body! what`s up??');
$sara->sendAll('Is there anybody hear me??');

echo $ali->getMessages();
echo $mohammad->getMessages();
echo $sara->getMessages();

/*
 * Output:
 *
 * ali messages:
 * ali to mohammad: hi! how are you doing? (2022-06-09 08-07)
 * mohammad to ali: I`m good! (2022-06-09 08-07)
 * sara to ali: hey every body! what`s up?? (2022-06-09 08-07)
 * sara to ali: Is there anybody hear me?? (2022-06-09 08-07)
 *
 * mohammad messages:
 * ali to mohammad: hi! how are you doing? (2022-06-09 08-07)
 * mohammad to ali: I`m good! (2022-06-09 08-07)
 * sara to mohammad: hey every body! what`s up?? (2022-06-09 08-07)
 * sara to mohammad: Is there anybody hear me?? (2022-06-09 08-07)
 *
 * sara messages:
 * sara to ali: hey every body! what`s up?? (2022-06-09 08-07)
 * sara to mohammad: hey every body! what`s up?? (2022-06-09 08-07)
 * sara to ali: Is there anybody hear me?? (2022-06-09 08-07)
 * sara to mohammad: Is there anybody hear me?? (2022-06-09 08-07)
 */
