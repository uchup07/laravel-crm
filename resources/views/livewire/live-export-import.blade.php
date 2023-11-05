<div>
    <div class="card">
        <div class="card-header">
            @include('laravel-crm::layouts.partials.nav-settings')
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="export-import" role="tabpanel">
                    <h3 class="mb-3">{{ ucfirst(__('laravel-crm::lang.export_import')) }} </h3>
                    <p class="border-bottom mb-3 pb-3">Export/import data from Leads, Deals, Customers, Organisations, People, Users, Products.</p>
                    <h4 class="mb-3"> {{ ucfirst(__('laravel-crm::lang.export')) }} </h4>
                    <form wire:submit.prevent="export">
                        @include('laravel-crm::export-import.partials.fields')

                        <hr />
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> {{ ucwords(__('laravel-crm::lang.export')) }}</button>
                            @if($exporting && !$exportFinished)
                                <div class="d-inline" wire:poll="updateExportProgress">Exporting...please wait.</div>
                            @endif

                            @if($exportFinished)
                                Done. Download file <a class="stretched-link" wire:click="downloadExport">here</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>