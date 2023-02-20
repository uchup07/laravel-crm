<?php

namespace VentureDrake\LaravelCrm\Imports;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use VentureDrake\LaravelCrm\Models\Organisation;
use VentureDrake\LaravelCrm\Services\OrganisationService;

class OrganisationsImport implements ToCollection, WithValidation, WithHeadingRow
{
    use Importable;

    /**
     * @var OrganisationService
     */
    private $organisationService;

    public function __construct(OrganisationService $organisationService)
    {
        $this->organisationService = $organisationService;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            
        }
    }

    public function rules(): array
    {
        return [
            'Name' => 'required|max:255',
            'Description' => 'nullable',
            'OrganisationType' => 'required',
            'OwnerID' => 'required|exists:users,id',
            
        ];
    }
}