<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;
use Ramsey\Uuid\Uuid;
use VentureDrake\LaravelCrm\Models\Call;
use VentureDrake\LaravelCrm\Models\File;
use VentureDrake\LaravelCrm\Services\SettingService;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveFiles extends Component
{
    use NotifyToast;
    use WithFileUploads;
	use AuthorizesRequests;
    private $settingService;
    public $model;
    public $files;
    public $file;
    public $random;
    public $showForm = false;

    protected $listeners = [
        'addFileActivity' => 'addFileOn',
        'fileDeleted' => 'getFiles',
    ];

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function mount($model)
    {
        $this->model = $model;
        $this->random = rand();
        $this->getFiles();

        if (!$this->files || ($this->files && $this->files->count() < 1)) {
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
            'causeable_type' => auth()->user()->getMorphClass(),
            'causeable_id' => auth()->user()->id,
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
        $fileIds = [];

        foreach($this->model->files()->latest()->get() as $file){
            $fileIds[] =  $file->id;
        }

        if($this->settingService->get('show_related_activity')->value == 1 && method_exists($this->model, 'contacts')){
            foreach($this->model->contacts as $contact) {
                foreach ($contact->entityable->files()->latest()->get() as $file) {
                    $fileIds[] = $file->id;
                }
            }
        }

        if(count($fileIds) > 0){
            $this->files = File::whereIn('id', $fileIds)->latest()->get();
        }
        
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
