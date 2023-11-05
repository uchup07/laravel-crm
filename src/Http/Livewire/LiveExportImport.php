<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveExportImport extends Component
{
    use NotifyToast;
    use WithFileUploads;

    public function mount()
    {
        
    }

    public function render()
    {
        return view('laravel-crm::livewire.live-export-import')
            ->layout('laravel-crm::layouts.app');
    }
}