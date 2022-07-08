<?php
/*
 * Visitee interface
 */
interface Visitee
{
    public function accept(Visitor $visitor): string;
}

/*
 * Visitee concrete class
 */
class Company implements Visitee
{
    public string $name;
    public DateTime $created_at;

    public function accept(Visitor $visitor): string
    {
        return $visitor->visitCompany($this);
    }
}

class Employee implements Visitee
{
    public string $name;
    private array $experiences = [];

    public function setExperiences(array $experiences): void
    {
        foreach ($experiences as $experience) {
            $this->experiences[] = $experience;
        }
    }

    public function getExperiences(): array
    {
        return $this->experiences;
    }

    public function accept(Visitor $visitor): string
    {
        return $visitor->visitEmployee($this);
    }
}

/*
 * Visitor interface
 */
interface Visitor
{
    public function visitCompany(Company $company): string;
    public function visitEmployee(Employee $employee): string;
}

/*
 * Concrete visitor class
 */
class ExperienceReport implements Visitor
{
    public function visitCompany(Company $company): string
    {
        $age = $company->created_at->diff(new DateTime())->format("%y years, %m months and %d days");

        return $company->name . ' Has ' . $age . ' old!';
    }

    public function visitEmployee(Employee $employee): string
    {
        $totalYearsOfExperience = 0;

        foreach ($employee->getExperiences() as $experience) {
            $totalYearsOfExperience += $experience['totalYears'];
        }

        return $employee->name . ' Has ' . $totalYearsOfExperience . ' total years of experience!';
    }
}

/*
 * Client
 */
$experienceReports = new ExperienceReport();

$company = new Company();
$company->name = 'Moholand';
$company->created_at = new DateTime("2012-05-04");
echo $company->accept($experienceReports) . '<br>';

$employee = new Employee();
$employee->name = 'John Doe';
$employee->setExperiences([
    ['company_name' => 'Snap',       'totalYears' => 3],
    ['company_name' => 'Tapsi',      'totalYears' => 5],
    ['company_name' => 'Digikala',   'totalYears' => 1],
    ['company_name' => 'Irantalent', 'totalYears' => 2],
]);
echo $employee->accept($experienceReports);

/*
 * Output:
 *
 * Moholand Has 10 years, 2 months and 4 days old!
 *
 * John Doe Has 11 total years of experience!
 */
