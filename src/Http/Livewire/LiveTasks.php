<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use VentureDrake\LaravelCrm\Models\Task;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveTasks extends Component
{
    use NotifyToast;
    use AuthorizesRequests;
    
    public $model;
    public $tasks;
    public $name;
    public $description;
    public $due_at;
    public $showForm = false;

    protected $listeners = [
        'addTaskActivity' => 'addTaskOn',
        'taskDeleted' => 'getTasks',
        'taskCompleted' => 'getTasks',
     ];

    public function mount($model)
    {
        $this->model = $model;
        $this->getTasks();
        
        if ($this->tasks->count() < 1) {
            $this->showForm = true;
        }
    }

    public function create()
    {
        $this->authorize('create', new Task());
        $data = $this->validate([
            'name' => 'required',
            'description' => 'nullable',
            'due_at' => 'nullable',
        ]);
        
        $task = $this->model->tasks()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'due_at' => $this->due_at,
            'user_owner_id' => auth()->user()->id,
            'user_assigned_id' => auth()->user()->id,
        ]);
        
        $this->model->activities()->create([
            'causeable_type' => auth()->user()->getMorphClass(),
            'causeable_id' => auth()->user()->id,
            'timelineable_type' => $this->model->getMorphClass(),
            'timelineable_id' => $this->model->id,
            'recordable_type' => $task->getMorphClass(),
            'recordable_id' => $task->id,
        ]);

        $this->notify(
            'Task created',
        );

        $this->resetFields();
    }
    
    public function getTasks()
    {
        $this->tasks = $this->model->tasks()->where('user_assigned_id', auth()->user()->id)->latest()->get();
        $this->emit('refreshActivities');
    }
    
    public function addTaskToggle()
    {
        $this->showForm = ! $this->showForm;

        $this->dispatchBrowserEvent('taskEditModeToggled');
    }
    
    public function addTaskOn()
    {
        $this->showForm = true;

        $this->dispatchBrowserEvent('taskAddOn');
    }

    private function resetFields()
    {
        $this->reset('name', 'description', 'due_at');
        $this->getTasks();
    }
    
    public function render()
    {
        $this->authorize('viewAny', new Task());
        return view('laravel-crm::livewire.tasks');
    }
}
