<?php

namespace VentureDrake\LaravelCrm\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use VentureDrake\LaravelCrm\Exports\Sheets\OrganisationsSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\OrganisationTypesSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\UsersSheet;

class OrganisationsExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new OrganisationsSheet(),
            new OrganisationTypesSheet(),
            new UsersSheet()
        ];

        return $sheets;
    }
}