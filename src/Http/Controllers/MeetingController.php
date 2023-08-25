<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use Illuminate\Http\Request;
use VentureDrake\LaravelCrm\Models\Meeting;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Meeting::resetSearchValue($request);
        $params = Meeting::filters($request);

        if(auth()->user()->hasRole('Employee')) {
            $userOwners = \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(false);
            $params['user_owner_id'] = array_keys($userOwners);
        }

        if (Meeting::filter($params)->get()->count() < 30) {
            $meetings = Meeting::filter($params)->latest()->get();
        } else {
            $meetings = Meeting::filter($params)->latest()->paginate(30);
        }

        return view('laravel-crm::meetings.index', [
            'meetings' => $meetings ?? [],
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
    public function show(Meeting $meeting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Meeting $meeting)
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
    public function update(Request $request, Meeting $meeting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meeting $meeting)
    {
        //
    }
}
