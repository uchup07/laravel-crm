<li class="media">
    <div class="card w-100 mb-2">
        <div class="card-body">
            {{--<img src="..." class="mr-3" alt="...">--}}
            <div wire:loading wire:loading.class="d-flex align-items-center">
                <strong>Loading...</strong>
                <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
            </div>
            <div class="media-body">
                <h6 class="mt-0 mb-1">
                    @if($task->taskable)
                        @switch(class_basename($task->taskable->getMorphClass()))
                            @case('Lead')
                                <a href="{{ route('laravel-crm.leads.show', $task->taskable) }}">{{ $task->taskable->title }}</a>
                                <span class="badge badge-pill badge-info text-light">{{ ucfirst(__('laravel-crm::lang.lead')) }}</span>
                                @break
                            @case('Deal')
                                <a href="{{ route('laravel-crm.deals.show', $task->taskable) }}">{{ $task->taskable->title }}</a>
                                <span class="badge badge-pill badge-primary text-light">{{ ucfirst(__('laravel-crm::lang.deal')) }}</span>
                                @break
                            @case('Person')
                                <a href="{{ route('laravel-crm.people.show', $task->taskable) }}">{{ $task->taskable->name }}</a>
                                <span class="badge badge-pill badge-primary text-light">{{ ucfirst(__('laravel-crm::lang.person')) }}</span>
                                @break
                            @case('Organisation')
                                <a href="{{ route('laravel-crm.organisations.show', $task->taskable) }}">{{ $task->taskable->name }}</a>
                                <span class="badge badge-pill badge-primary text-light">{{ ucfirst(__('laravel-crm::lang.organization')) }}</span>
                                @break
                        @endswitch
                    @endif
                    @if(auth()->user()->id == $task->user_owner_id || (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Owner')))
                        @include('laravel-crm::livewire.components.partials.task.actions', ['task' => $task])
                    @endif
                </h6>
                <h5 class="mt-0 mb-1">{{ $task->name }}</h5>
                @if($showRelated)
                    <p class="pb-0 mb-2">
                        @if($task->taskable instanceof \VentureDrake\LaravelCrm\Models\Person)
                            <span class="fa fa-user-circle" aria-hidden="true"></span> <a
                                    href="{{ route('laravel-crm.people.show', $task->taskable) }}">{{ $task->taskable->name }}</a>
                        @elseif($task->taskable instanceof \VentureDrake\LaravelCrm\Models\Organisation)
                            <span class="fa fa-building" aria-hidden="true"></span> <a
                                    href="{{ route('laravel-crm.organisations.show', $task->taskable) }}">{{ $task->taskable->name }}</a>
                        @endif
                    </p>
                @endif
                @include('laravel-crm::livewire.components.partials.task.content', ['task' => $task])
            </div>
        </div>
    </div>
    @push('livewire-js')
        <script>
            $(document).ready(function () {
                $(document).on("change", "input[name='due_at']", function () {
                    @this.set('due_at', $(this).val());
                });
            });
        </script>
    @endpush
</li>

