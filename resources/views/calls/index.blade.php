@extends('laravel-crm::layouts.app')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            @include('laravel-crm::layouts.partials.nav-activities', [
                'action' => route('laravel-crm.calls.filter'),
                'model' => '\VentureDrake\LaravelCrm\Models\Call'
            ])
        </div>
        <div class="card-body">
            <h3 class="mb-3"> {{ ucfirst(__('laravel-crm::lang.calls')) }}</h3>
            @if($calls && $calls->count() > 0)
                @foreach($calls as $call)
                    @livewire('call',[
                        'call' => $call
                    ], key($call->id))
                @endforeach
            @endif
        </div>
        @if($calls instanceof \Illuminate\Pagination\LengthAwarePaginator )
            <div class="card-footer">
                {{ $calls->links() }}
            </div>
        @endif
    </div>

@endsection