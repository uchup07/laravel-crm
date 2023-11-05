@extends('laravel-crm::layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        @include('laravel-crm::layouts.partials.nav-settings')
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="export-import" role="tabpanel">
            </div>
        </div>
    </div>
</div>
@endsection