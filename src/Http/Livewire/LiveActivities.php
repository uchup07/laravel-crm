<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use VentureDrake\LaravelCrm\Models\Organisation;
use VentureDrake\LaravelCrm\Models\Person;
use Livewire\Component;
use VentureDrake\LaravelCrm\Models\Activity;
use VentureDrake\LaravelCrm\Services\SettingService;

class LiveActivities extends Component
{
    private $settingService;
    public $model;
    public $activities = [];

    public $people;

    protected $listeners = [
        'refreshActivities' => 'getActivities',
    ];

    public function boot(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }
    
    public function mount($model)
    {
        $this->model = $model;

        $this->getPeople();
        $this->getActivities();
    }

    public function getActivities()
    {
        $activityIds = [];

        foreach($this->model->activities()->latest()->get() as $activity){
            $activityIds[] =  $activity->id;
        }

        if($this->settingService->get('show_related_activity')->value == 1 && method_exists($this->model, 'contacts')){
            foreach($this->model->contacts as $contact) {
                foreach ($contact->entityable->activities()->latest()->get() as $activity) {
                    $activityIds[] = $activity->id;
                }
            }
        }

        if(count($activityIds) > 0){
            $this->activities = Activity::whereIn('id', $activityIds)->latest()->get();
        }
    }

    private function getPeople()
    {
        $this->people = null;
        if($this->model->people) {
            $peoples = $this->model->people()->get();

            if($peoples->count() > 0) {
                $this->people = $peoples;
            } else {
                $contacts = $this->model->contacts()->where('entityable_type','LIKE','%Person%')->get();
                if($contacts) {
                    $this->people = $contacts->map(function($item) {
                       return $item->entityable;
                    });
                }
            }
        }
    }

    public function render()
    {
        return view('laravel-crm::livewire.activities');
    }
}
