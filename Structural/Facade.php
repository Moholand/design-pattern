<?php

/*
 * Employer class
 */
class Employer
{
    protected string $name;
    protected string $slug;
    protected string $email;

    public function setName($name): void
    {
        $this->name = $name;

        $this->slug = SlugFacade::sluggable($name);
    }

    public function setEmail($email): void { $this->email = $email; }

    public function getName(): string { return $this->name; }

    public function getSlug(): string { return $this->slug; }

    public function getEmail(): string { return $this->email; }
}

class SlugFacade
{
    public static function sluggable($word): string
    {
        $lowerCaseWord = StringTools::convertToLower($word);
        $convertToSlug = StringTools::convertToSlug($lowerCaseWord);
        return DataBase::checkSimilarity($convertToSlug);
    }
}

class StringTools
{
    public static function convertToLower($word): string
    {
        return strtolower($word);
    }

    public static function convertToSlug($word): string
    {
        return str_replace(' ', '-', $word);
    }
}

class Database
{
    protected static array $companiesSlug = [
        'irantalent-company', 'snap-express-group', 'kasebi-group'
    ];

    public static function checkSimilarity($slug): string
    {
        foreach(self::$companiesSlug as $companySlug) {
            if($companySlug === $slug) {
                self::$companiesSlug[] = $newSlug = $slug . random_int(1, 100);

                return $newSlug;
            }
            return $slug;
        }
    }
}

/*
 * Client:
 */
function createEmployer(string $name, string $email)
{
    $employer = new Employer();
    $employer->setName($name);
    $employer->setEmail($email);

    echo 'Company Name: '  . $employer->getName()  . '<br />';
    echo 'Company Slug: '  . $employer->getSlug()  . '<br />';
    echo 'Company Email: ' . $employer->getEmail() . '<br />';
}

createEmployer('Digikala Group', 'digikala@digigroup.com');
createEmployer('Irantalent Company', 'irantalent@mail.com');

/*
 * Output:
 *
 * Company Name: Digikala Group
 * Company Slug: digikala-group
 * Company Email: digikala@digigroup.com
 *
 * Company Name: Irantalent Company
 * Company Slug: irantalent-company51
 * Company Email: irantalent@mail.com
 */
