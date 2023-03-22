<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use VentureDrake\LaravelCrm\Exports\OrganisationsErrorsExport;
use VentureDrake\LaravelCrm\Imports\OrganisationsImport;
use VentureDrake\LaravelCrm\Repositories\OrganisationRepository;
use VentureDrake\LaravelCrm\Services\OrganisationService;
use VentureDrake\LaravelCrm\Traits\NotifyToast;
use Maatwebsite\Excel\Facades\Excel;

class LiveImportFile extends Component
{
    use NotifyToast;
    use WithFileUploads;

    public $file;
    public $importing = false;
    public $importFinish = false;
    public $errors = [];
    public $download = '';
    public $organisations;

    public $listeners = [
        'organisationUpdated' => '$refresh'
    ];

    public function mount($organisations)
    {
        $this->organisations = $organisations;
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        $this->importing = true;
        $file = $this->file->store('laravel-crm/imports');

        $repository = new OrganisationRepository();
        $organisationService = new OrganisationService($repository);

//        try {
            $import = new OrganisationsImport($organisationService);
            $import->import($file);
//            $import->onlySheets('Organizations');
//            $tst = Excel::import($import, $file);
//            Log::info(print_r($tst->failures(),true));
//        } catch (\Exception $e) {
//            Log::error($e->getMessage());
            $failureValues = collect();
            $time = time();

            $failures = $import->failures();

            if($failures->isNotEmpty()) {
                $this->errors['message'] = 'The data received does not match.';
                $failCount = 0;
                foreach ($failures as $failure) {
                    $this->errors['errors'][$failCount] = $failure->toArray();
                    $failureValues->push([
                        'Name' => $failure->values()['Name'],
                        'Description' => $failure->values()['Description'],
                        'OrganisationType' => $failure->values()['OrganisationType'],
                        'Label' => $failure->values()['Label'],
                        'OwnerID' => $failure->values()['OwnerID'],
                        'PhoneNumber' => $failure->values()['PhoneNumber'],
                        'EmailAddress' => $failure->values()['EmailAddress'],
                        'ContactName' => $failure->values()['ContactName'],
                        'ContactPhoneNumber' => $failure->values()['ContactPhoneNumber'],
                        'ContactEmailAddress' => $failure->values()['ContactEmailAddress'],
                        'Reason' => collect($failure->errors())->implode(', ')
                    ]);
                    $failCount++;
                }
            }
//        }

        $this->emit('organisationUpdated');

        if(count($this->errors) > 0) {
            /* $output['status'] = false;
             $output['message'] = __('messages.error.new_data');
             $output['data'] = $errors;*/
            Excel::store(new OrganisationsErrorsExport($failureValues), 'exports/organisations-errors-'.$time.'.xlsx', 'public');
            $this->download = url('/storage/exports/organisations-errors-'.$time.'.xlsx');
            $this->notify($this->errors['message'], null, [], 'error');
        } else {
            $this->notify(
                'File imported',
            );
        }

        $this->dispatchBrowserEvent('fileUploaded');

        $this->resetFields();
    }

    private function resetFields()
    {
        $this->reset('file');


    }

    public function render()
    {
        return view('laravel-crm::livewire.import-file');
    }
}