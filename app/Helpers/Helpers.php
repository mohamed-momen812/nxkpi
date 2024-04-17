<?php

use App\Exports\EntriesExport;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

function exportCSV($data, $directory, $type)
{

    if (!File::isDirectory($directory)) {
        File::makeDirectory($directory, 0755, true, true);
    }

    if($type == 'entries'){
        return Excel::store(new EntriesExport($data), $directory);
    }

}
