<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Livewire\Component;
use VentureDrake\LaravelCrm\Models\Organisation;

class LiveDealForm extends Component
{
    public $person_id;
    public $person_name;
    public $organisation_id;
    public $organisation_name;
    public $lead_id;
    public $title;

    public function mount($deal)
    {
        $this->lead_id = old('lead_id') ?? $deal->lead->id ?? null;
        $this->person_id = old('person_id') ?? $deal->person->id ?? null;
        $this->person_name = old('person_name') ?? $deal->person->name ?? null;
        $this->organisation_id = old('organisation_id') ?? $deal->organisation->id ?? null;
        $this->organisation_name = old('organisation_name') ?? $deal->organisation->name ?? null;
        $this->title = old('title') ?? $deal->title ?? null;
    }

    public function updatedPersonName($value)
    {
        $data = [];
        if($this->organisation_id) {
            $organisation = Organisation::find($this->organisation_id);

            if($organisation) {
                $person = $organisation->people()->get();

                if($person) {
                    foreach($person as $p) {
                        $data[$p->name] = $p->id;
                    }
                }
            }
        }
        $this->dispatchBrowserEvent('updatedNameFieldAutocomplete',['people' => $data]);
    }

    public function updatedOrganisationName($value)
    {
//        Log::info('value: ' . $value);
        $data = [];
        if($this->organisation_id) {
            $organisation = Organisation::find($this->organisation_id);

            if($organisation) {
                $person = $organisation->people()->get();

                if($person) {
                    foreach($person as $p) {
                        $data[$p->name] = $p->id;
                    }
                }
            }
        }
        $this->dispatchBrowserEvent('updatedNameFieldAutocomplete',['people' => $data]);
    }

    public function render()
    {
        return view('laravel-crm::livewire.live-deal-form');
    }
}