<?php

namespace App\Traits;

use DateTime;

trait DateTimeDiffTrait {

    public function DateTimeDiff($start, $end) {

        $dteStart = new DateTime($start);
        $dteEnd   = new DateTime($end);

        $dteDiff  = $dteStart->diff($dteEnd);

        return $dteDiff->format("%H:%I:%S").' hour(s)';

    }

}
