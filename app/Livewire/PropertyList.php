<?php

namespace App\Livewire;

use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class PropertyList extends Component
{
    use WithPagination;

    public $search = '';
    public $minPrice = null;
    public $maxPrice = null;
    public $successMessage = '';
    public $showSuccessMessage = false;

    protected $listeners = [
        'booking-success' => 'handleBookingSuccess'
    ];

    public function openBooking($propertyId)
    {
        $this->dispatch('open-booking', $propertyId);
    }

    public function handleBookingSuccess($data)
    {
        $this->successMessage = $data['message'] . ' (' . $data['property_name'] . ')';
        $this->showSuccessMessage = true;
    }

    public function hideSuccessMessage()
    {
        $this->showSuccessMessage = false;
        $this->successMessage = '';
    }

    public function applyFilters()
    {
        $this->minPrice = $this->minPrice === '' ? null : $this->minPrice;
        $this->maxPrice = $this->maxPrice === '' ? null : $this->maxPrice;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->resetPage();

        $this->dispatch('filters-reset');
    }

    public function render()
    {
        $query = Property::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('city', 'like', '%' . $this->search . '%');
        }

        if (!is_null($this->minPrice)) {
            $query->where('price_per_night', '>=', $this->minPrice);
        }

        if (!is_null($this->maxPrice)) {
            $query->where('price_per_night', '<=', $this->maxPrice);
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(8);

        return view('livewire.property-list', [
            'properties' => $properties,
        ])->layout('layouts.app');
    }
}
