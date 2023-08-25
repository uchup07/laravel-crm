@extends('laravel-crm::layouts.app')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            @include('laravel-crm::layouts.partials.nav-activities', [
                'action' => route('laravel-crm.meetings.filter'),
                'model' => '\VentureDrake\LaravelCrm\Models\Meeting'
            ])
        </div>
        <div class="card-body">
            <h3 class="mb-3"> {{ ucfirst(__('laravel-crm::lang.meetings')) }}</h3>
            @if($meetings && $meetings->count() > 0)
                @foreach($meetings as $meeting)
                    @livewire('meeting',[
                        'meeting' => $meeting
                    ], key($meeting->id))
                @endforeach
            @endif
        </div>
        @if($meetings instanceof \Illuminate\Pagination\LengthAwarePaginator )
            <div class="card-footer">
                {{ $meetings->links() }}
            </div>
        @endif
    </div>

@endsection