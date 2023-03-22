<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Livewire\Component;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveOrganisations extends Component
{
    use NotifyToast;
    public $organisations;
    
    public $listeners = [
        'refreshComponent' => '$refresh',
        'organisationUpdated' => '$refresh'
    ];
    
    public function mount($organisations)
    {
        $this->organisations = $organisations;    
    }
    
    public function render()
    {
        return view('laravel-crm::livewire.organisations');
    }
}