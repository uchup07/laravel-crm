<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use VentureDrake\LaravelCrm\Models\Organisation;


class OrganisationsSheet implements FromQuery, WithTitle, WithHeadings
{
    /**
     * @return Builder
     */
    public function query()
    {
        return Organisation::select(['name', 'description', 'organisation_type_id', 'user_owner_id'])->newQuery();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Organizations';
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'OrganisationType',
            'OwnerID',
            'Contact'
        ];
    }
}