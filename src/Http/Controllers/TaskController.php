<?php

namespace VentureDrake\LaravelCrm\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use VentureDrake\LaravelCrm\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Task::resetSearchValue($request);
        $params = Task::filters($request);

        if(auth()->user()->hasRole('Employee')) {
            $userOwners = \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(false);
            $params['user_owner_id'] = array_keys($userOwners);
        }

        if (Task::filter($params)->get()->count() < 30) {
            $tasks = Task::filter($params)->latest()->get();
        } else {
            $tasks = Task::filter($params)->latest()->paginate(30);
        }
        
        return view('laravel-crm::tasks.index', [
            'tasks' => $tasks ?? [],
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();

        flash(ucfirst(trans('laravel-crm::lang.task_deleted')))->success()->important();

        return redirect(route('laravel-crm.tasks.index'));
    }

    public function complete(Task $task)
    {
        $task->update([
            'completed_at' => Carbon::now(),
        ]);

        flash(ucfirst(trans('laravel-crm::lang.task_completed')))->success()->important();

        return redirect(route('laravel-crm.tasks.index'));
    }
}
