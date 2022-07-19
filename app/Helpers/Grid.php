<?php

namespace App\Helpers;

use Carbon\Carbon;

class Grid
{
    const TYPE_DAY = 'day';

    const TYPE_WEEK = 'week';

    const TYPE_MONTH = 'month';

    public $type;

    public $headings;

    public $steps;

    public $timestamps;

    public $dateString;

    public function __construct(Carbon $date, string $type)
    {
        $this->type = in_array($type, [self::TYPE_DAY, self::TYPE_WEEK, self::TYPE_MONTH])
            ? $type
            : self::TYPE_DAY;

        $date = $date->startOfDay();

        $this->timestamps = $this->makeTimestampsFromDate($date);

        $this->dateString = $this->getDateString($this->timestamps);

        $this->headings = $this->getHeadings($date);

        $this->steps = $this->getSteps();
    }

    private function makeTimestampsFromDate(Carbon $date)
    {
        $timestamps = [
            'current' => (int) now()->format('U'),
            'start' => (int) $date->format('U'),
            'end' => (int) $date->copy()->{'add'.ucfirst($this->type)}()->format('U'),
        ];

        $timestamps['duration'] = $timestamps['end'] - $timestamps['start'];

        return $timestamps;
    }

    private function getSteps()
    {
        return [
            self::TYPE_DAY => 48,
            self::TYPE_WEEK => 56,
            self::TYPE_MONTH => count($this->headings),
        ][$this->type];
    }

    private function getHeadings(Carbon $date)
    {
        return $this->{'create'.ucfirst($this->type).'Headings'}($date);
    }

    private function createDayHeadings(Carbon $date)
    {
        return array_map(function ($number) {
            return str_pad($number, 2, '0', STR_PAD_LEFT);
        }, range(0, 23));
    }

    private function createWeekHeadings(Carbon $date)
    {
        return $this->createDateRangeHeadings($date->copy(), $date->copy()->addWeek(), 'D d');
    }

    private function createMonthHeadings(Carbon $date)
    {
        return $this->createDateRangeHeadings($date->copy(), $date->copy()->addMonth(), 'd');
    }

    private function createDateRangeHeadings(Carbon $startDate, Carbon $endDate, string $format)
    {
        $headings = [];

        do {
            $headings[] = $startDate->format($format);
        } while ($startDate->addDay()->notEqualTo($endDate));

        return $headings;
    }

    private function getDateString(array $timestamps)
    {
        return $this->{'create'.ucfirst($this->type).'DateString'}($timestamps);
    }

    private function createDayDateString(array $timestamps)
    {
        return Carbon::parse($timestamps['start'])->format('Y-m-d');
    }

    private function createWeekDateString(array $timestamps)
    {
        return sprintf('%s - %s',
            Carbon::parse($timestamps['start'])->format('Y-m-d'),
            Carbon::parse($timestamps['end'])->subDay()->format('Y-m-d')
        );
    }

    private function createMonthDateString(array $timestamps)
    {
        return sprintf('%s - %s',
            Carbon::parse($timestamps['start'])->format('Y-m-d'),
            Carbon::parse($timestamps['end'])->subDay()->format('Y-m-d')
        );
    }
}
