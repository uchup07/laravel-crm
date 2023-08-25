<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use Illuminate\Http\Request;
use VentureDrake\LaravelCrm\Models\Lunch;

class LunchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Lunch::resetSearchValue($request);
        $params = Lunch::filters($request);

        if(auth()->user()->hasRole('Employee')) {
            $userOwners = \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(false);
            $params['user_owner_id'] = array_keys($userOwners);
        }

        if (Lunch::filter($params)->get()->count() < 30) {
            $lunches = Lunch::filter($params)->latest()->get();
        } else {
            $lunches = Lunch::filter($params)->latest()->paginate(30);
        }

        return view('laravel-crm::lunches.index', [
            'lunches' => $lunches ?? [],
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
    public function show(Lunch $lunch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Lunch $lunch)
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
    public function update(Request $request, Lunch $lunch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lunch $lunch)
    {
        //
    }
}
