<?php

namespace VentureDrake\LaravelCrm\Http\Livewire\Components;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use VentureDrake\LaravelCrm\Models\File;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveFile extends Component
{
    use NotifyToast;
    use AuthorizesRequests;
    
    public $file;

    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];
    
    public function mount(File $file)
    {
        $this->file = $file;
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
