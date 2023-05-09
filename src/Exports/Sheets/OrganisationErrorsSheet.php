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

        array_push($arr, 'Reason');

        return $arr;
    }

    public function collection()
    {
        // TODO: Implement collection() method.
        return $this->items;
    }
}