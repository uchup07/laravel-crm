<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use VentureDrake\LaravelCrm\Models\Organisation;
use VentureDrake\LaravelCrm\Models\OrganisationType;


class OrganisationSheet implements FromQuery, WithTitle, WithHeadings, WithMapping
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
        return Organisation::select(['id', 'name', 'organisation_type_id','created_at','updated_at'])->where('user_owner_id', $this->owner)->newQuery()->with(['labels','contacts','deals']);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Deals';
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Organisation Type',
            'Labels',
            'Open Deal',
            'Lost Deal',
            'Won Deal',
            'Created'
        ];
    }

    public function map($organisation): array
    {
        return [
            $organisation->id,
            $organisation->name,
            $organisation->organisationType->name ?? null,
            $this->labels($organisation),
            $organisation->deals->whereNull('closed_at')->count(),
            $organisation->deals->where('closed_status', 'lost')->count(),
            $organisation->deals->where('closed_status', 'won')->count(),
            $organisation->created_at->format('d-m-Y'),
        ];
    }

    public function fields(): array
    {
        return [
            'id',
            'name',
            'type',
            'labels',
            'open',
            'lost',
            'won',
            'created_at'
        ];
    }

    private function labels($organisation)
    {
        $arr = $organisation->labels->pluck('name')->all();

        return implode(', ', $arr);
    }
}