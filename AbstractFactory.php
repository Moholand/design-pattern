<?php

/**
 * Employer Abstract Factory Interface
 */
interface EmployerAbstractFactory
{
    public function getPHPPosition(string $type): JobPosition;
    public function getPythonPosition(string $type): JobPosition;
}

/**
 * Irantalent Factory class
 */
class IrantalentFactory implements EmployerAbstractFactory
{
    public function getPHPPosition(string $type): JobPosition
    {
        switch($type) {
            case 'FullTime':
                return new FullTimePHPPosition();
                break;
            case 'HalfTime':
                return new HalfTimePHPPosition();
                break;
            default:
                throw new Exception('This job position type is not valid');
        }
    }

    public function getPythonPosition(string $type): JobPosition
    {
        switch($type) {
            case 'FullTime':
                return new FullTimePythonPosition();
                break;
            case 'HalfTime':
                return new HalfTimePythonPosition();
                break;
            default:
                throw new Exception('This job position type is not valid');
        }
    }
}

/**
 * Snap Factory class
 */
class SnapFactory implements EmployerAbstractFactory
{
    public function getPHPPosition(string $type): JobPosition
    {
        switch($type) {
            case 'FullTime':
                return new FullTimePHPPosition();
                break;
            case 'HalfTime':
                return new HalfTimePHPPosition();
                break;
            default:
                throw new Exception('This job position type is not valid');
        }
    }

    public function getPythonPosition(string $type): JobPosition
    {
        switch($type) {
            case 'FullTime':
                return new FullTimePythonPosition();
                break;
            case 'HalfTime':
                return new HalfTimePythonPosition();
                break;
            default:
                throw new Exception('This job position type is not valid');
        }
    }
}

/**
 * Job Position Interface
 */
interface JobPosition
{
    public function getSalary(): string;
    public function getWorkingHour(): string;
}

/**
 * Job Positions Abstract classes
 */
abstract class FullTimePosition implements JobPosition
{
    protected string $type = 'FullTime';
}

abstract class HalfTimePosition implements JobPosition
{
    protected string $type = 'HalfTime';
}

/**
 * Job classes
 */
class FullTimePHPPosition extends FullTimePosition
{
    public function getSalary(): string
    {
        return '1500$';
    }

    public function getWorkingHour(): string
    {
        return '45 Hours';
    }
}

class HalfTimePHPPosition extends HalfTimePosition
{
    public function getSalary(): string
    {
        return '800$';
    }

    public function getWorkingHour(): string
    {
        return '25 Hours';
    }
}

class FullTimePythonPosition extends FullTimePosition
{
    public function getSalary(): string
    {
        return '2000$';
    }

    public function getWorkingHour(): string
    {
        return '40 Hours';
    }
}

class HalfTimePythonPosition extends HalfTimePosition
{
    public function getSalary(): string
    {
        return '1000$';
    }

    public function getWorkingHour(): string
    {
        return '20 Hours';
    }
}

/**
 * Client class
 */
class EmployerClient
{
    protected JobPosition $PHPPosition;
    protected JobPosition $PythonPosition;

    public function __construct(EmployerAbstractFactory $employer, string $type)
    {
        $this->PHPPosition = $employer->getPHPPosition($type);
        $this->PythonPosition = $employer->getPythonPosition($type);
    }

    public function getPHPSalary(): string
    {
        return $this->PHPPosition->getSalary();
    }

    public function getPHPWorkingHour(): string
    {
        return $this->PHPPosition->getWorkingHour();
    }

    public function getPythonSalary(): string
    {
        return $this->PythonPosition->getSalary();
    }

    public function getPythonWorkingHour(): string
    {
        return $this->PythonPosition->getWorkingHour();
    }
}

$irantalent = new IrantalentFactory();
$snap = new SnapFactory();

$FullTimeIrantalent = new EmployerClient($irantalent, 'FullTime');
$HalfTimeIrantalent = new EmployerClient($irantalent, 'HalfTime');

