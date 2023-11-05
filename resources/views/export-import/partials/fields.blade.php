@include('laravel-crm::partials.form.select',[
    'name' => 'model',
    'label' => ucfirst(trans('laravel-crm::lang.model_name')),
    'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\fieldModels(),
    'attributes' => [
		   'wire:model' => 'model',
	  ]
    ])

@include('laravel-crm::partials.form.select',[
    'name' => 'owner',
    'label' => ucfirst(trans('laravel-crm::lang.owner')),
    'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(),
    'attributes' => [
		   'wire:model' => 'owner',
	  ]
    ])