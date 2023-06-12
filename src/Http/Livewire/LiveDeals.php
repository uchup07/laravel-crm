<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use VentureDrake\LaravelCrm\Models\Deal;
use VentureDrake\LaravelCrm\Traits\NotifyToast;
use Illuminate\Database\Eloquent\Collection;

class LiveDeals extends Component
{
    use NotifyToast;

    public $model;
    public Collection $deals;
    public $reason;
    public $closed_status;
    public $closed_at;
    public $deal_id;

    public $listeners = [
        'refreshComponent' => '$refresh',
        'dealUpdated' => '$refresh'
    ];

    public function edit($id, $status){
//        $this->model = $deal;
        $this->deal_id = $id;
        $this->closed_status = $status;
        $this->dispatchBrowserEvent('editEvent');
    }

    /**
     * Returns validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'reason' => "required",
            'closed_at' => 'required',
            'closed_status' => 'required',
        ];
    }

    public function store() {
        $this->validate();
        $deal = Deal::find($this->deal_id);
        
        if($deal) {
            $deal->update([
               'reason' => $this->reason,
               'closed_at' => $this->closed_at,
               'closed_status' => $this->closed_status 
            ]);
        }
        
        $this->notify(
            'Deal Updated',
        );
        $this->emit('dealUpdated');
        

        $this->resetFields();
    }

    public function mount($deals)
    {
        $this->deals = $deals;
        
    }

    public function resetFields() {
        $this->reset('reason', 'closed_at','closed_status');
    }

    public function render()
    {
        return view('laravel-crm::livewire.deals');
    }
}