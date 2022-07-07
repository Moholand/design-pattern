<?php
/*
 * Abstract class
 */
abstract class Model
{
    private array $table = [];

    final public function create(array $data): void
    {
        if ($this->validate($data)) {
            $this->beforeCreate();
            $this->insertToDb($data);
            $this->created();
        } else {
            throw new Exception('Data is not validated!');
        }
    }

    // The hook operation
    public function beforeCreate(): void
    {
        echo 'One record is about to insert to database' . '<br>';
    }

    public function created(): void
    {
        echo 'One record is inserted to database' . '<br>';
    }

    // The primitive operation
    abstract public function validate(array $data): bool;
    abstract public function insertToDb(array $data): void;
}

/*
 * Concrete class
 */
class Candidate extends Model
{
    public function validate(array $data): bool
    {
        $status = true;

        if (!isset($data['name']) || gettype($data['name']) !== 'string') {
            $status = false;
        } elseif (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $status = false;
        }

        return $status;
    }

    public function insertToDb(array $data): void
    {
        $this->table['candidate'][] = [$data['name'], $data['email']];
    }
}

class Employer extends Model
{
    public function validate(array $data): bool
    {
        $status = true;

        if (!isset($data['company_name']) || gettype($data['company_name']) !== 'string') {
            $status = false;
        } elseif (!isset($data['employer_count']) || gettype($data['employer_count']) !== 'integer' || $data['employer_count'] <= 0) {
            $status = false;
        }

        return $status;
    }

    public function insertToDb(array $data): void
    {
        $this->table['employer'][] = [$data['company_name'], $data['employer_count']];
    }

    public function created(): void
    {
        print_r($this->table);
    }
}

/*
 * Client
 */
$candidate = new Candidate();
$candidate->create(['name'  => 'ali hosseini', 'email' => 'alliam@gmail.com']);
$candidate->create(['name'  => 'sara saeidi',  'email' => 'saeidi@gmail.com']);

$employer = new Employer();
$employer->create(['company_name' => 'Moholand Gostaran Gharb', 'employer_count' => 55]);
$employer->create(['company_name' => 'Irani Cars',              'employer_count' => 44]);

/*
 * Output:
 *
 * One record is about to insert to database
 * One record is inserted to database
 *
 * One record is about to insert to database
 * One record is inserted to database
 *
 * One record is about to insert to database
 * Array ( [employer] => Array ( [0] => Array ( [0] => Moholand Gostaran Gharb [1] => 55 ) ) )
 *
 * One record is about to insert to database
 * Array ( [employer] => Array ( [0] => Array ( [0] => Moholand Gostaran Gharb [1] => 55 ) [1] => Array ( [0] => Irani Cars [1] => 44 ) ) )
 */
