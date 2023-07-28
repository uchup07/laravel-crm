@extends('laravel-crm::layouts.app')

@section('content')

    <div class="container-content">
        @if(auth()->user()->hasRole(['Manager','Owner']))
        <div class="row">
            @hasleadsenabled
            <div class="col-sm mb-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title float-left m-0">{{ ucfirst(__('laravel-crm::lang.leads')) }}</h4>
                    </div>
                    <div class="card-body">
                        <h2>{{ $totalLeadsCount ?? 0 }}</h2>
                        <small>{{ ucfirst(__('laravel-crm::lang.total_leads')) }}</small>
                    </div>
                </div>
            </div>
            @endhasleadsenabled
            @hasdealsenabled
            <div class="col-sm mb-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title float-left m-0">{{ ucfirst(__('laravel-crm::lang.deals')) }}</h4>
                    </div>
                    <div class="card-body">
                        <h2>{{ $totalDealsCount ?? 0 }}</h2>
                        <small>{{ ucfirst(__('laravel-crm::lang.total_deals')) }}</small>
                    </div>
                </div>
            </div>
            @endhasdealsenabled
            <div class="col-sm mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title float-left m-0">{{ ucfirst(__('laravel-crm::lang.people')) }}</h4>
                    </div>
                    <div class="card-body">
                        <h2>{{ $totalPeopleCount ?? 0 }}</h2>
                        <small>{{ ucfirst(__('laravel-crm::lang.total_people')) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title float-left m-0">{{ ucfirst(__('laravel-crm::lang.organizations')) }}</h4>
                    </div>
                    <div class="card-body">
                        <h2>{{ $totalOrganisationsCount ?? 0 }}</h2>
                        <small>{{ ucfirst(__('laravel-crm::lang.total_organizations')) }}</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row">

            <div class="col-sm-4">
                @hasleadsenabled
                <div class="card border-primary mb-4">
                    <div class="card-header text-white bg-primary">
                        <h4 class="card-title float-left m-0">Your {{ ucfirst(__('laravel-crm::lang.leads')) }}</h4>
                    </div>
                    <div class="card-body">
                        <h2>{{ $myTotalLeadsCount ?? 0 }}</h2>
                        <small>{{ ucfirst(__('laravel-crm::lang.total_leads')) }}</small>
                    </div>
                </div>
                @endhasleadsenabled
                @hasdealsenabled
                <div class="card text-white bg-primary mb-4">
                    <div class="card-header">
                        {{--                        <h4 class="card-title float-left m-0">Your {{ ucfirst(__('laravel-crm::lang.deals')) }}</h4>--}}
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <button class="nav-link text-white active" id="mydeal-tab" data-toggle="tab" data-target="#mydeal" type="button" role="tab" aria-controls="mydeal" aria-selected="true">Your Deals</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link text-success" id="mydealwon-tab" data-toggle="tab" data-target="#mydealwon" type="button" role="tab" aria-controls="mydealwon" aria-selected="true">Won Deals</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link text-danger" id="mydeallost-tab" data-toggle="tab" data-target="#mydeallost" type="button" role="tab" aria-controls="mydeallost" aria-selected="true">Lost Deals</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">

                        <div class="tab-content">
                            <div class="tab-pane active" id="mydeal" role="tabpanel" aria-labelledby="mydeal-tab">
                                <h2>{{ $myTotalDealsCount ?? 0 }}</h2>
                                <small>{{ ucfirst(__('laravel-crm::lang.total_deals')) }}</small>
                            </div>
                            <div class="tab-pane" id="mydealwon" role="tabpanel" aria-labelledby="mydealwon-tab">
                                <h2>{{ $myTotalWonDealsCount ?? 0 }}</h2>
                                <small>{{ ucfirst(__('laravel-crm::lang.total_deals')) }}</small>
                            </div>
                            <div class="tab-pane" id="mydeallost" role="tabpanel" aria-labelledby="mydeallost-tab">
                                <h2>{{ $myTotalLostDealsCount ?? 0 }}</h2>
                                <small>{{ ucfirst(__('laravel-crm::lang.total_deals')) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endhasdealsenabled
            </div>

            <div class="col-sm-4">
                <div class="card border-primary mb-4">
                    <div class="card-header text-white bg-primary">
                        <h4 class="card-title float-left m-0">Your {{ ucfirst(__('laravel-crm::lang.people')) }}</h4>
                    </div>
                    <div class="card-body">
                        <h2>{{ $myTotalPeopleCount ?? 0 }}</h2>
                        <small>{{ ucfirst(__('laravel-crm::lang.total_people')) }}</small>
                    </div>
                </div>
                <div class="card border-primary mb-4">
                    <div class="card-header text-white bg-primary">
                        <h4 class="card-title float-left m-0">Your {{ ucfirst(__('laravel-crm::lang.organizations')) }}</h4>
                    </div>
                    <div class="card-body">
                        <h2>{{ $myTotalOrganisationsCount ?? 0 }}</h2>
                        <small>{{ ucfirst(__('laravel-crm::lang.total_organizations')) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-white bg-success">
                    <div class="card-header">
                        <h4 class="card-title m-0"><i class="fa fa-birthday-cake"></i> {{ ucfirst('People Birthday') }}</h4>
                    </div>
                    <div class="card-body">

                            <div class="row">
                                <div class="col-sm">
                                    @if(count($personBirthday) > 0)
                                        @foreach($personBirthday as $person)
                                        <div class="media {{ (!$loop->last) ? 'mb-3' : null }}">
                                            <span class="fa fa-user fa-2x mr-3 border rounded-circle p-2 align-self-center" aria-hidden="true"></span>
                                            <div class="media-body">
                                                <h4 class="mt-1 mb-0">{{ $person->name }}</h4>
                                                @php
                                                    $organisation = $person->contacts()->where('entityable_type','LIKE','%Organisation%')->first();
                                                    $organisationName = '';

                                                    if($organisation) {
                                                        $organisationName = $organisation->entityable->name;
                                                    }
                                                @endphp
                                                <p class="mb-0">{{  \Carbon\Carbon::parse($person->birthday)->format('d-m-Y') }}.</p>
                                                <p class="mb-0"><span class="badge badge-info text-white">{{ $organisationName }}</span> </p>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        {{ ucfirst('Nobody has a birthday this month') }}
                                    @endif
                                </div>
                                <div class="col-sm">
                                    <div class="m-auto" style="width:100px;height:100px;">
                                        <div class="row h-100 align-items-center justify-content-center bg-info rounded-circle text-center">
                                            <div class="col-12">
                                                <h4>
                                                    {{ \Carbon\Carbon::now()->monthName }}
                                                </h4>
                                                <h4>{{ \Carbon\Carbon::now()->year }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ ucfirst(__('laravel-crm::lang.created_last_14_days')) }}</h4>
                </div>
                <div class="card-body">
                    <canvas id="createdLast14Days" style="height:500px; width:100%" data-chart="{{ $createdLast14Days }}" data-label-leads="{{ ucfirst(__('laravel-crm::lang.leads')) }}" data-label-deals="{{ ucfirst(__('laravel-crm::lang.deals')) }}"></canvas>
                </div>
            </div>
        </div>
        <div class="col-sm mb-4">

            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-title m-0">{{ ucfirst(__('laravel-crm::lang.users_online')) }}</h4>
                </div>
                <div class="card-body">
                    @foreach($usersOnline as $user)
                        <div class="media {{ (!$loop->last) ? 'mb-3' : null }}">
                            <span class="fa fa-user fa-2x mr-3 border rounded-circle p-2" aria-hidden="true"></span>
                            <div class="media-body">
                                <h4 class="mt-1 mb-0">{{ $user->name }}</h4>
                                <p class="mb-0">{{  \Carbon\Carbon::parse($user->last_online_at)->diffForHumans() }}.</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    
@endsection