<?php

namespace VentureDrake\LaravelCrm\Http\Livewire\Components;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use VentureDrake\LaravelCrm\Models\File;
use VentureDrake\LaravelCrm\Services\SettingService;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveFile extends Component
{
    use NotifyToast;

    private $settingService;
    public $showRelated = false;
    use AuthorizesRequests;
    
    public $file;

    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }
    
    public function mount(File $file)
    {
        $this->file = $file;

        if($this->settingService->get('show_related_activity')->value == 1){
            $this->showRelated = true;
        }
    }

    public function download()
    {
        return Storage::disk($this->file->disk)->download($this->file->file, $this->file->name);
    }

    public function delete()
    {
        $this->authorize('delete', $this->file);
        $this->file->delete();
        
        $this->emit('fileDeleted');
        $this->notify(
            'File deleted.'
        );
    }
    
    public function render()
    {
        $this->authorize('view', $this->file);
        return view('laravel-crm::livewire.components.file');
    }
}
