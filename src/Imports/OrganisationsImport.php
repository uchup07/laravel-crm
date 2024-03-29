<?php

namespace VentureDrake\LaravelCrm\Imports;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use VentureDrake\LaravelCrm\Imports\Sheets\OrganisationsSheet;
use VentureDrake\LaravelCrm\Models\OrganisationType;
use VentureDrake\LaravelCrm\Services\OrganisationService;
// use Maatwebsite\Excel\Concerns\SkipsOnError;
// use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use Ramsey\Uuid\Uuid;
use VentureDrake\LaravelCrm\Models\Label;
use VentureDrake\LaravelCrm\Models\Person;

#[\AllowDynamicProperties]
class OrganisationsImport implements ToCollection, WithMultipleSheets, WithValidation, SkipsOnFailure, WithHeadingRow
{
    use Importable,SkipsFailures;

    /**
     * @var OrganisationService
     */
    private $organisationService;

    public function __construct(OrganisationService $organisationService)
    {
        $this->organisationService = $organisationService;
        HeadingRowFormatter::default('none');
    }

    public function sheets(): array
    {
        return [
            0 => $this
        ];
    }

    public function collection(Collection $rows)
    {
        foreach($rows as $row)
        {
            $orgType = OrganisationType::where('name', trim($row['OrganisationType']))->first();

            $req = collect();
            $req->name = $row['Name'];
            $req->organisation_type_id = $orgType->id ?? '';
            $req->description = $row['Description'];
            $req->user_owner_id = $row['OwnerID'];

            $req->phones = [];
            $req->phones[0]['number'] = $row['PhoneNumber'];
            $req->phones[0]['type'] = 'work';
            $req->phones[0]['primary'] = 1;
            $req->phones[0]['id'] = 0;
            $req->emails = [];
            $req->emails[0]['address'] = $row['EmailAddress'];
            $req->emails[0]['type'] = 'work';
            $req->emails[0]['primary'] = 1;
            $req->emails[0]['id'] = 0;
            $req->addresses = [];
            $req->labels = $row['Label'] ? array_map('trim',explode(',',$row['Label'])) : [];
            $labels = Label::whereIn('name', $req->labels)->get()->pluck('id');

            $organisation = $this->organisationService->create($req);

            $organisation->labels()->sync($labels ?? []);
            
            for($i=1;$i<=10;$i++) {
                if($row['ContactName' . $i] != '') {
                    $name = \VentureDrake\LaravelCrm\Http\Helpers\PersonName\firstLastFromName($row['ContactName' . $i]);

                    $person = Person::create([
                        'external_id' => Uuid::uuid4()->toString(),
                        'first_name' => $name['first_name'],
                        'last_name' => $name['last_name'] ?? null,
                        'user_owner_id' => $req->user_owner_id,
                        'organisation_id' => $organisation->id,
                    ]);

                    if($row['ContactPhoneNumber' . $i] != '') {
                        $person->phones()->create([
                            'external_id' => Uuid::uuid4()->toString(),
                            'number' => $row['ContactPhoneNumber' . $i],
                            'type' => 'work',
                            'primary' => 1,
                        ]);
                    }

                    if($row['ContactEmailAddress' . $i] != '') {
                        $person->emails()->create([
                            'external_id' => Uuid::uuid4()->toString(),
                            'address' => $row['ContactEmailAddress' . $i],
                            'type' => 'work',
                            'primary' => 1,
                        ]);
                    }
                }
            }

            
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