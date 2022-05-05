<?php
/*
 * Message Interface - Adapter classes should implement this!
 */
interface Message
{
    public function send(string $title, string $body): void;
}

/*
 * Telegram Api - Contract for send message with telegram!
 */
class TelegramApi
{
    protected string $account;

    public function __construct(string $account)
    {
        $this->account = $account;
    }

    public function login(): void
    {
        echo 'Login to telegram account with ' . $this->account . ' account' . '<br />';
    }

    public function sendMessage(string $userId, string $message): void
    {
        echo 'Send message to '. $userId . '<br />' .
             'message: ' . $message;
    }
}

/*
 * Telegram message adapter class
 */
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
        $telegramMessage = $title . ' - ' . $body . '<br />';

        $this->telegramApi->login();

        $this->telegramApi->sendMessage($this->userId, $telegramMessage);
    }
}

/*
 * Whatsapp Api - Contract for send message with whatsapp!
 */
class WhatsappApi
{
    protected string $accountNumber;

    public function __construct(string $accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }

    public function login(): void
    {
        echo 'Login to whatsapp account with ' . $this->accountNumber . ' account number' . '<br />';
    }

    public function sendMessage(string $userNumber, string $message): void
    {
        echo 'Send message to '. $userNumber . '<br />' .
            'message: ' . $message;
    }
}

/*
 * Whatsapp message adapter class
 */
class WhatsappMessage implements Message
{
    protected WhatsappApi $whatsappApi;
    protected string $userNumber;

    public function __construct(WhatsappApi $whatsappApi, string $userNumber)
    {
        $this->whatsappApi = $whatsappApi;
        $this->userNumber = $userNumber;
    }

    public function send(string $title, string $body): void
    {
        $whatsappMessage = $title . ' - ' . $body . '<br />';

        $this->whatsappApi->login();

        $this->whatsappApi->sendMessage($this->userNumber, $whatsappMessage);
    }
}

/*
 * Client
 */
function sendMessage(Message $message, string $title, string $body): void
{
    $message->send($title, $body);
}

$telegram = new TelegramApi('@irantalent');
$telegramMessage = new TelegramMessage($telegram, '@mohammad-hr');

$whatsapp = new WhatsappApi('09121121212');
$whatsappMessage = new WhatsappMessage($whatsapp, '09362568878');

sendMessage($telegramMessage, 'You have telegram message!', 'This message is for you!');
echo '--------------------------------------------' . '<br />';
sendMessage($whatsappMessage, 'You have whatsapp message!', 'This message is for you!');


/*
 * Output:
 *
 * Login to telegram account with @irantalent account
 * Send message to @mohammad-hr
 * message: You have telegram message! - This message is for you!
 * --------------------------------------------
 * Login to whatsapp account with 09121121212 account number
 * Send message to 09362568878
 * message: You have whatsapp message! - This message is for you!
 *
 */
