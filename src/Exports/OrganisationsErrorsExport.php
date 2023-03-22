<?php

namespace VentureDrake\LaravelCrm\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use VentureDrake\LaravelCrm\Exports\Sheets\LabelsSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\OrganisationErrorsSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\OrganisationsSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\OrganisationTypesSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\UsersSheet;

class OrganisationsErrorsExport implements WithMultipleSheets
{
    protected $items;
    public function __construct($data)
    {
        $this->items = $data;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new OrganisationErrorsSheet($this->items),
            new OrganisationTypesSheet(),
            new LabelsSheet(),
            new UsersSheet()
        ];

        return $sheets;
    }
}