@extends('laravel-crm::layouts.app')

@section('content')
<form method="POST" action="{{ url(route('laravel-crm.export-import.import-execute')) }}" enctype="multipart/form-data">
    @csrf
<div class="card">
    <div class="card-header">
        @include('laravel-crm::layouts.partials.nav-settings')
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="export-import" role="tabpanel">
                <h3 class="mb-3"> {{ ucfirst(__('laravel-crm::lang.import')) }}</h3>
                @include('laravel-crm::export-import.partials.fields')
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">{{ ucwords(trans('laravel-crm::lang.import')) }}</button>
    </div>
</div>
</form>
@endsection