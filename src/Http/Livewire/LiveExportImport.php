<?php

namespace VentureDrake\LaravelCrm\Http\Livewire;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use VentureDrake\LaravelCrm\Jobs\ExportJob;
use VentureDrake\LaravelCrm\Traits\NotifyToast;

class LiveExportImport extends Component
{
    use NotifyToast;
    use WithFileUploads;

    public $batchId;
    public $exporting = false;
    public $exportFinished = false;
    public $model;
    public $owner;

    public $file;

    public function mount()
    {
        
    }

    public function export()
    {
        $this->validate([
            'model' => 'required',
            'owner' => 'required|exists:users,id'
        ]);

        $this->exporting = true;
        $this->exportFinished = false;

        $batch = Bus::batch([
            new ExportJob($this->model, $this->owner),
        ])->dispatch();

        $this->batchId = $batch->id;
    }

    public function getExportBatchProperty()
    {
        if (!$this->batchId) {
            return null;
        }

        return Bus::findBatch($this->batchId);
    }

    public function downloadExport()
    {
        return Storage::download('public/transactions.csv');
    }

    public function updateExportProgress()
    {
        $this->exportFinished = $this->exportBatch->finished();

        if ($this->exportFinished) {
            $this->exporting = false;
        }
    }

    public function render()
    {
        return view('laravel-crm::livewire.live-export-import')
            ->layout('laravel-crm::layouts.app');
    }
}