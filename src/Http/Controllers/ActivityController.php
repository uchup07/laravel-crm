<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use Illuminate\Http\Request;
use VentureDrake\LaravelCrm\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Activity::resetSearchValue($request);
        $params = Activity::filters($request);

        if(isset($params['user_owner_id'])) {
            $params['causeable_id'] = $params['user_owner_id'];
        }

        if(auth()->user()->hasRole('Employee')) {
            $userOwners = \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(false);
            $params['causeable_id'] = array_keys($userOwners);
        }

        if (Activity::filter($params)->get()->count() < 30) {
            $activities = Activity::filter($params)->latest()->get();
        } else {
            $activities = Activity::filter($params)->latest()->paginate(30);
        }

        return view('laravel-crm::activities.index', [
            'activities' => $activities ?? [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        //
    }
}
