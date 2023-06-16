<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Ramsey\Uuid\Uuid;
use VentureDrake\LaravelCrm\Models\Note;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveNotes extends Component
{
    use NotifyToast;
    use AuthorizesRequests;

    public $model;
    public $notes;
    public $pinned;
    public $content;
    public $noted_at;
    public $showForm = false;

    protected $listeners = [
        'addNoteActivity' => 'addNoteOn',
        'noteDeleted' => 'getNotes',
        'notePinned' => 'getNotes',
        'noteUnpinned' => 'getNotes',
    ];

    public function mount($model, $pinned = false)
    {
        $this->model = $model;
        $this->pinned = $pinned;
        $this->getNotes();

        if ($this->notes->count() < 1) {
            $this->showForm = true;
        }
    }

    public function create()
    {
        $this->authorize('create', new Note());
        $data = $this->validate([
            'content' => 'required',
        ]);

        $note = $this->model->notes()->create([
            'external_id' => Uuid::uuid4()->toString(),
            'content' => $data['content'],
            'noted_at' => $this->noted_at,
        ]);

        $this->model->activities()->create([
            'causeable_type' => auth()->user()->getMorphClass(),
            'causeable_id' => auth()->user()->id,
            'timelineable_type' => $this->model->getMorphClass(),
            'timelineable_id' => $this->model->id,
            'recordable_type' => $note->getMorphClass(),
            'recordable_id' => $note->id,
        ]);

        $this->emit('noteAdded');

        $this->notify(
            'Note created',
        );

        $this->resetFields();
    }

    public function getNotes()
    {
        if ($this->pinned) {
            $this->notes = $this->model->notes()->where('pinned', 1)->latest()->get();
        } else {
            $this->notes = $this->model->notes()->latest()->get();
        }

        $this->emit('refreshActivities');
    }

    public function addNoteToggle()
    {
        $this->showForm = ! $this->showForm;

        $this->dispatchBrowserEvent('noteEditModeToggled');
    }

    public function addNoteOn()
    {
        $this->showForm = true;

        $this->dispatchBrowserEvent('noteAddOn');
    }

    private function resetFields()
    {
        $this->reset('content', 'noted_at');
        $this->getNotes();
    }

    public function render()
    {
        $this->authorize('viewAny', new Note());
        return view('laravel-crm::livewire.notes');
    }
}
