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
                    <hr/>
                    <h4 class="mb-3"> {{ ucfirst(__('laravel-crm::lang.import')) }} </h4>
                    <form wire:submit.prevent="import">
                        @include('laravel-crm::export-import.partials.fields')
                        @include('laravel-crm::partials.form.file',[
                              'name' => 'file',
                              'label' => ucfirst(__('laravel-crm::lang.add_file')),
                              'attributes' => [
                                  'wire:model.defer' => 'file'
                              ]
                            ])
                        <hr />
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> {{ ucwords(__('laravel-crm::lang.import')) }}</button>
                            @if($importing && !$importFinished)
                                <div class="d-inline" wire:poll="updateExportProgress">Importing...please wait.</div>
                            @endif

                            @if($importFinished)
                                Import Finished. Click <a class="stretched-link" wire:click="viewImport">here</a> to view it.
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div>