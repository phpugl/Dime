<?php

namespace Dime\TimetrackerBundle\Parser;

/**
 * a time range parser
 *
 * Example:
 * 10:00-12:00 => [start: "10:00", stop: "12:00"]
 * 10-12       => [start: "10:00", stop: "12:00"]
 * 10:00-      => [start: "10:00", stop: ""]
 * -12:00      => [start: "", stop: "12:00"]
 */
class TimerangeParser extends AbstractParser
{
    protected $regex = array(
        '/(?P<start>\d+(?::\d+)?)\s*-\s*(?P<stop>\d+(?::\d+)?)/',
        '/((?P<start>\d+(?::\d+)?)\s*-)/',
        '(-\s*(?P<stop>\d+(?::\d+)?))'
    );
    protected $matches = array();
    protected $matched = false;

    protected function appendMissingZeros($input)
    {
        if (strlen($input) > 0 && strstr($input, ':') === false) {
            $input .= ':00';
        }

        return $input;
    }

    public function clean($input)
    {
        if ($this->matched && isset($this->matches[0])) {
            $input = trim(str_replace($this->matches[0], '', $input));
        }

        return $input;
    }

    public function run($input)
    {
        for ($i = 0; $i < count($this->regex); $i++) {
            if (preg_match($this->regex[$i], $input, $this->matches)) {
                $start = isset($this->matches['start']) ? $this->matches['start'] : '';
                $stop = isset($this->matches['stop']) ? $this->matches['stop'] : '';

                if (!empty($start) || !empty($stop)) {
                    $this->matched = true;
                    $this->result['range'] = array(
                        'start' => $this->appendMissingZeros($start),
                        'stop' => $this->appendMissingZeros($stop)
                    );
                }

                break;
            }
        }

        return $this->result;
    }
}
