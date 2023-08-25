<form method="post" action="{{ $action }}" class="form-inline float-left mr-1">
    @if(request()->session()->has(class_basename(request()->route()->getController()).'.params'))
        @if(!request()->session()->has(class_basename(request()->route()->getController()).'.params.select-all-label'))
            <span class="mr-1">Label: </span>
            @if(request()->session()->has(class_basename(request()->route()->getController()).'.params.label_id'))
                @php
                    $FlabelIds = request()->session()->get(class_basename(request()->route()->getController()).'.params.label_id');
                    $FilterLabels = \VentureDrake\LaravelCrm\Models\Label::whereIn('id',$FlabelIds)->get();
                @endphp
                @if(isset($FilterLabels))
                    @foreach($FilterLabels as $flabel)
                        <span class="badge badge-primary mr-1" style="background-color: #{{ $flabel->hex }}">{{ $flabel->name }}</span>
                    @endforeach
                @endif
            @endif
        @endif
        @if(!request()->session()->has(class_basename(request()->route()->getController()).'.params.select-all-owner'))
            <span class="mr-1">Owner:</span>
            @if(request()->session()->has(class_basename(request()->route()->getController()).'.params.user_owner_id'))
                @php
                    $FOwnerIds = request()->session()->get(class_basename(request()->route()->getController()).'.params.user_owner_id');
                    $FilterOwners = \App\Models\User::whereIn('id',$FOwnerIds)->get();
                @endphp
                @if(isset($FilterOwners))
                    @foreach($FilterOwners as $fowner)
                        <span class="mr-1">{{ ((!$loop->last) ? $fowner->name . ','  : $fowner->name) }}</span>
                    @endforeach
                @endif
            @endif
        @endif
    @endif
    @csrf
    <a class="btn btn-sm {{ ($model::anyFilterActive([
    'user_owner_id' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(false) + [0 => '(Blank)'],
    'label_id' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\optionsFromModel(\VentureDrake\LaravelCrm\Models\Label::all(), false) + [0 => '(Blank)']
    ])) ? 'btn-outline-success' : 'btn-outline-secondary' }}" data-toggle="modal" data-target="#searchFilterModal">{{ ucfirst(__('laravel-crm::lang.filter')) }}</a>
    <div class="modal" id="searchFilterModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ ucfirst(__('laravel-crm::lang.filters')) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-filter-group mb-2 {{ ($model::filterActive('user_owner_id', \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(false) + [0 => '(Blank)'])) ? 'filter-active' : null }}">
                        @include('laravel-crm::partials.filter',[
                            'filter' => $owners ?? null,
                            'name' => 'user_owner_id',
                            'label' => 'owner',
                            'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(false) + [0 => '(Blank)'],
                            'value' => request()->input('user_owner_id') ?? $model::filterValue('user_owner_id') ?? array_keys(\VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(false) + [0 => '(Blank)'])
                        ])
                    </div>
                    <div class="modal-filter-group {{ ($model::filterActive('label_id', \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\optionsFromModel(\VentureDrake\LaravelCrm\Models\Label::all(), false) + [0 => '(Blank)'])) ? 'filter-active' : null }}">
                        @include('laravel-crm::partials.filter',[
                            'filter' => $labels ?? null,
                            'name' => 'label_id',
                            'label' => 'label',
                            'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\optionsFromModel(\VentureDrake\LaravelCrm\Models\Label::all(), false) + [0 => '(Blank)'],
                            'value' => request()->input('label_id') ?? $model::filterValue('label_id')  ?? array_keys(\VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\optionsFromModel(\VentureDrake\LaravelCrm\Models\Label::all(), false) + [0 => '(Blank)'])
                        ])
                        @include('laravel-crm::partials.form.hidden',[
                            'name' => 'created_from',
                            'value' => request()->input('created_from') ?? $model::filterValue('created_from') ?? null,
                       ])
   
                       @include('laravel-crm::partials.form.hidden',[
                            'name' => 'created_to',
                            'value' => request()->input('created_to') ?? $model::filterValue('created_to') ?? null,
                       ])
                    </div>      
                </div>
                <div class="modal-footer">
                    {{--<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>--}}
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-filter">{{ ucfirst(__('laravel-crm::lang.clear')) }}</button>
                    <button type="submit" class="btn btn-primary btn-sm">{{ ucfirst(__('laravel-crm::lang.filter')) }}</button>
                </div>
            </div>
        </div>
    </div>
    
</form>