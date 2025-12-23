<?php

namespace App\Helpers;

class AllConstant
{
   public static function DepositStatus($status)
    {
        return match($status) {
            self::PENDING   => 0,
            self::COMPLETED => 1,
            self::ACCEPTED  => 2,
            self::REJECTED  => 3,
            default         => 'Unknown',
        };
    }


}