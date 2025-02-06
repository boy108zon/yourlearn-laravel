<div>
   
    <select wire:model="selectedCountry" class="form-control">
        <option value="">Select Country</option>
        @foreach($countries as $country)
            <option value="{{ $country->id }}">{{ $country->name }}</option>
        @endforeach
    </select>

   
    <select wire:model="selectedState" class="form-control" {{ !$selectedCountry ? 'disabled' : '' }}>
        <option value="">Select State</option>
        @forelse($states as $state)
            <option value="{{ $state->id }}">{{ $state->name }}</option>
        @empty
            <option value="">No states available</option>
        @endforelse
    </select>
</div>
