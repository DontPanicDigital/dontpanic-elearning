<?php
namespace DontPanic\Exception;

abstract class DontPanicException extends \Exception implements IException
{

    /** @var string */
    protected $message = 'Unknown exception';

    /** @var int */
    protected $code = 0;

    /** @var string */
    protected $file;

    /** @var string */
    protected $line;

    /** @var stringr */
    private $trace;

    /** @var string */
    private $string;

    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown ' . get_class($this));
        }
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return sprintf("%s '%s' in %s(%s)\n%s", get_class($this), $this->message, $this->file, $this->line, $this->getTraceAsString());
    }
}