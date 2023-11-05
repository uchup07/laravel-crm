<?php

namespace VentureDrake\LaravelCrm\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use VentureDrake\LaravelCrm\Exports\TransactionExport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    
    public $owner;

    public $filename;

    public function __construct($model, $owner, $filename)
    {
        $this->model = $model;
        $this->owner = $owner;
        $this->filename = $filename;
    }

    public function handle()
    {
        (new TransactionExport($this->model, $this->owner))->store("public/{$this->filename}.xlsx");
    }
}