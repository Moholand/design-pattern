<?php
/*
 * Command abstract class
 */
abstract class CalculatorCommand
{
    protected Calculator $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public abstract function execute(): int|float;
}

/*
 * Concrete commands classes
 */
class PlusCommand extends CalculatorCommand
{
    public function execute(): int|float
    {
        return $this->calculator->plus();
    }
}

class MinusCommand extends CalculatorCommand
{
    public function execute(): int|float
    {
        return $this->calculator->minus();
    }
}

class MultipliedCommand extends CalculatorCommand
{
    public function execute(): int|float
    {
        return $this->calculator->multiplied();
    }
}

class DividedByCommand extends CalculatorCommand
{
    public function execute(): int|float
    {
        return $this->calculator->dividedBy();
    }
}

/*
 * Receiver class
 */
class Calculator
{
    private int $a;
    private int $b;

    public function __construct(int $a, int $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function plus(): int
    {
        return $this->a + $this->b;
    }

    public function minus(): int
    {
        return $this->a - $this->b;
    }

    public function multiplied(): int
    {
        return $this->a * $this->b;
    }

    public function dividedBy(): float
    {
        return $this->a / $this->b;
    }
}

/*
 * Invoker Class
 */
class Invoker
{
    public function executeCommand(CalculatorCommand $command): int|float
    {
        return $command->execute();
    }
}

function calculatorStart(): void
{
    $a = readline('Enter your first number: ');
    $b = readline('Enter your second number: ');
    $command = readline('Enter your command(allowed: + - * /): ');

    $calculator = new Calculator((int)$a, (int)$b);

    $invoker = new Invoker();

    switch($command) {
        case '+':
            echo 'result: ' .  $invoker->executeCommand(new PlusCommand($calculator));
            break;
        case '-':
            echo 'result: ' .  $invoker->executeCommand(new MinusCommand($calculator));
            break;
        case '*':
            echo 'result: ' .  $invoker->executeCommand(new MultipliedCommand($calculator));
            break;
        case '/':
            echo 'result: ' .  $invoker->executeCommand(new DividedByCommand($calculator));
            break;
        default:
            echo 'Wrong command selected!';
    }

    $isContinue = readline('Do you want to continue(yes/no): ');

    switch($isContinue) {
        case 'yes':
            echo calculatorStart();
            break;
        default:
            break;
    }
}

calculatorStart();

/*
 * Output:
 *
 * Enter your first number: 4
 * Enter your second number: 8
 * Enter your command(allowed: + - * /): *
 * result: 32
 * Do you want to continue(yes/no): no
 */
