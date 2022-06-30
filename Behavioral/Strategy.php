<?php
/*
 * Strategy interface
 */
interface PaymentMethod
{
    public function pay(string $amount): bool;
}

/*
 * Concrete Strategy classes
 */
class Online implements PaymentMethod
{
    public function pay(string $amount): bool
    {
        return $this->checkGatewayStatus();
    }

    public function checkGatewayStatus(): bool
    {
        echo 'Online payment rejected' . '<br>';
        return false;
    }
}

class CreditCard implements PaymentMethod
{
    public function pay(string $amount): bool
    {
        return $this->checkForCardNumber() && $this->checkCardAmount($amount);
    }

    public function checkForCardNumber(): bool
    {
        echo 'Card number exists' . '<br>';
        return true;
    }

    public function checkCardAmount(string $amount): bool
    {
        echo 'Card amount does not enough!' . '<br>';
        return false;
    }
}

class Cash implements PaymentMethod
{
    public function pay(string $amount): bool
    {
        echo 'Cash paid!' . '<br>';
        return true;
    }
}

/*
 * Context class
 */
class OrderController
{
    private PaymentMethod $paymentMethod;
    private array $orders;

    public function setPaymentMethod(PaymentMethod $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function addOrder(array $order): void
    {
        $this->orders[] = $order;
    }

    public function buy(): void
    {
        $amount = $this->getTotalAmount();

        if ($this->paymentMethod->pay($amount)) {
            echo 'Payment was successful' . '<br>';
            $this->orders = [];
        } else {
            echo 'Payment does not successful' . '<br>';
        }
    }

    public function getTotalAmount(): int
    {
        $amount = 0;

        foreach($this->orders as $order) {
            $amount += $order['amount'];
        }

        return $amount;
    }
}

/*
 * Client
 */
$orderController = new OrderController();

$orderController->addOrder(['id' => 1111, 'name' => 'Laptop',   'amount' => 50000000]);
$orderController->addOrder(['id' => 1211, 'name' => 'Keyboard', 'amount' => 7000000]);
$orderController->addOrder(['id' => 1341, 'name' => 'Mouse',    'amount' => 1000000]);

$orderController->setPaymentMethod(new Online());
$orderController->buy();

$orderController->setPaymentMethod(new CreditCard());
$orderController->buy();

$orderController->setPaymentMethod(new Cash());
$orderController->buy();

/*
 * Output:
 *
 * Online payment rejected
 * Payment does not successful
 *
 * Card number exists
 * Card amount does not enough!
 * Payment does not successful
 *
 * Cash paid!
 * Payment was successful
 */
