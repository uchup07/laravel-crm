<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use VentureDrake\LaravelCrm\Models\Meeting;
use VentureDrake\LaravelCrm\Models\Person;
use VentureDrake\LaravelCrm\Services\SettingService;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveMeetings extends Component
{
    use NotifyToast;
    use AuthorizesRequests;

    private $settingService;
    public $model;
    public $meetings;
    public $name;
    public $description;
    public $start_at;
    public $finish_at;
    public $guests = [];
    public $location;
    public $showForm = false;
    
    public $people;
    
    public $contacts;

    protected $listeners = [
        'addMeetingActivity' => 'addMeetingOn',
        'meetingDeleted' => 'getMeetings',
        'meetingCompleted' => 'getMeetings',
        'refreshContacts' => 'getContacts',
        '$refresh'
     ];

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function mount($model)
    {
        $this->model = $model;

        $this->getPeople();
        
        $this->getContacts();

        $this->getMeetings();

        if (! $this->meetings || ($this->meetings && $this->meetings->count() < 1)) {
            $this->showForm = true;
        }
    }

    public function create()
    {
        $this->authorize('create', new Meeting());
        $data = $this->validate([
            'name' => "required",
            'description' => "nullable",
            'start_at' => 'required',
            'finish_at' => 'required',
            'guests' => 'nullable',
            'location' => "nullable",
        ]);

        $meeting = $this->model->meetings()->create([
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
                $meeting->contacts()->create([
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
            'recordable_type' => $meeting->getMorphClass(),
            'recordable_id' => $meeting->id,
        ]);

        $this->notify(
            ucfirst(trans('laravel-crm::lang.meeting_created'))
        );

        $this->resetFields();
    }

    public function getMeetings()
    {
        $meetingIds = [];

        foreach($this->model->meetings()->where('user_assigned_id', auth()->user()->id)->latest()->get() as $meeting) {
            $meetingIds[] = $meeting->id;
        }

        if($this->settingService->get('show_related_activity')->value == 1 && method_exists($this->model, 'contacts')) {
            foreach($this->model->contacts as $contact) {
                foreach ($contact->entityable->meetings()->where('user_assigned_id', auth()->user()->id)->latest()->get() as $meeting) {
                    $meetingIds[] = $meeting->id;
                }
            }
        }

        if(count($meetingIds) > 0) {
            $this->meetings = Meeting::whereIn('id', $meetingIds)->latest()->get();
        }

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

    private function getContacts()
    {
        if($this->model->contacts) {
            $contacts = $this->model->contacts()->where('entityable_type','like','%Person')->get();

            foreach($contacts as $contact) {
                $this->contacts[$contact->entityable->id] = $contact->entityable->name;
            }
        }
    }

    public function addMeetingToggle()
    {
        $this->showForm = ! $this->showForm;

        $this->dispatchBrowserEvent('meetingEditModeToggled');
    }

    public function addMeetingOn()
    {
        $this->showForm = true;

        $this->dispatchBrowserEvent('meetingAddOn');
    }

    private function resetFields()
    {
        $this->reset('name', 'description', 'start_at', 'finish_at', 'guests', 'location');

        $this->dispatchBrowserEvent('meetingFieldsReset');

        $this->addMeetingToggle();

        $this->getMeetings();
    }

    public function render()
    {
        $this->authorize('viewAny', new Meeting());
        return view('laravel-crm::livewire.meetings');
    }
}
