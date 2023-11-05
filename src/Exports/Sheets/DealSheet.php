<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use VentureDrake\LaravelCrm\Models\Deal;
use VentureDrake\LaravelCrm\Models\OrganisationType;


class DealSheet implements FromQuery, WithTitle, WithHeadings, WithMapping
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
        return Deal::select(['id', 'title', 'amount','currency','closed_status','organisation_id','person_id','created_at','closed_at'])->where('user_owner_id', $this->owner)->newQuery()->with(['labels','organisation','person']);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Leads';
    }

    public function headings(): array
    {
        return [
            '#',
            'Title',
            'Value',
            'Organisation',
            'Contact Person',
            'Status',
            'Closed',
            'Created'
        ];
    }

    public function map($deal): array
    {
        return [
            $deal->id,
            $deal->title,
            (money($deal->amount, $deal->currency)),
            (($deal->organisation) ? $deal->organisation->name : ''),
            (($deal->person) ? $deal->person->name : ''),
            $deal->closed_status,
            (($deal->closed_at) ? $deal->closed_at->format('d-m-Y') : ''),
            $deal->created_at->format('d-m-Y'),
        ];
    }

    public function fields(): array
    {
        return [
            'id',
            'title',
            'amount',
            'organisation',
            'person',
            'status',
            'closed_at',
            'created_at'
        ];
    }
}