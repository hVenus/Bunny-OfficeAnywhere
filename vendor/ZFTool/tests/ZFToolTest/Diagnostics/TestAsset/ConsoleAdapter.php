<?php
namespace ZFToolTest\Diagnostics\TestAssets;

use Zend\Console\Adapter\AbstractAdapter;
use Zend\Console\Charset\Ascii;

class ConsoleAdapter extends AbstractAdapter
{
    public $stream;

    public $autoRewind = true;

    public $testWidth = 80;

    public $testIsUtf8 = true;

    /**
     * Read a single line from the console input
     *
     * @param int $maxLength        Maximum response length
     * @return string
     */
    public function readLine($maxLength = 2048)
    {
        if($this->autoRewind) {
            rewind($this->stream);
        }
        $line = stream_get_line($this->stream, $maxLength, PHP_EOL);
        return rtrim($line,"\n\r");
    }

    /**
     * Read a single character from the console input
     *
     * @param string|null   $mask   A list of allowed chars
     * @return string
     */
    public function readChar($mask = null)
    {
        if($this->autoRewind) {
            rewind($this->stream);
        }
        do {
            $char = fread($this->stream, 1);
        } while ("" === $char || ($mask !== null && false === strstr($mask, $char)));
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
}
