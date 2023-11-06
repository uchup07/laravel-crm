<?php

namespace VentureDrake\LaravelCrm\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use VentureDrake\LaravelCrm\Imports\TransactionsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;

    public $owner;

    public $uploadFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $owner, $uploadFile)
    {
        $this->model = $model;
        $this->owner = $owner;
        $this->uploadFile = $uploadFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::import(new TransactionsImport($this->model, $this->owner), $this->uploadFile);
    }
}