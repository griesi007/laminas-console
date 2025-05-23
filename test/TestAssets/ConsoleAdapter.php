<?php

/**
 * @see       https://github.com/laminas/laminas-console for the canonical source repository
 * @copyright https://github.com/laminas/laminas-console/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-console/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Console\TestAssets;

use Laminas\Console\Adapter\AbstractAdapter;

/**
 * @group      Laminas_Console
 */
class ConsoleAdapter extends AbstractAdapter
{
    public $stream;

    public $autoRewind = true;

    public $testWidth = 80;

    public $testIsUtf8 = true;

    public $writtenData = [];

    /**
     * Construct.
     *
     * @param bool $autoRewind If rewinds the stream before read the next char/line
     */
    public function __construct($autoRewind = true)
    {
        $this->autoRewind = (bool) $autoRewind;
    }

    /**
     * Read a single line from the console input
     *
     * @param int $maxLength        Maximum response length
     * @return string
     */
    public function readLine($maxLength = 2048)
    {
        if ($this->autoRewind) {
            rewind($this->stream);
        }
        $line = stream_get_line($this->stream, $maxLength, PHP_EOL) ?: '';
        return rtrim($line, PHP_EOL);
    }

    /**
     * Read a single character from the console input
     *
     * @param string|null   $mask   A list of allowed chars
     * @return string
     */
    public function readChar($mask = null)
    {
        if ($this->autoRewind) {
            rewind($this->stream);
        }
        do {
            $char = fread($this->stream, 1);
        } while ("" === $char || ($mask !== null && !str_contains($mask, $char)));
        return $char;
    }

    /**
     * Force reported width for testing purposes.
     *
     * @param int $width
     * @return int
     */
    public function setTestWidth($width)
    {
        $this->testWidth = $width;
    }

    /**
     * Force reported utf8 capability.
     *
     * @param bool $isUtf8
     */
    public function setTestUtf8($isUtf8)
    {
        $this->testIsUtf8 = $isUtf8;
    }

    public function isUtf8()
    {
        return $this->testIsUtf8;
    }

    public function getWidth()
    {
        return $this->testWidth;
    }

    /**
     * Tracks exactly what data has been written
     * @param string $text
     * @param null $color
     * @param null $bgColor
     */
    public function write($text, $color = null, $bgColor = null)
    {
        $this->writtenData[] = $text;
        parent::write($text, $color, $bgColor);
    }
}
