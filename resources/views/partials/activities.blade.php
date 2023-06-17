@livewire('notes', [
'model' => $model,
'pinned' => true
 ])
<ul class="nav nav-tabs nav-activities">
    @can('view crm activities')
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" id="tab-activities" href="#tabs-activities">{{ ucfirst(__('laravel-crm::lang.activity')) }}</a>
    </li>
    @endcan
    @can('view crm notes')
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" id="tab-notes" href="#tabs-notes">{{ ucfirst(__('laravel-crm::lang.notes')) }}</a>
    </li>
    @endcan
    @can('view crm tasks')
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" id="tab-tasks" href="#tabs-tasks">{{ ucfirst(__('laravel-crm::lang.tasks')) }}</a>
    </li>
    @endcan
    @can('view crm calls')
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" id="tab-calls" href="#tabs-calls">{{ ucfirst(__('laravel-crm::lang.calls')) }}</a>
    </li>
    @endcan
    @can('view crm meetings')
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" id="tab-meetings" href="#tabs-meetings">{{ ucfirst(__('laravel-crm::lang.meetings')) }}</a>
    </li>
    @endcan
    @can('view crm lunches')
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" id="tab-lunches" href="#tabs-lunches">{{ ucfirst(__('laravel-crm::lang.lunches')) }}</a>
    </li>
    @endcan
    @can('view crm files')
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" id="tab-files" href="#tabs-files">{{ ucfirst(__('laravel-crm::lang.files')) }}</a>
    </li>
    @endcan
</ul>
<div class="tab-content">
    @can('view crm activities')
    <div class="tab-pane fadev show active" id="tabs-activities">
        <div class="card-body pl-0 pr-0">
            @livewire('activities', [
            'model' => $model
            ])
        </div>
    </div>
    @endcan
    @can('view crm notes')
    <div class="tab-pane fade" id="tabs-notes">
        <div class="card-body pl-0 pr-0">
            @livewire('notes', [
            'model' => $model
            ])
        </div>
    </div>
    @endcan
    @can('view crm tasks')
    <div class="tab-pane fade" id="tabs-tasks">
        <div class="card-body pl-0 pr-0">
            @livewire('tasks', [
            'model' => $model
            ])
        </div>
    </div>
    @endcan
    @can('view crm calls')
    <div class="tab-pane fade" id="tabs-calls">
        <div class="card-body pl-0 pr-0">
            @livewire('calls', [
            'model' => $model
            ])
        </div>
    </div>
    @endcan
    @can('view crm meetings')
    <div class="tab-pane fade" id="tabs-meetings">
        <div class="card-body pl-0 pr-0">
            @livewire('meetings', [
            'model' => $model
            ])
        </div>
    </div>
    @endcan
    @can('view crm lunches')
    <div class="tab-pane fade" id="tabs-lunches">
        <div class="card-body pl-0 pr-0">
            @livewire('lunches', [
            'model' => $model
            ])
        </div>
    </div>
    @endcan
    @can('view crm files')
    <div class="tab-pane fade" id="tabs-files">
        <div class="card-body pl-0 pr-0">
            @livewire('files', [
                'model' => $model
            ])
        </div>
    </div>
    @endcan
</div>
