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
                    @if($meeting->meetingable)
                        @switch(class_basename($meeting->meetingable->getMorphClass()))
                            @case('Lead')
                                <a href="{{ route('laravel-crm.leads.show', $meeting->meetingable) }}">{{ $meeting->meetingable->title }}</a>
                                <span class="badge badge-pill badge-info text-light">{{ ucfirst(__('laravel-crm::lang.lead')) }}</span>
                                @break
                            @case('Deal')
                                <a href="{{ route('laravel-crm.deals.show', $meeting->meetingable) }}">{{ $meeting->meetingable->title }}</a>
                                <span class="badge badge-pill badge-primary text-light">{{ ucfirst(__('laravel-crm::lang.deal')) }}</span>
                                @break
                            @case('Person')
                                <a href="{{ route('laravel-crm.people.show', $meeting->meetingable) }}">{{ $meeting->meetingable->name }}</a>
                                <span class="badge badge-pill badge-primary text-light">{{ ucfirst(__('laravel-crm::lang.person')) }}</span>
                                @break
                            @case('Organisation')
                                <a href="{{ route('laravel-crm.organisations.show', $meeting->meetingable) }}">{{ $meeting->meetingable->name }}</a>
                                <span class="badge badge-pill badge-primary text-light">{{ ucfirst(__('laravel-crm::lang.organization')) }}</span>
                                @break
                        @endswitch
                    @endif
                    @if(auth()->user()->id == $meeting->user_owner_id || (auth()->user()->hasRole(['Admin','Owner','Manager'])))
                        @include('laravel-crm::livewire.components.partials.meeting.actions', ['meeting' => $meeting])
                    @endif
                </h6>
                <h5 class="mt-0 mb-1">{{ $meeting->name }}</h5>
                @if($showRelated)
                    <p class="pb-0 mb-2">
                        @if($meeting->meetingable instanceof \VentureDrake\LaravelCrm\Models\Person)
                            <span class="fa fa-user-circle" aria-hidden="true"></span> <a
                                    href="{{ route('laravel-crm.people.show', $meeting->meetingable) }}">{{ $meeting->meetingable->name }}</a>
                        @elseif($meeting->meetingable instanceof \VentureDrake\LaravelCrm\Models\Organisation)
                            <span class="fa fa-building" aria-hidden="true"></span> <a
                                    href="{{ route('laravel-crm.organisations.show', $meeting->meetingable) }}">{{ $meeting->meetingable->name }}</a>
                        @endif
                    </p>
                @endif
                @include('laravel-crm::livewire.components.partials.meeting.content', ['meeting' => $meeting, 'people' => $people])
            </div>
        </div>
    </div>
    @push('livewire-js')
        <script>
            $(document).ready(function () {
                $(document).on("change", ".meetings input[name='start_at']", function () {
                    @this.set('start_at', $(this).val());
                });

                $(document).on("change", ".meetings input[name='finish_at']", function () {
                    @this.set('finish_at', $(this).val());
                });

                $(document).on("change", '.meetings select[name="guests[]"]', function (e) {
                    var data = $('.meetings select[name="guests[]"]').select2("val");
                    @this.set('guests', data);
                });
            });
        </script>
    @endpush
</li>

