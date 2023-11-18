<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use VentureDrake\LaravelCrm\Models\Person;
use VentureDrake\LaravelCrm\Models\Contact;


class PeopleSheet implements FromQuery, WithTitle, WithHeadings, WithMapping
{

    public $owner;

    public function __construct($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return Person::select(['id', 'first_name', 'last_name','middle_name','title','birthday','gender'])->where('user_owner_id', $this->owner)->newQuery()->with(['labels','organisation','contacts']);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'People';
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Birthday',
            'Gender',
            'Organization',
            'Labels',
            'Email',
            'Phone'
        ];
    }

    public function map($person): array
    {
        return [
            $person->id,
            $person->name,
            $person->birthday->format('Y-m-d'),
            $person->gender,
            $this->organisations($person),
            $this->labels($person),
            $person->getPrimaryEmail()->address ?? null,
            $person->getPrimaryPhone()->number ?? null,
        ];
    }

    public function fields(): array
    {
        return [
            'id',
            'name',
            'birthday',
            'gender',
            'organisation',
            'label',
            'email',
            'phone'
        ];
    }

    private function organisations($person)
    {
        $organisations = $person->contacts()->where('entityable_type','LIKE','%Organisation%')->get();
        
        $arr = [];
        if($organisations) {
            foreach($organisations as $organisation)
            {
                $arr[] = $organisation->entityable->name;
            }
        }

        return implode(', ', $arr);
    }

    private function labels($person)
    {
        $arr = $person->labels->pluck('name');

        return implode(', ', $arr);
    }
}