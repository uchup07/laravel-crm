<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use VentureDrake\LaravelCrm\Models\Lunch;
use VentureDrake\LaravelCrm\Models\Person;
use VentureDrake\LaravelCrm\Services\SettingService;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveLunches extends Component
{
    use NotifyToast;
    use AuthorizesRequests;

    private $settingService;
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

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function mount($model)
    {
        $this->model = $model;

        $this->getPeople();
        $this->getLunches();

        if (!$this->lunches || ($this->lunches && $this->lunches->count() < 1)) {
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
        $lunchIds = [];

        foreach($this->model->lunches()->where('user_assigned_id', auth()->user()->id)->latest()->get() as $lunch){
            $lunchIds[] =  $lunch->id;
        }

        if($this->settingService->get('show_related_activity')->value == 1 && method_exists($this->model, 'contacts')){
            foreach($this->model->contacts as $contact) {
                foreach ($contact->entityable->lunches()->where('user_assigned_id', auth()->user()->id)->latest()->get() as $lunch) {
                    $lunchIds[] = $lunch->id;
                }
            }
        }

        if(count($lunchIds) > 0){
            $this->lunches = Lunch::whereIn('id', $lunchIds)->latest()->get();
        }
        
        $this->emit('refreshActivities');
    }

    private function getPeople()
    {
        $this->people = null;
        if($this->model->people) {
            $peoples = $this->model->people()->get();
            if($peoples->count() > 0 ) {
                $this->people = $peoples;
            } else {
                $contacts = $this->model->contacts()->where('entityable_type', 'LIKE', '%Person%')->get();
                if($contacts) {
                    $this->people = $contacts->map(function($item) {
                       return $item->entityable;
                    });
                }
            }
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