$FullTimeSnap = new EmployerClient($snap, 'FullTime');
$HalfTimeSnap = new EmployerClient($snap, 'HalfTime');

echo '--------------- Irantalent PHP FullTime Position -------------------- <br/>';
echo 'PHP FullTime job salary: ' . $FullTimeIrantalent->getPHPSalary() .'<br/>';
echo 'PHP FullTime job working hour: ' . $FullTimeIrantalent->getPHPWorkingHour() .'<br/>';

echo '--------------- Irantalent Python FullTime Position --------------------<br/>';
echo 'Python FullTime job salary: ' . $FullTimeIrantalent->getPythonSalary() .'<br/>';
echo 'Python FullTime job working hour: ' . $FullTimeIrantalent->getPythonWorkingHour() .'<br/>';

echo '--------------- Irantalent PHP HalfTime Position --------------------<br/>';
echo 'PHP HalfTime job salary: ' . $HalfTimeIrantalent->getPHPSalary() .'<br/>';
echo 'PHP HalfTime job working hour: ' . $HalfTimeIrantalent->getPHPWorkingHour() .'<br/>';

echo '--------------- Irantalent Python HalfTime Position --------------------<br/>';
echo 'Python HalfTime job salary: ' . $HalfTimeIrantalent->getPythonSalary() .'<br/>';
echo 'Python HalfTime job working hour: ' . $HalfTimeIrantalent->getPythonWorkingHour() .'<br/>';

echo '--------------- Snap PHP FullTime Position -------------------- <br/>';
echo 'PHP FullTime job salary: ' . $FullTimeSnap->getPHPSalary() .'<br/>';
echo 'PHP FullTime job working hour: ' . $FullTimeSnap->getPHPWorkingHour() .'<br/>';

echo '--------------- Snap Python FullTime Position --------------------<br/>';
echo 'Python FullTime job salary: ' . $FullTimeSnap->getPythonSalary() .'<br/>';
echo 'Python FullTime job working hour: ' . $FullTimeSnap->getPythonWorkingHour() .'<br/>';

echo '--------------- Snap PHP HalfTime Position --------------------<br/>';
echo 'PHP HalfTime job salary: ' . $HalfTimeSnap->getPHPSalary() .'<br/>';
echo 'PHP HalfTime job working hour: ' . $HalfTimeSnap->getPHPWorkingHour() .'<br/>';

echo '--------------- Snap Python HalfTime Position --------------------<br/>';
echo 'Python HalfTime job salary: ' . $HalfTimeSnap->getPythonSalary() .'<br/>';
echo 'Python HalfTime job working hour: ' . $HalfTimeSnap->getPythonWorkingHour() .'<br/>';

/**
 * Output:
 * --------------- Irantalent PHP FullTime Position --------------------
 * PHP FullTime job salary: 1500$
 * PHP FullTime job working hour: 45 Hours
 * --------------- Irantalent Python FullTime Position --------------------
 * Python FullTime job salary: 2000$
 * Python FullTime job working hour: 40 Hours
 * --------------- Irantalent PHP HalfTime Position --------------------
 * PHP HalfTime job salary: 800$
 * PHP HalfTime job working hour: 25 Hours
 * --------------- Irantalent Python HalfTime Position --------------------
 * Python HalfTime job salary: 1000$
 * Python HalfTime job working hour: 20 Hours
 * --------------- Snap PHP FullTime Position --------------------
 * PHP FullTime job salary: 1500$
 * PHP FullTime job working hour: 45 Hours
 * --------------- Snap Python FullTime Position --------------------
 * Python FullTime job salary: 2000$
 * Python FullTime job working hour: 40 Hours
 * --------------- Snap PHP HalfTime Position --------------------
 * PHP HalfTime job salary: 800$
 * PHP HalfTime job working hour: 25 Hours
 * --------------- Snap Python HalfTime Position --------------------
 * Python HalfTime job salary: 1000$
 * Python HalfTime job working hour: 20 Hours
 *
 */
