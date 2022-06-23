<?php
/*
 * Context class - Game for guessing number
 */
class GuessNumGame
{
    private State $currentState;
    private int $answer;

    public function __construct()
    {
        $this->setState(new SafeState($this));
        $this->generateNumber();
    }

    public function generateNumber(): void
    {
        $this->answer = rand(1, 20);
    }

    public function getAnswer(): int
    {
        return $this->answer;
    }

    public function setState(State $newState): void
    {
        $this->currentState = $newState;
    }

    public function getState(): string
    {
        return get_class($this->currentState);
    }

    public function Check(int $number): bool
    {
        if ($this->answer === $number) {
            $this->currentState->Right();
            return true;
        } else {
            $this->currentState->Wrong();
            return false;
        }
    }
}

/*
 * State interface
 */
interface State
{
    public function Right(): void;
    public function Wrong(): void;
}

/*
 * Concrete state classes
 */
class SafeState implements State
{
    private GuessNumGame $game;
    private int $limit = 0;

    public function __construct(GuessNumGame $game)
    {
        $this->game = $game;
    }

    public function Right(): void
    {
        echo "You Win!!! The answer was {$this->game->getAnswer()}" . ".\n";
    }

    public function Wrong(): void
    {
        $this->limit++;

        if ($this->limit >= 2) {
            $this->game->setState(new WarningState($this->game));
            echo "Incorrect!!! You are in {Warning} state" . ".\n";
        } else {
            echo "Incorrect!!! You are in {Safe} state" . ".\n";
        }
    }
}

class WarningState implements State
{
    private GuessNumGame $game;
    private int $limit = 0;

    public function __construct(GuessNumGame $game)
    {
        $this->game = $game;
    }

    public function Right(): void
    {
        echo "You Win!!! The answer was {$this->game->getAnswer()}" . ".\n";
    }

    public function Wrong(): void
    {
        $this->limit++;

        if ($this->limit >= 2) {
            $this->game->setState(new LooseState($this->game));
            echo "Incorrect!!! You are in {Loose} state" . ".\n";
        } else {
             echo "Incorrect!!! You are in {Warning} state" . ".\n";
         }
    }
}

class LooseState implements State
{
    private GuessNumGame $game;

    public function __construct(GuessNumGame $game)
    {
        $this->game = $game;
    }

    public function Right(): void
    {
        echo "You Win!!! The answer was {$this->game->getAnswer()}" . ".\n";
    }

    public function Wrong(): void
    {
        echo "Game Over!" . ".\n";
    }
}

/*
 * Client
 */
function GuessMyNumber(): void
{
    $game = new GuessNumGame();
    $lastChance = 1;

    while ($lastChance >= 0) {
        $num = readline('Guess my number. It`s between 1 to 20: ');

        if ($game->check($num)) {
            break;
        };

        if ($game->getState() === 'LooseState') {
            $lastChance--;
        }
    }
}

GuessMyNumber();

/*
 * Output:
 *
 * Guess my number. It`s between 1 to 20: 7
 * Incorrect!!! You are in {Safe} state.
 * Guess my number. It`s between 1 to 20: 9
 * Incorrect!!! You are in {Warning} state.
 * Guess my number. It`s between 1 to 20: 12
 * Incorrect!!! You are in {Warning} state.
 * Guess my number. It`s between 1 to 20: 16
 * Incorrect!!! You are in {Loose} state.
 * Guess my number. It`s between 1 to 20: 3
 *
 * Game Over!.
 * Or
 * You Win!!! The answer was 2.
 */
