<?php

namespace App\Rules;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class Available implements Rule
{
    /**
     * Start of time span to check.
     *
     * @var \Carbon\Carbon
     */
    private $startTime;

    /**
     * End of time span to check.
     *
     * @var \Carbon\Carbon
     */
    private $endTime;

    /**
     * ID of booking to ignore.
     *
     * @var int
     */
    private $ignore;

    /**
     * Column name in database.
     *
     * @var string
     */
    private $column;

    /**
     * Create a new rule instance.
     *
     * @param  mixed  $startTime
     * @param  mixed  $endTime
     * @param  int  $ignore
     * @param  string  $column  = ''
     */
    public function __construct($startTime, $endTime, int $ignore = 0, string $column = '')
    {
        // Try to parse the supplied timestamps.
        try {
            $this->startTime = Carbon::parse($startTime);
            $this->endTime = Carbon::parse($endTime);
        } catch (\Exception $exception) {
            $this->startTime = null;
            $this->endTime = null;
        }

        $this->ignore = $ignore;
        $this->column = $column;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Fail validation if timestamps is invalid.
        if ($this->startTime == null || $this->endTime == null) {
            return false;
        }

        // Check if any bookings collide with requested resources within timestamps.
        return ! Booking::where(! empty($this->column) ? $this->column : $attribute, $value)
                        ->where('id', '!=', $this->ignore)
                        ->between($this->startTime, $this->endTime)
                        ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The resource is not available during the given time frame.';
    }
}
