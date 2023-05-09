<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use VentureDrake\LaravelCrm\Models\Organisation;


class OrganisationsSheet implements WithTitle, WithHeadings
{
    /**
     * @return Builder
     */
    /* public function query()
    {
        return Organisation::select(['name', 'description', 'organisation_type_id', 'user_owner_id'])->newQuery();
    } */

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Organizations';
    }

    public function headings(): array
    {
        $arr = [
            'Name',
            'Description',
            'OrganisationType',
            'Label',
            'OwnerID',
            'PhoneNumber',
            'EmailAddress',
        ];

        for($i=1;$i<=10;$i++) {
            array_push($arr, 'ContactName'.$i,'ContactPhoneNumber'.$i,'ContactEmailAddress'.$i);
        }

        return $arr;
    }
}