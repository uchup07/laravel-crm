<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Livewire\Component;
use Ramsey\Uuid\Uuid;
use VentureDrake\LaravelCrm\Models\Organisation;

class LiveRelatedContactOrganisation extends Component
{
    public $model;
    public $contacts;
    public $organisation_id;
    public $organisation_name;

    public function mount($model)
    {
        $this->model = $model;
        $this->getContacts();
    }

    public function link()
    {
        $data = $this->validate([
            'organisation_name' => 'required',
        ]);

        if ($this->organisation_id) {
            $organisation = Organisation::find($this->organisation_id);
        } else {
            $organisation = Organisation::create([
                'external_id' => Uuid::uuid4()->toString(),
                'name' => $data['organisation_name'],
                'user_owner_id' => auth()->user()->id,
            ]);
        }
        
        $this->model->contacts()->create([
            'external_id' => Uuid::uuid4()->toString(),
            'entityable_type' => $organisation->getMorphClass(),
            'entityable_id' => $organisation->id,
        ]);

        $this->resetFields();

        $this->getContacts();

        $this->dispatchBrowserEvent('linkedOrganisation');
    }

    public function remove($id)
    {
        if ($organisation = Organisation::find($id)) {
            $this->model->contacts()
                ->where([
                    'entityable_type' => $organisation->getMorphClass(),
                    'entityable_id' => $organisation->id,
                ])
                ->delete();
        }

        $this->getContacts();

        $this->dispatchBrowserEvent('linkedOrganisation');
    }

    public function updatedOrganisationName($value)
    {
        $this->dispatchBrowserEvent('updatedNameFieldAutocomplete');
    }

    private function getContacts()
    {
        $this->contacts = $this->model
            ->contacts()
            ->where('entityable_type', 'LIKE', '%Organisation%')
            ->get();
    }

    private function resetFields()
    {
        $this->reset('organisation_id', 'organisation_name');
    }
    
    public function render()
    {
        return view('laravel-crm::livewire.related-contact-organisations');
    }
}
