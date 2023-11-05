<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use App\Models\JobModel;

class ExportImportController extends Controller
{
    public function index()
    {
        if(JobModel::all()->count() < 30) {
            $jobs = JobModel::latest()->get();
        } else {
            $jobs = JobModel::latest()->paginate(30);
        }

        return view('laravel-crm::export-import.index', [
            'jobs' => $jobs ?? [],
        ]);
    }
}