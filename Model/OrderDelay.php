<?php

namespace DelayManagement\Model;

use DelayManagement\Model\Base\OrderDelay as BaseOrderDelay;

/**
 * Skeleton subclass for representing a row from the 'order_delay' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class OrderDelay extends BaseOrderDelay
{
    const TYPES = [
        'NONE' => 'Aucun retard',
        'LATE' => 'En retard',
        'VERY_LATE' => 'Très en retard',
        'EXTREME_LATE' => 'Extrèmement en retard'
    ];

    public function getPrettyType()
    {
        if (!array_key_exists($this->getType(), self::TYPES)) {
            return $this->getType();
        }

        return self::TYPES[$this->getType()];
    }
}
