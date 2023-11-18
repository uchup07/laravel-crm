<?php

namespace VentureDrake\LaravelCrm\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use VentureDrake\LaravelCrm\Exports\Sheets\LabelsSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\PeopleSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\LeadSheet;
use VentureDrake\LaravelCrm\Exports\Sheets\DealSheet;
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
                    new LeadSheet($this->owner),
                    new LabelsSheet(),
                ];
                break;
            case 'VentureDrake\LaravelCrm\Models\Deal':
                $sheets = [
                    new DealSheet($this->owner),
                    new LabelsSheet(),
                ];
                break;
                
            case 'VenturaDrake\LaravelCrm\Models\People':
                $sheets = [
                    new PeopleSheet($this->owner),
                    new LabelsSheet()
                ];
                break;

        }

        return $sheets;
    }
}