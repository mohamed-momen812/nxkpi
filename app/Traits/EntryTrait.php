<?php

namespace App\Traits;

use App\Models\Entry;
use Carbon\Carbon;

trait EntryTrait
{
    public function getLastWeekData()
    {
        $oneWeekAgo = Carbon::now()->subWeek();

        $data = Entry::where('created_at', '>=', $oneWeekAgo)->get();
    }
}
