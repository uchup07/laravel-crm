<?php
namespace VentureDrake\LaravelCrm\Imports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Ramsey\Uuid\Uuid;
use VentureDrake\LaravelCrm\Models\Label;
use VentureDrake\LaravelCrm\Models\OrganisationType;
use VentureDrake\LaravelCrm\Models\Person;
use VentureDrake\LaravelCrm\Services\OrganisationService;

#[\AllowDynamicProperties]
class OrganisationsSheet implements ToCollection, WithValidation, SkipsOnFailure, WithHeadingRow, SkipsEmptyRows
{
    use Importable,SkipsFailures;
    
    /**
     * @var OrganisationService
     */
    private $organisationService;

    public function __construct(OrganisationService $organisationService)
    {
        //$this->organisationService = $organisationService;
        HeadingRowFormatter::default('none');
        $this->organisationService = $organisationService;
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

            if($row['ContactName'] != '') {
                $name = \VentureDrake\LaravelCrm\Http\Helpers\PersonName\firstLastFromName($row['ContactName']);

                $person = Person::create([
                    'external_id' => Uuid::uuid4()->toString(),
                    'first_name' => $name['first_name'],
                    'last_name' => $name['last_name'] ?? null,
                    'user_owner_id' => $req->user_owner_id,
                    'organisation_id' => $organisation->id,
                ]);

                if($row['ContactPhoneNumber'] != '') {
                    $person->phones()->create([
                        'external_id' => Uuid::uuid4()->toString(),
                        'number' => $row['ContactPhoneNumber'],
                        'type' => 'work',
                        'primary' => 1,
                    ]);
                }

                if($row['ContactEmailAddress'] != '') {
                    $person->emails()->create([
                        'external_id' => Uuid::uuid4()->toString(),
                        'address' => $row['ContactEmailAddress'],
                        'type' => 'work',
                        'primary' => 1,
                    ]);
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