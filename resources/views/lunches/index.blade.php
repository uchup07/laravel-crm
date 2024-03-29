@extends('laravel-crm::layouts.app')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            @include('laravel-crm::layouts.partials.nav-activities', [
                'action' => route('laravel-crm.lunches.filter'),
                'model' => '\VentureDrake\LaravelCrm\Models\Lunch'
            ])
        </div>
        <div class="card-body">
            <h3 class="mb-3"> {{ ucfirst(__('laravel-crm::lang.lunches')) }}</h3>
            @if($lunches && $lunches->count() > 0)
                @foreach($lunches as $lunch)
                    @livewire('lunch',[
                        'lunch' => $lunch
                    ], key($lunch->id))
                @endforeach
            @endif
        </div>
        @if($lunches instanceof \Illuminate\Pagination\LengthAwarePaginator )
            <div class="card-footer">
                {{ $lunches->links() }}
            </div>
        @endif
    </div>

@endsection