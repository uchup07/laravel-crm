<?php

namespace VentureDrake\LaravelCrm\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TransactionsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public $owner;

    public $model;

    public function __construct($model, $owner)
    {
        $this->model = $model;
        $this->owner = $owner;
    }

    public function model(array $row)
    {
        /* return new Transaction([
            'description' => $row['description'],
            'amount' => $row['amount'],
            'user_id' => $this->users[$row['user']],
            'created_at' => $row['created_at']
        ]); */
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}