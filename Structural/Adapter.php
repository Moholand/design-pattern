<?php
interface Message
{
    public function send(string $title, string $body): void;
}

class EmailMessage implements Message
{
    protected string $address;

    public function __construct(string $address)
    {
        $this->address = $address;
    }

    public function send(string $title, string $body): void
    {
        echo 'Email sent to ' . $this->address . '<br />' .
             'title: ' . $title . '<br />' . 'body: ' . $body . '<br />';
    }
}

class TelegramApi
{
    protected $account;

    public function __construct($account)
    {
        $this->account = $account;
    }

    public function login()
    {
        echo 'Login to telegram account with ' . $this->account . ' account' . '<br />';
    }

    public function sendMessage($userId, $message)
    {
        echo 'Send message to '. $userId . '<br />' .
             'message: ' . $message;
    }
}

class TelegramMessage implements Message
{
    protected TelegramApi $telegramApi;
    protected string $userId;

    public function __construct(TelegramApi $telegramApi, string $userId)
    {
        $this->telegramApi = $telegramApi;
        $this->userId = $userId;
    }

    public function send(string $title, string $body): void
    {
        $telegramMessage = $title . '---' . $body;

        $this->telegramApi->login();

        $this->telegramApi->sendMessage($this->userId, $telegramMessage);
    }
}

/*
 * Client
 */
function sendMessage(Message $message): void
{
    $message->send('You have message!', 'This message is for you!');
}

$message = new EmailMessage('mohammad@gmail.com');

sendMessage($message);

echo '--------------------------------------------' . '<br />';

$telegram = new TelegramApi('@irantalent');
$telegramMessage = new TelegramMessage($telegram, '@mohammad-hr');

sendMessage($telegramMessage);
