<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Country;
use App\Models\State;

class LocationDropdowns extends Component
{
    
    public $countries; 
    public $states = []; 
    public $selectedCountry = null; 
    public $selectedState = null; 

    
    public function mount()
    {
        $this->countries = Country::all();
    }

    
    public function updatedSelectedCountry($countryId)
    {
        $this->states = State::where('country_id', $countryId)->get();    
        $this->selectedState = null;
    }

    public function render()
    {
        return view('livewire.location-dropdowns');
    }
}
