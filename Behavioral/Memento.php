<?php
/*
 * Originator class
 */
class Calculator
{
    private string $result;

    public function calculate(int $a, int $b, string $operator): string
    {
        switch ($operator) {
            case '+':
                $this->result = "{$a} + {$b} = " . $a + $b;
                break;
            case '-':
                $this->result = "{$a} - {$b} = " . $a - $b;
                break;
            case '*':
                $this->result = "{$a} * {$b} = " . $a * $b;
                break;
            case '/':
                $this->result = "{$a} / {$b} = " . $a / $b;
                break;
            default:
                return 'Invalid operator!';
        }

        return $this->result;
    }

    public function save(): Memory
    {
        return new ConcreteMemory($this->result);
    }

    public function undo(Memory $memory): void
    {
        $this->result = $memory->getResult();
    }
}

/*
 * Memento interface
 */
interface Memory
{
    public function getResult(): string;
}

/*
 * Concrete memento class
 */
class ConcreteMemory implements Memory
{
    private string $result;

    public function __construct(string $result)
    {
        $this->result = $result;
    }

    public function getResult(): string
    {
        return $this->result;
    }
}

/*
 * CareTaker class
 */
class MemoryCart
{
    private array $memories = [];
    private Calculator $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function save(): void
    {
        $this->memories[] = $this->calculator->save();
    }

    public function undo(): void
    {
        if (count($this->memories)) {
            $this->calculator->undo(array_pop($this->memories));
        }
    }

    public function showMemories(): string
    {
        $html = 'Memory results history: ' . '<br>';

        foreach ($this->memories as $memory) {
            $html .= $memory->getResult() . "<br>";
        }

        return $html;
    }
}

/*
 * Client
 */
$calculator = new Calculator();
$memoryCart = new MemoryCart($calculator);

function calculatorStart(Calculator $calculator, MemoryCart $memoryCart): void
{
    $a = readline('Enter your first number: ');
    $b = readline('Enter your second number: ');
    $operator = readline('Enter your operator(allowed: + - * /): ');

    echo $calculator->calculate((int)$a, (int)$b, $operator);

    $command = readline('What do you want next? (c:continue ; s:save ; u:undo ; r:results ; e:exit): ');

    switch($command) {
        case 'c':
        case 'continue':
            calculatorStart($calculator, $memoryCart);
            break;
        case 's':
        case 'save':
            $memoryCart->save();
            calculatorStart($calculator, $memoryCart);
            break;
        case 'u':
        case 'undo':
            $memoryCart->undo();
            calculatorStart($calculator, $memoryCart);
            break;
        case 'r':
        case 'results':
            echo $memoryCart->showMemories();
            calculatorStart($calculator, $memoryCart);
            break;
        case 'e':
        case 'exit':
        default:
            break;
    }
}

calculatorStart($calculator, $memoryCart);

/*
 * Output:
 *
 * Enter your first number: 5
 * Enter your second number: 6
 * Enter your operator(allowed: + - * /): +
 * 5 + 6 = 11
 * What do you want next? (c:continue ; s:save ; u:undo ; r:results ; e:exit): s
 *
 * Enter your first number: 6
 * Enter your second number: 6
 * Enter your operator(allowed: + - * /): *
 * 6 * 6 = 36
 * What do you want next? (c:continue ; s:save ; u:undo ; r:results ; e:exit): s
 *
 * Enter your first number: 5
 * Enter your second number: 4
 * Enter your operator(allowed: + - * /): -
 * 5 - 4 = 1
 * What do you want next? (c:continue ; s:save ; u:undo ; r:results ; e:exit): s
 *
 * Enter your first number: 3
 * Enter your second number: 9
 * Enter your operator(allowed: + - * /): +
 * 3 + 9 = 12
 * What do you want next? (c:continue ; s:save ; u:undo ; r:results ; e:exit): u
 *
 * Enter your first number: 5
 * Enter your second number: 4
 * Enter your operator(allowed: + - * /): *
 * 5 * 4 = 20
 * What do you want next? (c:continue ; s:save ; u:undo ; r:results ; e:exit): r
 *
 * Memory results history:
 * 5 + 6 = 11
 * 6 * 6 = 36
 */
