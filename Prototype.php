<?php

/**
 * Job position base class
 */
abstract class JobPosition
{
    protected string $type;
    protected string $title;
    protected DateTime $created_at;

    abstract public function adsUp(): void;
    abstract public function numOfAds(): string;

    public function __clone()
    {
        $this->created_at = new \DateTime();
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedDate(): DateTime
    {
        return $this->created_at;
    }
}

/**
 * Full time job class
 */
class FullTimeJob extends JobPosition
{
    protected int $numOfAds = 0;

    public function __construct()
    {
        $this->type = 'FullTime';
    }

    public function adsUp(): void
    {
        $this->numOfAds++;
    }

    public function numOfAds(): string
    {
        return $this->numOfAds . ' Advertisement from full time job posted! <br />';
    }
}

/**
 * Half time job class
 */
class HalfTimeJob extends JobPosition
{
    protected int $numOfAds = 0;

    public function __construct()
    {
        $this->type = 'HalfTime';
    }

    public function adsUp(): void
    {
        $this->numOfAds++;
    }

    public function numOfAds(): string
    {
        return $this->numOfAds . ' Advertisement from half time job posted! <br />';
    }
}

/**
 * Client: function for create and show advertisement
 */
function makeAdvertise(JobPosition $jobClass, string $title): void
{
    $jobClassClone = clone $jobClass;
    $jobClassClone->setTitle($title);

    $jobClass->adsUp();

    echo 'We need ' . $jobClassClone->getTitle() . ' as a ' . $jobClassClone->getType() . ' job.<br />';
    echo 'Posted at: ' . $jobClassClone->getCreatedDate()->format('Y-m-d') . '<br />';
    echo 'expired at: ' . $jobClassClone->getCreatedDate()->add(new DateInterval('P1M'))->format('Y-m-d') . '<br />';
    echo '-------------------' . '<br />';
}

$fullTimeJob = new FullTimeJob();
$halfTimeJob = new HalfTimeJob();

makeAdvertise($fullTimeJob, 'Python Developer');
makeAdvertise($fullTimeJob, 'PHP Developer');
makeAdvertise($halfTimeJob, 'PHP Developer');

echo $fullTimeJob->numOfAds();
echo $halfTimeJob->numOfAds();

/**
 * Output:
 */

/**
 * We need Python Developer as a FullTime job.
 * Posted at: 2022-04-22
 * expired at: 2022-05-22
 * -------------------
 * We need PHP Developer as a FullTime job.
 * Posted at: 2022-04-22
 * expired at: 2022-05-22
 * -------------------
 * We need PHP Developer as a HalfTime job.
 * Posted at: 2022-04-22
 * expired at: 2022-05-22
 * -------------------
 * 2 Advertisement from full time job posted!
 * 1 Advertisement from half time job posted!
 */