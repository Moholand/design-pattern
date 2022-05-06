<?php

/*
 * Abstraction
 */
abstract class View
{
    protected Resource $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function changeResource(Resource $resource): void
    {
        $this->resource = $resource;
    }

    public abstract function show(): string;
}

/*
 * Concrete Abstraction
 */
class DesktopView extends View
{
    public function show(): string
    {
        return 'Image url: ' . $this->resource->image() . '<br />' .
               '---------------------------------' . '<br />' .
               'Name & Email: ' . $this->resource->introduction() . '<br />' .
               'Description: ' . $this->resource->description() . '<br />' .
               '---------------------------------' . '<br />' .
               'Website: ' . $this->resource->website() . '<br />';
    }
}

class MobileView extends View
{
    public function show(): string
    {
        return 'Image url: ' . $this->resource->image() . '<br />' .
               '---------------------------------' . '<br />' .
               'Name & Email: ' . $this->resource->introduction() . '<br />' .
               '---------------------------------' . '<br />' .
               'Website: ' . $this->resource->website() . '<br />';
    }
}

/*
 * Implementor Interface
 */
interface Resource
{
    public function image(): string;
    public function introduction(): string;
    public function description(): string;
    public function website(): string;
}

/*
 * Concrete Implementor
 */
class EmployerResource implements Resource
{
    protected Employer $employer;

    public function __construct(Employer $employer)
    {
        $this->employer = $employer;
    }

    public function image(): string
    {
        return $this->employer->getCompanyCover();
    }

    public function introduction(): string
    {
        return $this->employer->getCompanyName() . '<br />' .
               $this->employer->getCompanyEmail();
    }

    public function description(): string
    {
        return $this->employer->getCompanyDescription();
    }

    public function website(): string
    {
        return $this->employer->getCompanyWebsite();
    }
}

class CandidateResource implements Resource
{
    protected Candidate $candidate;

    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }

    public function image(): string
    {
        return $this->candidate->getImage();
    }

    public function introduction(): string
    {
        return $this->candidate->getName() . '<br />' .
            $this->candidate->getEmail();
    }

    public function description(): string
    {
        return $this->candidate->getBio();
    }

    public function website(): string
    {
        return $this->candidate->getWebsite();
    }
}

class Employer
{
    protected string $companyCover;
    protected string $companyName;
    protected string $companyEmail;
    protected string $companyDescription;
    protected string $companyWebsite;

    public function setCompanyCover(string $companyCover): void
    {
        $this->companyCover = $companyCover;
    }

    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    public function setCompanyEmail(string $companyEmail): void
    {
        $this->companyEmail = $companyEmail;
    }

    public function setCompanyDescription(string $companyDescription): void
    {
        $this->companyDescription = $companyDescription;
    }

    public function setCompanyWebsite(string $companyWebsite): void
    {
        $this->companyWebsite = $companyWebsite;
    }

    public function getCompanyCover(): string { return $this->companyCover; }

    public function getCompanyName(): string { return $this->companyName; }

    public function getCompanyEmail(): string { return $this->companyEmail; }

    public function getCompanyDescription(): string { return $this->companyDescription; }

    public function getCompanyWebsite(): string { return $this->companyWebsite; }
}

class Candidate
{
    protected string $image;
    protected string $name;
    protected string $email;
    protected string $bio;
    protected string $website;

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setBio(string $bio): void
    {
        $this->bio = $bio;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function getImage(): string { return $this->image; }

    public function getName(): string { return $this->name; }

    public function getEmail(): string { return $this->email; }

    public function getBio(): string { return $this->bio; }

    public function getWebsite(): string { return $this->website; }
}

/*
 * Client
 */
function displayUser(View $view): void
{
    echo $view->show();
}

$employer = new Employer();
$candidate = new Candidate();

$employer->setCompanyCover('https://www.google.com/url?sa=i&url');
$employer->setCompanyName('Irantalent');
$employer->setCompanyDescription('2730 job opportunities waiting for you to apply');
$employer->setCompanyEmail('irantalent@irantalent.com');
$employer->setCompanyWebsite('https://www.irantalent.com');

$candidate->setImage('https://www.google.com/url?sa=i&url=https%iira.com');
$candidate->setName('mohammad');
$candidate->setEmail('mohammad@gmail.com');
$candidate->setBio('Software Engineer');
$candidate->setWebsite('https://www.moholand.com');

$employerResource = new EmployerResource($employer);
$candidateResource = new CandidateResource($candidate);

echo '** Desktop views:' . '<br />' .
     '=============================' . '<br />' .
     '* Employer Desktop view:' . '<br />' .
     '=============================' . '<br />';

$desktopView = new DesktopView($employerResource);
displayUser($desktopView);

echo '=============================' . '<br />' .
     '* Candidate Desktop view:' . '<br />' .
     '=============================' . '<br />';

$desktopView->changeResource($candidateResource);
displayUser($desktopView);

echo '=============================' . '<br />' .
     '** Mobile views:' . '<br />' .
     '=============================' . '<br />' .
     '* Employer Mobile view:' . '<br />' .
     '=============================' . '<br />';

$mobileView = new MobileView($employerResource);
displayUser($mobileView);

echo '=============================' . '<br />' .
     '* Candidate Mobile view:' . '<br />' .
     '=============================' . '<br />';

$mobileView->changeResource($candidateResource);
displayUser($mobileView);

/*
 * Output:
 *
 * ** Desktop views:
 * =============================
 * * Employer Desktop view:
 * =============================
 * Image url: https://www.google.com/url?sa=i&url
 * ---------------------------------
 * Name & Email: Irantalent
 * irantalent@irantalent.com
 * Description: 2730 job opportunities waiting for you to apply
 * ---------------------------------
 * Website: https://www.irantalent.com
 * =============================
 * * Candidate Desktop view:
 * =============================
 * Image url: https://www.google.com/url?sa=i&url=https%iira.com
 * ---------------------------------
 * Name & Email: mohammad
 * mohammad@gmail.com
 * Description: Software Engineer
 * ---------------------------------
 * Website: https://www.moholand.com
 * =============================
 * ** Mobile views:
 * =============================
 * * Employer Mobile view:
 * =============================
 * Image url: https://www.google.com/url?sa=i&url
 * ---------------------------------
 * Name & Email: Irantalent
 * irantalent@irantalent.com
 * ---------------------------------
 * Website: https://www.irantalent.com
 * =============================
 * * Candidate Mobile view:
 * =============================
 * Image url: https://www.google.com/url?sa=i&url=https%iira.com
 * ---------------------------------
 * Name & Email: mohammad
 * mohammad@gmail.com
 * ---------------------------------
 * Website: https://www.moholand.com
 *
 */
