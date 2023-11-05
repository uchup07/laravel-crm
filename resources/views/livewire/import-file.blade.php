<form wire:submit.prevent="import" class="form-inline float-left mr-1" enctype="multipart/form-data">
	@if($download != '')
		<a href="{{ $download }}" class="btn btn-link">Error Data Download</a>
	@endif
	<a class="btn btn-sm btn-dark" data-toggle="modal" data-target="#importModal"><i class="fa fa-upload"></i> {{ ucfirst(__('laravel-crm::lang.upload')) }}</a>
	<div wire:ignore.self  class="modal" id="importModal" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ ucfirst(__('laravel-crm::lang.upload')) }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					@include('laravel-crm::partials.form.file',[
					  'name' => 'file',
					  'label' => ucfirst(__('laravel-crm::lang.add_file')),
					  'attributes' => [
						  'wire:model.defer' => 'file'
					  ]
					])

					<div class="bg-info text-white p-2 mt-5">
						<p>Please Download this template file for using as a upload file.</p>
						<a href="{{ route('laravel-crm.organisations.template') }}" class="btn btn-outline-light btn-sm">Download</a>
					</div>
				</div>
				<div class="modal-footer">
					{{--<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>--}}
					<button type="button" class="btn btn-outline-secondary btn-sm" id="clear-filter">{{ ucfirst(__('laravel-crm::lang.clear')) }}</button>
					<button type="submit" class="btn btn-primary btn-sm">{{ ucfirst(__('laravel-crm::lang.upload')) }}</button>
				</div>
			</div>
		</div>
	</div>
	@push('livewire-js')
		<script>
            $(document).ready(function () {
                window.addEventListener('fileUploaded', event => {
                    bsCustomFileInput.init();
                    $("#importModal").modal('hide');
                    setTimeout(function() {
                        window.location.reload();
					},200);
                });
            });
		</script>
	@endpush
</form>