<?php

namespace App\Traits;

use Carbon\Carbon;

trait CustomValidateTrait
{
    /**
     * @param $date
     * @param $format
     * @return bool
     */
    protected function checkValidDate($date, $format = 'Y-m-d'): bool
    {
        try {
            $dateFormat = Carbon::createFromFormat($format, $date);

            return $dateFormat && $dateFormat->format($format) === $date;
        } catch (\Exception $e) {
            return false;
        }
    }
}
