<?php

/**
 * Singleton class
 */
class JobPosition
{
    private string $title = 'PHP | Laravel Developer';

    private static ?self $job = null;
    private static bool $isAvailable = true;

    private function __construct() {}

    static function getJob(): ?JobPosition
    {
        if (self::$isAvailable === true) {
            if (self::$job === null) {
                self::$job = new JobPosition();
            }
            self::$isAvailable = false;
            return self::$job;
        } else {
            return null;
        }
    }

    function fired(JobPosition $jobPosition): void
    {
        self::$isAvailable = true;
    }

    function getTitle(): string
    {
        return $this->title;
    }
}

class Candidate
{
    private ?JobPosition $currentJob;
    private bool $haveJob = false;

    function getJob(): void
    {
        $this->currentJob = JobPosition::getJob();

        if (!$this->currentJob) {
            $this->haveJob = false;
        } else {
            $this->haveJob = true;
        }
    }

    function fired(): void
    {
        $this->currentJob->fired($this->currentJob);
    }

    function getJobTitle(): string
    {
        if ($this->haveJob) {
            return $this->currentJob->getTitle();
        } else {
            return "I don't have a Job";
        }
    }
}

/**
 * Client
 */
$candidate1 = new Candidate();
$candidate2 = new Candidate();

/**
 * Candidate 1 get the job
 */
$candidate1->getJob();
print_r('Candidate 1 job title: ' . $candidate1->getJobTitle() . '<br />');

/**
 * Candidate 2 can not get the same job
 */
$candidate2->getJob();
print_r('Candidate 2 job title: ' . $candidate2->getJobTitle() . '<br />');

/**
 * Candidate 1 lost his job
 */
$candidate1->fired();

/**
 * Candidate 2 get the job
 */
$candidate2->getJob();
print_r('Candidate 2 job title: ' . $candidate2->getJobTitle());

/**
 * Output:
 *
 * Candidate 1 job title: PHP | Laravel Developer
 * Candidate 2 job title: I don't have a Job
 * Candidate 2 job title: PHP | Laravel Developer
 *
 */