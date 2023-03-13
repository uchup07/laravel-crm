@component('laravel-crm::components.card')

    @component('laravel-crm::components.card-header')

        @slot('title')
            {{ $client->name }} <small>@include('laravel-crm::partials.labels',[
                            'labels' => $client->labels
                    ])</small>
        @endslot

        @slot('actions')
            <span class="float-right">
                @include('laravel-crm::partials.return-button',[
                    'model' => $client,
                    'route' => 'clients'
                ]) {{--| 
                @can('create crm deals')
                <a href="{{ url(route('laravel-crm.deals.create',['model' => 'client', 'id' => $client->id])) }}" alt="Add deal" class="btn btn-success btn-sm"><span class="fa fa-plus" aria-hidden="true"></span> {{ ucfirst(__('laravel-crm::lang.add_new_deal')) }}</a>
                @endcan--}}
                @include('laravel-crm::partials.navs.activities') | 
                {{--@can('edit crm clients')
                <a href="{{ url(route('laravel-crm.clients.edit', $client)) }}" type="button" class="btn btn-outline-secondary btn-sm"><span class="fa fa-edit" aria-hidden="true"></span></a>
                @endcan--}}
                @can('delete crm clients')
                <form action="{{ route('laravel-crm.clients.destroy',$client) }}" method="POST" class="form-check-inline mr-0 form-delete-button">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button class="btn btn-danger btn-sm" type="submit" data-model="{{ __('laravel-crm::lang.client') }}"><span class="fa fa-trash-o" aria-hidden="true"></span></button>
                </form>
                @endcan    
            </span>
        @endslot

    @endcomponent

    @component('laravel-crm::components.card-body')

        <div class="row">
            <div class="col-sm-6 border-right">
                <h6 class="text-uppercase">{{ ucfirst(__('laravel-crm::lang.details')) }}</h6>
                <hr />
                <dl class="row">
                    <dt class="col-sm-3 text-right">{{ ucfirst(__('laravel-crm::lang.name')) }}</dt>
                    <dd class="col-sm-9">{{ $client->name }}</dd>
                    <dt class="col-sm-3 text-right">{{ ucfirst(__('laravel-crm::lang.type')) }}</dt>
                    <dd class="col-sm-9">{{ class_basename($client->clientable) }}</dd>
                </dl>
                <h6 class="text-uppercase mt-4">{{ ucfirst(__('laravel-crm::lang.owner')) }}</h6>
                <hr />
                <dl class="row">
                    <dt class="col-sm-3 text-right">{{ ucfirst(__('laravel-crm::lang.name')) }}</dt>
                    <dd class="col-sm-9"><a href="{{ route('laravel-crm.users.show', $client->ownerUser) }}">{{ $client->ownerUser->name }}</a></dd>
                </dl>
                @livewire('related-contact-people',[
                    'model' => $client
                ])
               
                @livewire('related-contact-organisations',[
                    'model' => $client
                ])
            </div>
            <div class="col-sm-6">
                @include('laravel-crm::partials.activities', [
                    'model' => $client
                ])
            </div>
        </div>

    @endcomponent

@endcomponent    