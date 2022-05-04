<?php

class Cv
{
    private string $cv_page;
    private string $title;
    private string $fullname;
    private string $birth_date;
    private string $marital_status;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setFullname(string $fullname): void
    {
        $this->fullname = $fullname;
    }

    public function setBirthDate(string $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    public function setMaritalStatus(string $marital_status): void
    {
        $this->marital_status = $marital_status;
    }

    public function formatEnglishCv(): void
    {
        $this->cv_page  = "-----------------------------";
        $this->cv_page .= "<h3>{$this->title}</h3>";
        $this->cv_page .= "<p>fullname: {$this->fullname}</p>";
        $this->cv_page .= "<p>birth date: {$this->birth_date}</p>";
        $this->cv_page .= "<p>marital status: {$this->marital_status}</p>";
        $this->cv_page .= "-----------------------------";
    }

    public function formatPersianCv(): void
    {
        $this->cv_page  = "-----------------------------";
        $this->cv_page .= "<h3>{$this->title}</h3>";
        $this->cv_page .= "<p>نام و نام خوانوادگی: {$this->fullname}</p>";
        $this->cv_page .= "<p>تاریخ تولد: {$this->birth_date}</p>";
        $this->cv_page .= "<p>وضعیت تاهل: {$this->marital_status}</p>";
        $this->cv_page .= "-----------------------------";
    }

    public function showCv(): string
    {
        return $this->cv_page;
    }
}

interface CvBuilderInterface
{
    public function setTitle(string $title): void;
    public function setFullname(string $fullname): void;
    public function setBirthDate(string $birth_date): void;
    public function setMaritalStatus(string $marital_status): void;
    public function formatCv(): void;
    public function getCv(): Cv;
}

// A builder for create english cv from Cv class
class EnglishCvBuilder implements CvBuilderInterface
{
    private Cv $cv;

    public function __construct()
    {
        $this->cv = new Cv();
    }

    public function setTitle(string $title): void
    {
        $this->cv->setTitle($title);
    }

    public function setFullname(string $fullname): void
    {
        $this->cv->setFullname($fullname);
    }

    public function setBirthDate(string $birth_date): void
    {
        $this->cv->setBirthDate($birth_date);
    }

    public function setMaritalStatus(string $marital_status): void
    {
        $this->cv->setMaritalStatus($marital_status);
    }

    public function formatCv(): void
    {
        $this->cv->formatEnglishCv();
    }

    public function getCv(): Cv
    {
        return $this->cv;
    }
}

// A builder for create persian cv from Cv class
class PersianCvBuilder implements CvBuilderInterface
{
    private Cv $cv;

    public function __construct()
    {
        $this->cv = new Cv();
    }

    public function setTitle(string $title): void
    {
        $this->cv->setTitle($title);
    }

    public function setFullname(string $fullname): void
    {
        $this->cv->setFullname($fullname);
    }

    public function setBirthDate(string $birth_date): void
    {
        $this->cv->setBirthDate($birth_date);
    }

    public function setMaritalStatus(string $marital_status): void
    {
        $this->cv->setMaritalStatus($marital_status);
    }

    public function formatCv(): void
    {
        $this->cv->formatPersianCv();
    }

    public function getCv(): Cv
    {
        return $this->cv;
    }
}

interface CvDirectorInterface
{
    public function buildCV(): void;
    public function getCV(): CV;
}

class CvDirector implements CvDirectorInterface
{
    private CvBuilderInterface $builder;

    public function __construct(CvBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function buildCv(): void
    {
        $this->builder->formatCv();
    }

    public function getCV(): CV
    {
        return $this->builder->getCV();
    }
}

// Client Code
$englishCvData = [
    'title' => 'Back End Developer',
    'fullname' => 'Mohammad Akbarzadeh',
    'birth_date' => '1995-05-12',
    'marital_status' => 'single'
];

$persianCvData = [
    'title' => 'توسعه دهنده سایت',
    'fullname' => 'محمد اکبرزاده',
    'birth_date' => '1374-11-05',
    'marital_status' => 'مجرد'
];

function getEnglishCv(array $data): void
{
    $englishCvBuilder = new EnglishCvBuilder();

    $englishCvBuilder->setTitle($data['title']);
    $englishCvBuilder->setFullname($data['fullname']);
    $englishCvBuilder->setBirthDate($data['birth_date']);
    $englishCvBuilder->setMaritalStatus($data['marital_status']);

    $englishCvDirector = new CvDirector($englishCvBuilder);
    $englishCvDirector->buildCv();
    $englishCv = $englishCvDirector->getcv();
    print_r($englishCv->showCv());
}

function getPersianCv(array $data): void
{
    $persianCvBuilder = new PersianCvBuilder();
    $persianCvBuilder->setTitle($data['title']);
    $persianCvBuilder->setFullname($data['fullname']);
    $persianCvBuilder->setBirthDate($data['birth_date']);
    $persianCvBuilder->setMaritalStatus($data['marital_status']);

    $persianCvDirector = new CvDirector($persianCvBuilder);
    $persianCvDirector->buildCv();
    $persianCv = $persianCvDirector->getcv();
    print_r($persianCv->showCv());
}

getEnglishCv($englishCvData);
getPersianCv($persianCvData);

// Output:
//-----------------------------
//Back End Developer
//fullname: Mohammad Akbarzadeh
//
//birth date: 1995-05-12
//
//marital status: single
//
//----------------------------------------------------------
//توسعه دهنده سایت
//نام و نام خوانوادگی: محمد اکبرزاده
//
//تاریخ تولد: 1374-11-05
//
//وضعیت تاهل: مجرد
//
//-----------------------------