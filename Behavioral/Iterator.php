<?php
/*
 * Candidate class
 */
class Candidate
{
    private string $fullName;
    private int $yearsOfExperience;
    private bool $hasDegree;
    private bool $isNative;
    private int $points = 0;

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function setYearsOfExperience(int $yearsOfExperience): void
    {
        $this->yearsOfExperience = $yearsOfExperience;
    }

    public function setHasDegree(int $hasDegree): void
    {
        $this->hasDegree = $hasDegree;
    }

    public function setIsNative(int $isNative): void
    {
        $this->isNative = $isNative;
    }

    public function calculatePoint(): void
    {
        $points = 0;

        if($this->getYearsOfExperience() > 5) {
            $points += 70;
        } elseif($this->getYearsOfExperience() <= 5 && $this->getYearsOfExperience() > 3) {
            $points += 40;
        } elseif($this->getYearsOfExperience() <= 3 && $this->getYearsOfExperience() > 1) {
            $points += 20;
        }

        if($this->hasDegree()) {
            $points += 10;
        }

        if($this->isNative()) {
            $points += 20;
        }

        $this->points = $points;
    }

    public function getFullName(): string { return $this->fullName; }

    public function getYearsOfExperience(): int { return $this->yearsOfExperience; }

    public function hasDegree(): int { return $this->hasDegree; }

    public function isNative(): int { return $this->isNative; }

    public function getPoints(): int { return $this->points; }
}

/*
 * Collection interface
 */
interface Collection
{
    public function getIterator(): IteratorInterface;
}

/*
 * Candidate collection class
 */
class CandidateCollection implements Collection
{
    private array $candidates = array();

    public function addCandidate(Candidate $candidate): void
    {
        $this->candidates[] = $candidate;
    }

    public function getCandidatesCount(): int
    {
        return count($this->candidates);
    }

    public function getCandidates(): array
    {
        return $this->candidates;
    }

    public function getIterator(): IteratorInterface
    {
        return new CandidatesIterator($this);
    }
}

/*
 * Iterator interface
 */
interface IteratorInterface
{
    public function getCurrent(): Candidate;
    public function next(): void;
    public function hasNext(): bool;
}

/*
 * Iterator class
 */
class CandidatesIterator implements IteratorInterface
{
    private CandidateCollection $collection;
    private int $index = 0;

    public function __construct(CandidateCollection $collection)
    {
        $this->collection = $collection;
    }

    public function getCurrent(): Candidate
    {
        return $this->collection->getCandidates()[$this->index];
    }

    public function next(): void
    {
        $this->index += 1;
    }

    public function hasNext(): bool
    {
        return $this->index < $this->collection->getCandidatesCount();
    }
}

/*
 * Client
 */
function insertCandidates(array $candidates): CandidateCollection
{
    $candidateCollection = new CandidateCollection();

    foreach($candidates as $specifications) {

        $candidate = new Candidate();
        $candidate->setFullName($specifications['fullName']);
        $candidate->setYearsOfExperience($specifications['yearsOfExperience']);
        $candidate->setHasDegree($specifications['hasDegree']);
        $candidate->setIsNative($specifications['isNative']);
        $candidate->calculatePoint();

        $candidateCollection->addCandidate($candidate);
    }

    return $candidateCollection;
}

function selectCandidates(CandidateCollection $collection, int $requiredPoints): array
{
    $selectedCandidates = array();

    $iterator = $collection->getIterator();

    do {
        $candidate = $iterator->getCurrent();

        if($candidate->getPoints() >= $requiredPoints) {
            $selectedCandidates[] = $candidate;
        }

        $iterator->next();

    } while($iterator->hasNext());

    return $selectedCandidates;
}

$candidates = [
    [ 'fullName' => 'hesam mousavi',  'yearsOfExperience' => 4, 'hasDegree' => true,  'isNative' => true ],
    [ 'fullName' => 'ali sabeti',     'yearsOfExperience' => 2, 'hasDegree' => true,  'isNative' => true ],
    [ 'fullName' => 'arman ramezani', 'yearsOfExperience' => 3, 'hasDegree' => false, 'isNative' => true ],
    [ 'fullName' => 'arman ramezani', 'yearsOfExperience' => 3, 'hasDegree' => false, 'isNative' => true ],
    [ 'fullName' => 'saba moradi',    'yearsOfExperience' => 3, 'hasDegree' => false, 'isNative' => true ],
    [ 'fullName' => 'julia robert',   'yearsOfExperience' => 5, 'hasDegree' => true,  'isNative' => false ],
];

$collection = insertCandidates($candidates);

$selectedCandidates = selectCandidates($collection, 50);

print_r($selectedCandidates);

/*
 * Output:
 *
 * Array (
 *          [0] => Candidate Object (
 *                                      [fullName:Candidate:private] => hesam mousavi
 *                                      [yearsOfExperience:Candidate:private] => 4
 *                                      [hasDegree:Candidate:private] => 1
 *                                      [isNative:Candidate:private] => 1
 *                                      [points:Candidate:private] => 70
 *                                  )
 *          [1] => Candidate Object (
 *                                      [fullName:Candidate:private] => ali sabeti
 *                                      [yearsOfExperience:Candidate:private] => 2
 *                                      [hasDegree:Candidate:private] => 1
 *                                      [isNative:Candidate:private] => 1
 *                                      [points:Candidate:private] => 50
 *                                   )
 *          [2] => Candidate Object (
 *                                      [fullName:Candidate:private] => julia robert
 *                                      [yearsOfExperience:Candidate:private] => 5
 *                                      [hasDegree:Candidate:private] => 1
 *                                      [isNative:Candidate:private] =>
 *                                      [points:Candidate:private] => 50
 *                                   )
 *       )
 */
