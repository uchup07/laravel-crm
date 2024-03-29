<li class="media">
    <div class="card w-100 mb-2">
        <div class="card-body">
            {{--<img src="..." class="mr-3" alt="...">--}}
            <div wire:loading wire:loading.class="d-flex align-items-center">
                <strong>Loading...</strong>
                <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
            </div>
            <div class="media-body">
                @if($note->relatedNote)
                    <h5 class="mt-0 mb-1">{{ $note->relatedNote->created_at->diffForHumans() }}
                        - {{ $note->relatedNote->createdByUser->name }} @if(auth()->user()->id == $note->user_created_id || (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Owner'))) @include('laravel-crm::livewire.components.partials.note.actions', ['note' => $note]) @endif</h5>
                    <p class="pb-0 mb-2">
                        @if($note->relatedNote->noteable instanceof \VentureDrake\LaravelCrm\Models\Person)
                            <span class="fa fa-user mr-1" aria-hidden="true"></span> <a
                                    href="{{ route('laravel-crm.people.show', $note->relatedNote->noteable) }}">{{ $note->relatedNote->noteable->name }}</a>
                        @elseif($note->relatedNote->noteable instanceof \VentureDrake\LaravelCrm\Models\Organisation)
                            <span class="fa fa-building mr-1" aria-hidden="true"></span> <a
                                    href="{{ route('laravel-crm.organisations.show', $note->relatedNote->noteable) }}">{{ $note->relatedNote->noteable->name }}</a>
                        @endif
                    </p>
                    @include('laravel-crm::livewire.components.partials.note.content', ['note' => $note->relatedNote])
                @else
                    <h6 class="mt-0 mb-1">
                        @if($note->noteable)
                            @switch(class_basename($note->noteable->getMorphClass()))
                                @case('Lead')
                                    <a href="{{ route('laravel-crm.leads.show', $note->noteable) }}">{{ $note->noteable->title }}</a>
                                    <span class="badge badge-pill badge-info text-light">{{ ucfirst(__('laravel-crm::lang.lead')) }}</span>
                                    @break
                                @case('Deal')
                                    <a href="{{ route('laravel-crm.deals.show', $note->noteable) }}">{{ $note->noteable->title }}</a>
                                    <span class="badge badge-pill badge-primary text-light">{{ ucfirst(__('laravel-crm::lang.deal')) }}</span>
                                    @break
                                @case('Person')
                                    <a href="{{ route('laravel-crm.people.show', $note->noteable) }}">{{ $note->noteable->name }}</a>
                                    <span class="badge badge-pill badge-primary text-light">{{ ucfirst(__('laravel-crm::lang.person')) }}</span>
                                    @break
                                @case('Organisation')
                                    <a href="{{ route('laravel-crm.organisations.show', $note->noteable) }}">{{ $note->noteable->name }}</a>
                                    <span class="badge badge-pill badge-primary text-light">{{ ucfirst(__('laravel-crm::lang.organization')) }}</span>
                                    @break
                            @endswitch
                        @endif
                        @if(auth()->user()->id == $note->user_created_id || (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Owner')))
                            @include('laravel-crm::livewire.components.partials.note.actions', ['note' => $note])
                        @endif
                    </h6>
                    <h5 class="mt-0 mb-1">
                        {{ $note->created_at->diffForHumans() }} - {{ $note->createdByUser->name }}
                    </h5>
                    @if($showRelated)
                        <p class="pb-0 mb-2">
                        @if($note->noteable instanceof \VentureDrake\LaravelCrm\Models\Person)
                            <span class="fa fa-user-circle" aria-hidden="true"></span> <a
                                    href="{{ route('laravel-crm.people.show', $note->noteable) }}">{{ $note->noteable->name }}</a>
                        @elseif($note->noteable instanceof \VentureDrake\LaravelCrm\Models\Organisation)
                            <span class="fa fa-building" aria-hidden="true"></span> <a
                                    href="{{ route('laravel-crm.organisations.show', $note->noteable) }}">{{ $note->noteable->name }}</a>
                        @endif   
                        </p>
                    @endif
                    @include('laravel-crm::livewire.components.partials.note.content', ['note' => $note])
                    @if(auth()->user()->id != $note->user_created_id)
                        <hr/>
                        <h6><strong>Owner</strong></h6>
                        {{ $note->createdByUser->name }}
                    @endif
                @endif
            </div>
        </div>
    </div>
    @push('livewire-js')
        <script>
            $(document).ready(function () {
                $(document).on("change", "input[name='noted_at']", function () {
                @this.set('noted_at', $(this).val());
                });
            });
        </script>
    @endpush
</li>

