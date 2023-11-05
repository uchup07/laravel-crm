@extends('laravel-crm::layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        @include('laravel-crm::layouts.partials.nav-settings')
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="export-import" role="tabpanel">
                <h3 class="mb-3"> {{ ucfirst(__('laravel-crm::lang.export_import')) }}  @can('export crm export-import')<span class="float-right"><a type="button" class="btn btn-primary btn-sm" href="{{ url(route('laravel-crm.export-import.export')) }}"><span class="fa fa-download"></span>  {{ ucfirst(__('laravel-crm::lang.export')) }}</a></span>@endcan @can('import crm export-import')<span class="float-right"><a type="button" class="btn btn-primary btn-sm" href="{{ url(route('laravel-crm.export-import.import')) }}"><span class="fa fa-upload"></span>  {{ ucfirst(__('laravel-crm::lang.import')) }}</a></span>@endcan</h3>
                <div class="table-responsive">
                    <table class="table mb-0 card-table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{{ ucfirst(__('laravel-crm::lang.queue')) }}</th>
                                <th scope="col">{{ ucfirst(__('laravel-crm::lang.payload')) }}</th>
                                <th scope="col">{{ ucfirst(__('laravel-crm::lang.attempts')) }}</th>
                                <th scope="col">{{ ucfirst(__('laravel-crm::lang.reserved_at')) }}</th>
                                <th scope="col">{{ ucfirst(__('laravel-crm::lang.available_at')) }}</th>
                                <th scope="col">{{ ucfirst(__('laravel-crm::lang.created_at')) }}</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobs as $job)
                                <tr>
                                    <td>{{ $job->queue }}</td>
                                    <td>{{ ($job->payload }}</td>
                                    <td>{{ ($job->attempts }}</td>
                                    <td>{{ $job->reserved_at->format($dateFormat) }}</td>
                                    <td>{{ $job->available_at->format($dateFormat) }}</td>
                                    <td>{{ $job->created_at->format($dateFormat) }}</td>
                                    <td class="disable-link text-right">
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection