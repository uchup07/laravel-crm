<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use VentureDrake\LaravelCrm\Models\Deal;
use VentureDrake\LaravelCrm\Models\Lead;
use VentureDrake\LaravelCrm\Models\Organisation;
use VentureDrake\LaravelCrm\Models\Person;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (config('laravel-crm.teams')) {
            if (auth()->user()->currentTeam) {
                $usersOnline = auth()->user()->currentTeam->allUsers();
                foreach ($usersOnline as $userKey => $user) {
                    if ($user->last_online_at <= Carbon::now()->subMinutes(20)->toDateString()) {
                        $usersOnline->forget($userKey);
                    }
                }
            } else {
                $usersOnline = [];
            }
        } else {
            $usersOnline = \App\User::whereDate('last_online_at', '>=', Carbon::now()->subMinutes(20)->toDateString())->get();
        }
        

        $today = today();
        $startDate = today()->subdays(14);
        $period = CarbonPeriod::create($startDate, $today);
        $datasheet = [];

        // Iterate over the period
        foreach ($period as $date) {
            $datasheet[$date->format('d/m/Y')] = [];
            $datasheet[$date->format('d/m/Y')]["daily"] = [];
            $datasheet[$date->format('d/m/Y')]["daily"]["date"] = $date->format('d/m/Y');
            $datasheet[$date->format('d/m/Y')]["daily"]["leads"] = 0;
            $datasheet[$date->format('d/m/Y')]["daily"]["deals"] = 0;
        }
        
        $leads = Lead::whereBetween('created_at', [$startDate, now()])->get();

        foreach ($leads as $lead) {
            $datasheet[$lead->created_at->format('d/m/Y')]["daily"]["leads"]++;
        }

        $deals = Deal::whereBetween('created_at', [$startDate, now()])->get();

        foreach ($deals as $deal) {
            $datasheet[$deal->created_at->format('d/m/Y')]["daily"]["deals"]++;
        }
        
        return view('laravel-crm::index', [
            'totalLeadsCount' => Lead::count(),
            'totalDealsCount' => Deal::count(),
            'totalPeopleCount' => Person::count(),
            'totalOrganisationsCount' => Organisation::count(),
            'usersOnline' => $usersOnline,
            'createdLast14Days' => json_encode($datasheet),
        ]);
    }
}
