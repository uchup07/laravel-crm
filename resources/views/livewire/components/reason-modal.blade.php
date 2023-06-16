<div wire:ignore.self class="modal" id="reasonModal" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<form wire:submit.prevent="store" id="reasonForm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ ucfirst(__('laravel-crm::lang.reason')) }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
					<div class="modal-body">
						@include('laravel-crm::livewire.partials.dealreason-form-fields')
					</div>
					<div class="modal-footer">
						{{--<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>--}}
						<button type="button" class="btn btn-outline-secondary btn-sm" id="clear-filter">{{ ucfirst(__('laravel-crm::lang.clear')) }}</button>
						<button type="submit" class="btn btn-primary btn-sm">Submit</button>
					</div>
			</div>

		</form>
	</div>
</div>
