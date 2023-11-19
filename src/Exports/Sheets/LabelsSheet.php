<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use VentureDrake\LaravelCrm\Models\Label;


class LabelsSheet implements FromQuery, WithTitle, WithHeadings, ShouldAutoSize
{
    /**
     * @return Builder
     */
    public function query()
    {
        return Label::select(['id', 'name'])->newQuery();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Label';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
        ];
    }
}