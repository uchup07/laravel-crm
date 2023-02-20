<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use VentureDrake\LaravelCrm\Models\OrganisationType;


class OrganisationTypesSheet implements FromQuery, WithTitle, WithHeadings
{
    /**
     * @return Builder
     */
    public function query()
    {
        return OrganisationType::select(['id', 'name'])->newQuery();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Organization Type';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
        ];
    }
}