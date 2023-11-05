<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use VentureDrake\LaravelCrm\Models\Lead;
use VentureDrake\LaravelCrm\Models\OrganisationType;


class LeadSheet implements FromQuery, WithTitle, WithHeadings, WithMapping
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
        return Lead::select(['id', 'title', 'amount','currency','lead_status_id','organisation_id','person_id','created_at'])->where('user_owner_id', $this->owner)->newQuery()->with(['labels','organisation','person']);
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
            'Created'
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->title,
            (money($lead->amount, $lead->currency)),
            (($lead->organisation) ? $lead->organisation->name : ''),
            (($lead->person) ? $lead->person->name : ''),
            $lead->created_at->format('d-m-Y'),
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
            'created_at'
        ];
    }
}