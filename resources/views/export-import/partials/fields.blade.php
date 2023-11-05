@include('laravel-crm::partials.form.select',[
    'name' => 'model',
    'label' => ucfirst(trans('laravel-crm::lang.model_name')),
    'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\fieldModels(),
    'value' => old('model', $request->model  ?? ''),
    'required' => 'true'
    ])

@include('laravel-crm::partials.form.select',[
    'name' => 'owner',
    'label' => ucfirst(trans('laravel-crm::lang.owner')),
    'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(),
    'value' => old('owner', $request->owner  ?? ''),
    'required' => 'true'
    ])