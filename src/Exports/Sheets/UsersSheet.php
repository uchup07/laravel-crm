<?php

namespace VentureDrake\LaravelCrm\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;


class UsersSheet implements FromQuery, WithTitle, WithHeadings
{
    /**
     * @return Builder
     */
    public function query()
    {
        return User::select(['id', 'name', 'email'])->where('crm_access', 1)->newQuery();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Users';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
        ];
    }
}