<?php

/*
 * The Subject interface
 */
interface Viewer
{
    public function getWordsCount(): int;
    public function getLinesCount(): int;
    public function getFileSize(): int;
}

/*
 * The Real Subject class
 */
class TextViewer implements Viewer
{
    protected int $wordsCount;
    protected int $linesCount;
    protected int $fileSize;

    public function __construct($path)
    {
        /* Get text file all words count */
        $this->wordsCount = str_word_count(file_get_contents($path));

        /* Get text file size in bytes */
        $this->fileSize = filesize($path);

        /* Get text file all lines count */
        $textFile = fopen($path, 'r') or die('Unable to open file!');

        $linescount = 0;

        while(!feof($textFile)){
            $line = fgets($textFile);
            $linescount++;
        }


        $this->linesCount = $linescount;

        fclose($textFile);
    }

    public function getWordsCount(): int { return $this->wordsCount; }

    public function getLinesCount(): int { return $this->linesCount; }

    public function getFileSize(): int { return $this->fileSize; }
}

/*
 * The Proxy class
 */
class TextViewerProxy implements Viewer
{
    private ?Viewer $viewer = NULL;

    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function checkViewer(): void
    {
        $this->viewer ?? $this->makeTextViewer();
    }

    public function getWordsCount(): int
    {
        $this->checkViewer();

        return $this->viewer->getWordsCount();
    }

    public function getLinesCount(): int
    {
        $this->checkViewer();

        return $this->viewer->getLinesCount();
    }

    public function getFileSize(): int
    {
        $this->checkViewer();

        return $this->viewer->getFileSize();
    }

    public function makeTextViewer()
    {
        $this->viewer = new TextViewer($this->path);
    }
}

/*
 * Client:
 */
$viewer = new TextViewerProxy('../Assets/textFile.txt');
echo 'Text file all words count: ' . $viewer->getWordsCount() . '<br>';
echo 'Text file all lines count: ' . $viewer->getLinesCount() . '<br>';
echo 'Text file size in bytes: '   . $viewer->getFileSize()   . '<br>';

/*
 * Output:
 *
 * Text file all words count: 92
 * Text file all lines count: 8
 * Text file size in bytes: 503
*/
