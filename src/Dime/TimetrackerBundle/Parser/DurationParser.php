<?php

namespace Dime\TimetrackerBundle\Parser;

/**
 * a duration parser
 *
 * Example:
 * [+-]02:30:15    => [sign: [+-], number: 9015]
 * [+-]2h 30m 15s  => [sign: [+-], number: 9015]
 * [+-]2,5h        => [sign: [+-], number: 9000]
 * [+-]2.5h        => [sign: [+-], number: 9000]
 */
class DurationParser extends AbstractParser
{
    protected $regex = '/(?P<sign>[+-])?(?P<duration>(\d+((:\d+)?(:\d+)?)?[hms]?([,\.]\d+[h])?(\s+)?(\d+[ms])?(\s+)?(\d+[s])?)?)/';
    protected $matches = array();

    public function clean($input)
    {
        if (isset($this->matches[0])) {
            $input = trim(str_replace($this->matches[0], '', $input));
        }

        return $input;
    }

    public function run($input)
    {
        if (!empty($input) && preg_match($this->regex, $input, $this->matches)) {
            if (!empty($this->matches[0])) {
                $duration = 0;
                if (preg_match_all(
                    '/(?P<number>(\d+([,\.]\d+)?(:\d+)?))(?P<unit>[hms])/',
                    $this->matches['duration'],
                    $items
                )
                ) {
                    // 02:30h or 02:30m
                    if (count($items['unit']) == 1 && strstr($items['number'][0], ':') !== false) {
                        $time = explode(':', $items['number'][0]);
                        if (count($time) >= 2) {
                            switch ($items['unit'][0]) {
                                case 'h':
                                    $duration += $this->calcDuration($time[0], 'h');
                                    $duration += $this->calcDuration($time[1], 'm');
                                    break;
                                case 'm':
                                    $duration += $this->calcDuration($time[0], 'm');
                                    $duration += $this->calcDuration($time[1], 's');
                                    break;
                            }
                        }
                    } else { // 2h 30m 0s
                        foreach ($items['unit'] as $key => $unit) {
                            $items['number'][$key] = str_replace(',', '.', $items['number'][$key]);
                            $duration += $this->calcDuration($items['number'][$key], $unit);
                        }
                    }
                } else {
                    $time = explode(':', $this->matches['duration']);

                    if (isset($time[0])) {
                        $duration += $this->calcDuration($time[0], 'h');
                    }
                    if (isset($time[1])) {
                        $duration += $this->calcDuration($time[1], 'm');
                    }
                    if (isset($time[2])) {
                        $duration += $this->calcDuration($time[2], 's');
                    }
                }

                // check if already set and run operation
                if (isset($this->result['duration'])) {
                    if ($this->matches['sign'] == '-') {
                        $duration *= -1;
                    }

                    if ($this->result['duration']['sign'] == '-') {
                        $duration -= $this->result['duration']['number'];
                    } else {
                        $duration += $this->result['duration']['number'];
                    }
                }

                $this->result['duration'] = array(
                    'sign' => $this->matches['sign'],
                    'number' => $duration
                );
            }
        }

        return $this->result;
    }


    protected function calcDuration($number, $unit)
    {
        switch ($unit) {
            case 'h':
                return $number * 3600;
                break;
            case 'm':
                return $number * 60;
                break;
            case 's':
                return $number;
                break;
            default:
                return 0;
        }
    }
}
