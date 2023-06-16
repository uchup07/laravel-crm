<span> | 
    @can('create crm notes')
        <a wire:click.prevent="addNote()" href="#note" data-toggle="tooltip" data-placement="bottom" title="{{ ucfirst(__('laravel-crm::lang.add_note')) }}" class="btn btn-outline-secondary btn-sm"><span class="fa fa-sticky-note-o" aria-hidden="true"></span></a>
    @endcan
    @can('create crm tasks')
        <a wire:click.prevent="addTask()" href="#task" data-toggle="tooltip" data-placement="bottom" title="{{ ucfirst(__('laravel-crm::lang.add_task')) }}" class="btn btn-outline-secondary btn-sm"><span class="fa fa-tasks" aria-hidden="true"></span></a>
    @endcan
    @can('create crm calls')
        <a wire:click.prevent="addCall()" href="#call" data-toggle="tooltip" data-placement="bottom" title="{{ ucfirst(__('laravel-crm::lang.schedule_call')) }}" class="btn btn-outline-secondary btn-sm"><span class="fa fa-phone" aria-hidden="true"></span></a>
    @endcan
{{--<a href="#email" data-toggle="tooltip" data-placement="bottom" title="{{ ucfirst(__('laravel-crm::lang.send_email')) }}" class="btn btn-outline-secondary btn-sm"><span class="fa fa-envelope-o" aria-hidden="true"></span></a>--}}
    @can('create crm meetings')
        <a wire:click.prevent="addMeeting()" href="#meeting" data-toggle="tooltip" data-placement="bottom" title="{{ ucfirst(__('laravel-crm::lang.schedule_meeting')) }}" class="btn btn-outline-secondary btn-sm"><span class="fa fa-users" aria-hidden="true"></span></a>
    @endcan
{{--<a href="#deadline" data-toggle="tooltip" data-placement="bottom" title="{{ ucfirst(__('laravel-crm::lang.set_deadline')) }}" class="btn btn-outline-secondary btn-sm"><span class="fa fa-flag" aria-hidden="true"></span></a>--}}
    @can('create crm lunches')    
        <a wire:click.prevent="addLunch()" href="#lunch" data-toggle="tooltip" data-placement="bottom" title="{{ ucfirst(__('laravel-crm::lang.schedule_lunch')) }}" class="btn btn-outline-secondary btn-sm"><span class="fa fa-cutlery" aria-hidden="true"></span></a>
    @endcan
    @can('create crm files')
        <a wire:click.prevent="addFile()" href="#file" data-toggle="tooltip" data-placement="bottom" title="{{ ucfirst(__('laravel-crm::lang.add_file')) }}" class="btn btn-outline-secondary btn-sm"><span class="fa fa-paperclip" aria-hidden="true"></span></a>
    @endcan
</span>
