<?php

namespace Dime\TimetrackerBundle\Parser;

abstract class AbstractParser
{
    protected $result = array();

    /**
     * Set result to prefill the results array.
     *
     * @param string $input
     * @return Parser
     */
    public function setResult(array $result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Clean input string with matched content.
     *
     * @param string $input
     * @return string
     */
    abstract public function clean($input);

    /**
     * Run parser with input string.
     *
     * @param string $input
     * @return array
     */
    abstract public function run($input);
}
