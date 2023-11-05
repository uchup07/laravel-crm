<?php

namespace VentureDrake\LaravelCrm\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use VentureDrake\LaravelCrm\Exports\Sheets\LabelsSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\OrganisationsSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\OrganisationTypesSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\UsersSheet;

class TransactionExport implements WithMultipleSheets
{
    use Exportable;

    public $model;

    public $owner;

    public function __construct($model, $owner)
    {
        $this->model = $model;
        $this->owner = $owner;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        switch($this->model) {
            case 'VentureDrake\LaravelCrm\Models\Lead':
                $sheets = [
                    LeadSheet($this->owner),
                    LabelsSheet(),
                ];
                break;
            case 'VentureDrake\LaravelCrm\Models\Deal':
                
                break;

        }

        return $sheets;
    }
}