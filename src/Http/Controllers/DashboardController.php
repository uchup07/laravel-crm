<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use VentureDrake\LaravelCrm\Models\Client;
use VentureDrake\LaravelCrm\Models\Deal;
use VentureDrake\LaravelCrm\Models\Delivery;
use VentureDrake\LaravelCrm\Models\Invoice;
use VentureDrake\LaravelCrm\Models\Lead;
use VentureDrake\LaravelCrm\Models\Order;
use VentureDrake\LaravelCrm\Models\Organisation;
use VentureDrake\LaravelCrm\Models\Person;
use VentureDrake\LaravelCrm\Models\Quote;

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

        // Birthday person
        $birthdays = Person::where('user_owner_id', auth()->user()->id)->whereMonth('birthday',Carbon::now()->month)->get();

        return view('laravel-crm::index', [
            'totalLeadsCount' => Lead::count(),
            'myTotalLeadsCount' => Lead::where('user_owner_id', auth()->user()->id)->whereNull('converted_at')->count(),
            'totalDealsCount' => Deal::count(),
            'myTotalDealsCount' => Deal::where('user_owner_id', auth()->user()->id)->count(),
            'myTotalWonDealsCount' => Deal::where(['user_owner_id' => auth()->user()->id, 'closed_status' => 'won'])->count(),
            'myTotalLostDealsCount' => Deal::where(['user_owner_id' => auth()->user()->id, 'closed_status' => 'lost'])->count(),
            'totalPeopleCount' => Person::count(),
            'myTotalPeopleCount' => Person::where('user_owner_id', auth()->user()->id)->count(),
            'totalOrganisationsCount' => Organisation::count(),
            'myTotalOrganisationsCount' => Organisation::where('user_owner_id', auth()->user()->id)->count(),
            'personBirthday' => $birthdays,
            'usersOnline' => $usersOnline,
            'createdLast14Days' => json_encode($datasheet),
            'totalQuotesCount' => Quote::count(),
            'totalOrdersCount' => Order::count(),
            'totalInvoicesCount' => Invoice::count(),
            'totalDeliveriesCount' => Delivery::count(),
            'totalClientsCount' => Client::count(),
            'usersOnline' => $usersOnline,
            'createdLast14Days' => json_encode($datasheet),
        ]);
    }
}
