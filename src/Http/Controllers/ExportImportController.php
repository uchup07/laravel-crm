<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use App\Models\JobModel;
use Illuminate\Support\Facades\Request;

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

    public function export()
    {
        return view('laravel-crm::export-import.export', [
            
        ]);
    }

    public function exportExecute(Request $request)
    {

    }

    public function import()
    {
        return view('laravel-crm::export-import.import', [
            
        ]);
    }

    public function importExecute(Request $request)
    {

    }
}