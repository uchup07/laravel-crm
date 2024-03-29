@extends('laravel-crm::layouts.app')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            @include('laravel-crm::layouts.partials.nav-activities', [
                'action' => route('laravel-crm.activities.filter'),
                'model' => '\VentureDrake\LaravelCrm\Models\Activity'
            ])
        </div>
        <div class="card-body">
            @if($activities && $activities->count() > 0)
                @foreach($activities as $activity)
                    @include('laravel-crm::activities.partials.activity', $activity)
                @endforeach
            @endif
        </div>
        @if($activities instanceof \Illuminate\Pagination\LengthAwarePaginator )
            <div class="card-footer">
                {{ $activities->links() }}
            </div>
        @endif
    </div>

@endsection