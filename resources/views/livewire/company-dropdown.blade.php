<div class="form-group row">
    <x-label for="company" :value="__('custom.services.company')" />
    <div class="col-md-9">
        <select class="form-control required" wire:model="company_id" id='company' name="company">
            <option value="0">Select Company</option>
            @forelse($companies as $company)
                <option value="{{ $company->id }}">{{ $company->c_name }}</option>
            @empty
                <option>No Company Found</option>
            @endforelse
        </select>
        <span class="small float-right block"><a href="{{ route('companies.create') }}"
                target="_blank">+
                Add new
                company</a></span>
        <x-error-message :value="__('company_id')" />
    </div>
</div>
