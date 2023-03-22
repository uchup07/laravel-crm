<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrganisationErrorsSheet implements FromCollection, WithTitle, WithHeadings
{
    protected $items;
    public function __construct($data)
    {
        $this->items = $data;
    }
    
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
            'Label',
            'OwnerID',
            'PhoneNumber',
            'EmailAddress',
            'ContactName',
            'ContactPhoneNumber',
            'ContactEmailAddress',
            'Reason'
        ];
    }

    public function collection()
    {
        // TODO: Implement collection() method.
        return $this->items;
    }
}