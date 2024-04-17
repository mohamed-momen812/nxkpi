<?php

namespace App\Exports;

use App\Models\Entry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EntriesExport implements FromCollection, WithMapping, WithHeadings
{
    private $entries;

    public function __construct($entries)
    {
        $this->entries = $entries;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->entries;
    }

    public function headings(): array
    {
        return [
            'Entry Date',
            'User',
            'KPI ID',
            'KPI Description',
            'Frequency',
            'Direction',
            'Actual',
            'Target',
            'Notes'
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->entry_date,
            $entry->user->name,
            $entry->kpi->id,
            $entry->Kpi->description,
            $entry->kpi->frequency->name,
            $entry->kpi->direction,
            $entry->actual,
            $entry->target,
            $entry->notes
        ];
    }
}
