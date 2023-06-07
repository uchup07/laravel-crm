<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;
use Ramsey\Uuid\Uuid;
use VentureDrake\LaravelCrm\Models\File;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveFiles extends Component
{
    use NotifyToast;
    use WithFileUploads;
    use AuthorizesRequests;
    
    public $model;
    public $files;
    public $file;
    public $random;
    public $showForm = false;

    protected $listeners = [
        'addFileActivity' => 'addFileOn',
        'fileDeleted' => 'getFiles',
    ];

    public function mount($model)
    {
        $this->model = $model;
        $this->random = rand();
        $this->getFiles();

        if ($this->files->count() < 1) {
            $this->showForm = true;
        }
    }

    public function upload()
    {
        $this->authorize('create', new File());
        $data = $this->validate([
            'file' => 'required',
        ]);

        $file = $this->file->store('laravel-crm/'.strtolower(class_basename($this->model)).'/'.$this->model->id.'/files');
        
        $fileModel = $this->model->files()->create([
            'external_id' => Uuid::uuid4()->toString(),
            'file' => $file,
            'name' => $this->file->getClientOriginalName(),
            'filesize' => $this->file->getSize(),
            'mime' => $this->file->getMimeType(),
        ]);

        $this->model->activities()->create([
            'causable_type' => auth()->user()->getMorphClass(),
            'causable_id' => auth()->user()->id,
            'timelineable_type' => $this->model->getMorphClass(),
            'timelineable_id' => $this->model->id,
            'recordable_type' => $fileModel->getMorphClass(),
            'recordable_id' => $fileModel->id,
        ]);

        $this->notify(
            'File uploaded',
        );

        $this->dispatchBrowserEvent('fileUploaded');

        $this->resetFields();
    }
    
    public function getFiles()
    {
        $this->files = $this->model->files()->latest()->get();
        $this->emit('refreshActivities');
    }

    public function addFileToggle()
    {
        $this->showForm = ! $this->showForm;
        $this->dispatchBrowserEvent('addFileToggled');
    }

    public function addFileOn()
    {
        $this->showForm = true;
        $this->dispatchBrowserEvent('fileAddOn');
    }

    private function resetFields()
    {
        $this->reset('file');
        $this->random = rand();
        $this->getFiles();
    }
    
    public function render()
    {
        $this->authorize('viewAny', new File());
        return view('laravel-crm::livewire.files');
    }
}
