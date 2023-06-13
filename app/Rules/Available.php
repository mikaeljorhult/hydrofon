<?php

namespace App\Rules;

use App\Models\Booking;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Available implements ValidationRule
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
     */
    public function __construct(mixed $startTime, mixed $endTime, int $ignore = 0, string $column = '')
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
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Fail validation if timestamps are invalid.
        if ($this->startTime == null || $this->endTime == null) {
            $fail('The :attribute timestamps are invalid.');
        }

        // Check if any bookings collide with requested resources within timestamps.
        $available = ! Booking::where(! empty($this->column) ? $this->column : $attribute, $value)
                        ->where('id', '!=', $this->ignore)
                        ->between($this->startTime, $this->endTime)
                        ->exists();

        if (!$available) {
            $fail('The resource is not available during the given time frame.');
        }
    }
}
