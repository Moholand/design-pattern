<?php

/*
 * Flyweight abstract class
 */
abstract class JobPosition
{
    protected string $title;
    protected string $description;
    protected array $abilities;
    protected int $salary;
    protected DateTime $closingDate;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setAbilities(array $abilities): void
    {
        $this->abilities = $abilities;
    }

    public function setSalary(int $salary): void
    {
        $this->salary = $salary;
    }

    public function setLeftDays(int $leftDays): void
    {
        $this->closingDate = (new DateTime())->add(new DateInterval('P' . $leftDays . 'D'));
    }

    public function getTitle(): string { return $this->title; }

    public function getDescription(): string { return $this->description; }

    public function getAbilities(): array { return $this->abilities; }

    public function getSalary(): int { return $this->salary; }

    public function getLeftDays(): string
    {
        return ($this->closingDate)->diff(new DateTime())->format('%d');
    }

    public abstract function render(): string;
}

/*
 * Concrete Flyweight classes
 */
class PersianJobPosition extends JobPosition
{
    public function render(): string
    {
        $html  = "<div style='direction: rtl'>";
        $html .= "<h3>{$this->getTitle()}</h3>";
        $html .= "<hr>";
        $html .= "<h5>شرح موقعیت شغلی:</h5>";
        $html .= "<p>{$this->getDescription()}</p>";
        $html .= "<h5>توانایی های مورد نیاز:</h5>";
        $html .= "<ul>";

        foreach($this->getAbilities() as $ability) {
            $html .= "<li>{$ability}</li>";
        }

        $html .= "</ul>";
        $html .= "<p><span>حقوق: </span><span> {$this->getSalary()} </span><span>تومان</span></p>";
        $html .= "<h5><span>فرصت ارسال رزومه: </span><span style='font-weight: bold'> {$this->getLeftDays()} </span><span>روز</span></h5>";
        $html .= "</div>";

        return $html;
    }
}

class EnglishJobPosition extends JobPosition
{
    public function render(): string
    {
        $html  = "<div style='direction: ltr'>";
        $html .= "<h3>{$this->getTitle()}</h3>";
        $html .= "<hr>";
        $html .= "<h5>Job Position Description:</h5>";
        $html .= "<p>{$this->getDescription()}</p>";
        $html .= "<h5>Requirements:</h5>";
        $html .= "<ul>";

        foreach($this->getAbilities() as $ability) {
            $html .= "<li>{$ability}</li>";
        }

        $html .= "</ul>";
        $html .= "<p><span>Salary: </span><span>{$this->getSalary()}</span><span> $</span></p>";
        $html .= "<h5><span>Days left for submit: </span><span style='font-weight: bold'> {$this->getLeftDays()} </span><span>day</span></h5>";
        $html .= "</div>";

        return $html;
    }
}

/*
 * Flyweight Factory class
 */
class JobPositionFactory
{
    protected array $positions = [];

    public function getPositionsCount(): int
    {
        return count($this->positions);
    }

    public function getPosition(string $language = 'fa'): JobPosition
    {
        if(array_key_exists($language, $this->positions)) {
            return $this->positions[$language];
        }

        if($language === 'fa') {
            $position = new PersianJobPosition();
            $this->positions['fa'] = $position;
        } elseif ($language === 'en') {
            $position = new EnglishJobPosition();
            $this->positions['en'] = $position;
        }

        return $position;
    }
}

/*
 * Client:
 */
$jobPositionFactory = new JobPositionFactory();

$phpPersianPosition = $jobPositionFactory->getPosition('fa');
$phpPersianPosition->setTitle('استخدام برنامه نویس php');
$phpPersianPosition->setDescription('به یک همکار نیازمندیم!');
$phpPersianPosition->setAbilities([
    'مسلط به زبان PHP و فریم ورک Laravel',
    'مسلط به  MVC و design patterns',
    'تسلط کامل به دیتابیس MYSQL'
]);
$phpPersianPosition->setSalary(10000000);
$phpPersianPosition->setLeftDays(20);
echo $phpPersianPosition->render();

echo '<br>-----------------------------------------------------<br>';

$phpEnglishPosition = $jobPositionFactory->getPosition('en');
$phpEnglishPosition->setTitle('Hiring PHP Developer');
$phpEnglishPosition->setDescription('We`re Hiring!');
$phpEnglishPosition->setAbilities([
    'Work closely with the IT team in completing projects',
    'Troubleshoot and fix any issues relating to PHP programs',
    'Create scripts to facilitate client systems'
]);
$phpEnglishPosition->setSalary(4000);
$phpEnglishPosition->setLeftDays(25);
echo $phpEnglishPosition->render();

echo '<br>-----------------------------------------------------<br>';

$pythonEnglishPosition = $jobPositionFactory->getPosition('en');
$pythonEnglishPosition->setTitle('Hiring Python Developer');
$pythonEnglishPosition->setDescription('We`re Hiring!');
$pythonEnglishPosition->setAbilities([
    'Using Python in a cross-functional teams',
    'Write effective and scalable code',
    'Integrate user-facing elements into applications'
]);
$pythonEnglishPosition->setSalary(5000);
$pythonEnglishPosition->setLeftDays(30);
echo $pythonEnglishPosition->render();

echo '<br>-----------------------------------------------------<br>';

echo 'Number of job position class were created: ' . $jobPositionFactory->getPositionsCount();

/*
 * Output:
 *
                  * استخدام برنامه نویس php
                         * شرح موقعیت شغلی:
                   * به یک همکار نیازمندیم!

                   * توانایی های مورد نیاز:
    - مسلط به زبان PHP و فریم ورک Laravel
          - مسلط به MVC و design patterns
             - تسلط کامل به دیتابیس MYSQL

                     * حقوق: 10000000 تومان
                 * فرصت ارسال رزومه: 19 روز
 *
 * ---------------------------------------
 *
 * Hiring PHP Developer
 *
 * Job Position Description:
 * We`re Hiring!

 * Requirements:
 * - Work closely with the IT team in completing projects
 * - Troubleshoot and fix any issues relating to PHP programs
 * - Create scripts to facilitate client systems
 *
 * Salary: 4000 $
 * Days left for submit: 24 day
 *
 * ---------------------------------------
 *
 * Hiring Python Developer
 *
 * Job Position Description:
 * We`re Hiring!

 * Requirements:
 * - Using Python in a cross-functional teams
 * - Write effective and scalable code
 * - Integrate user-facing elements into applications
 *
 * Salary: 5000 $
 * Days left for submit: 29 day
 *
 * ----------------------------------------
 *
 * Number of job position class were created: 2
 */
