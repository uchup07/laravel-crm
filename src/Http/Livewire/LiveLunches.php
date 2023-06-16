<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use VentureDrake\LaravelCrm\Models\Lunch;
use VentureDrake\LaravelCrm\Models\Person;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveLunches extends Component
{
    use NotifyToast;
    use AuthorizesRequests;

    public $model;
    public $lunches;
    public $name;
    public $description;
    public $start_at;
    public $finish_at;
    public $guests = [];
    public $location;
    public $showForm = false;

    public $people;

    protected $listeners = [
        'addLunchActivity' => 'addLunchOn',
        'lunchDeleted' => 'getLunches',
        'lunchCompleted' => 'getLunches',
     ];

    public function mount($model)
    {
        $this->model = $model;

        $this->getPeople();
        $this->getLunches();

        if ($this->lunches->count() < 1) {
            $this->showForm = true;
        }
    }

    public function create()
    {
        $this->authorize('create', new Lunch());
        $data = $this->validate([
            'name' => 'required',
            'description' => 'nullable',
            'start_at' => 'required',
            'finish_at' => 'required',
            'guests' => 'nullable',
            'location' => "nullable",
        ]);

        $lunch = $this->model->lunches()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'start_at' => $this->start_at,
            'finish_at' => $this->finish_at,
            'location' => $this->location,
            'user_owner_id' => auth()->user()->id,
            'user_assigned_id' => auth()->user()->id,
        ]);

        foreach ($this->guests as $personId) {
            if ($person = Person::find($personId)) {
                $lunch->contacts()->create([
                    'entityable_type' => $person->getMorphClass(),
                    'entityable_id' => $person->id,
                ]);
            }
        }

        $this->model->activities()->create([
            'causeable_type' => auth()->user()->getMorphClass(),
            'causeable_id' => auth()->user()->id,
            'timelineable_type' => $this->model->getMorphClass(),
            'timelineable_id' => $this->model->id,
            'recordable_type' => $lunch->getMorphClass(),
            'recordable_id' => $lunch->id,
        ]);

        $this->notify(
            ucfirst(trans('laravel-crm::lang.lunch_created'))
        );

        $this->resetFields();
    }

    public function getLunches()
    {
        $this->lunches = $this->model->lunches()->where('user_assigned_id', auth()->user()->id)->latest()->get();
        $this->emit('refreshActivities');
    }

    private function getPeople()
    {
        if($this->model->people) {
            $this->people = $this->model->people()->get();
        } else {
            $this->people = null;
        }
    }

    public function addLunchToggle()
    {
        $this->showForm = ! $this->showForm;

        $this->dispatchBrowserEvent('lunchEditModeToggled');
    }

    public function addLunchOn()
    {
        $this->showForm = true;

        $this->dispatchBrowserEvent('lunchAddOn');
    }

    private function resetFields()
    {
        $this->reset('name', 'description', 'start_at', 'finish_at', 'guests', 'location');

        $this->dispatchBrowserEvent('lunchFieldsReset');

        $this->addLunchToggle();
        
        $this->getLunches();
    }

    public function render()
    {
        $this->authorize('viewAny', new Lunch());
        return view('laravel-crm::livewire.lunches');
    }
}
