<?php

namespace VentureDrake\LaravelCrm\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use App\Exports\TransactionExport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    
    public $owner;

    public function __construct($model, $owner)
    {
        $this->model = $model;
        $this->owner = $owner;
    }

    public function handle()
    {
        (new TransactionExport($this->model, $this->owner))->store('public/transactions.xlsx');
    }
}