<?php

namespace App\Services;

use App\Models\User;

class ReputationService
{
    const TYPE_SUBMIT = 'submit';

    const TYPE_VERIFIED = 'verify';

    const TYPE_RESOLVE = 'resolve';

    const TYPE_LOW_QUALITY = 'low_quality';

    const TYPE_DUPLICATE = 'duplicate';

    public static function addReputationHistory($report, $recordType)
    {
        $amount = self::getAmountByType($recordType);
        $user = User::find($report->user_id);
        if ($amount === null) {
            throw new \InvalidArgumentException("Invalid record type: $recordType");
        }

        $user->reputationHistory()->create([
            'report_id' => $report->id,
            'type' => $recordType,
            'amount' => $amount,
        ]);

        $user->reputation = $user->reputation + $amount;
        $user->save();
    }

    private static function getAmountByType($recordType)
    {
        switch ($recordType) {
            case self::TYPE_SUBMIT:
                return 5; // Example value
            case self::TYPE_VERIFIED:
                return 15; // Example value
            case self::TYPE_RESOLVE:
                return 20; // Example value
            case self::TYPE_LOW_QUALITY:
                return -10; // Example value
            case self::TYPE_DUPLICATE:
                return -5; // Example value
            default:
                return null;
        }
    }
}
