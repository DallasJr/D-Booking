<?php

namespace App\Livewire;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Property;

class BookingManager extends Component
{
    public ?Property $property = null;
    public $startDate;
    public $endDate;

    public $note;

    public $unavailableDates = [];

    public $nights = 0;
    public $totalPrice = 0;

    protected $listeners = ['open-booking' => 'openBooking'];

    public function openBooking($propertyId)
    {
        $this->property = Property::findOrFail($propertyId);
        $bookings = $this->property->bookings()->where('end_date', '>=', Carbon::today())->get();

        $dates = [];
        foreach ($bookings as $booking) {
            $period = Carbon::parse($booking->start_date)->daysUntil(Carbon::parse($booking->end_date));
            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        $this->unavailableDates = $dates;

        $this->reset(['startDate', 'endDate', 'nights', 'totalPrice']);
    }

    public function getFormattedBookingPeriods()
    {
        $bookings = $this->property->bookings()
            ->where('end_date', '>=', Carbon::today())
            ->orderBy('start_date')
            ->get();

        $periods = [];
        foreach ($bookings as $booking) {
            $startDate = Carbon::parse($booking->start_date);
            $endDate = Carbon::parse($booking->end_date);

            $periods[] = [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y'),
                'nights' => $startDate->diffInDays($endDate)
            ];
        }

        return $periods;
    }

    public function updatedStartDate($value) {
        if ($this->checkStartDate($value)) {
            $this->resetValidation('errors');
        }
        $this->calculatePrice();
    }

    public function updatedEndDate($value) {
        if ($this->checkEndDate($value)) {
            $this->resetValidation('errors');
        }
        $this->calculatePrice();
    }

    private function checkStartDate($value) : bool {
        if ($this->endDate) {
            if ($this->endDate <= $value) {
                $this->addError('errors', 'La date de début doit être avant la date de fin.');
                $this->startDate = null;
                $this->dispatch('start-reset');
                return false;
            }
        }
        if (Carbon::parse($value) < Carbon::today()) {
            $this->addError('errors', "La date de début doit être au moins aujourd'hui ou les jours suivants.");
            $this->startDate = null;
            $this->dispatch('start-reset');
            return false;
        }
        return true;
    }

    private function checkEndDate($value) : bool {
        if ($this->startDate) {
            if ($this->startDate >= $value) {
                $this->addError('errors', 'La date de fin doit être après la date de début.');
                $this->endDate = null;
                $this->dispatch('end-reset');
                return false;
            }
        }
        if (Carbon::parse($value) <= Carbon::today()) {
            $this->addError('errors', "La date de fin doit être au moins demain ou les jours suivants.");
            $this->endDate = null;
            $this->dispatch('end-reset');
            return false;
        }
        return true;
    }

    private function calculatePrice()
    {
        if ($this->startDate && $this->endDate) {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);
            $this->nights = $start->diffInDays($end);
            $this->totalPrice = $this->property ? $this->property->price_per_night * $this->nights : 0;
        } else {
            $this->nights = 0;
            $this->totalPrice = 0;
        }
    }

    public function book()
    {
        if ($this->startDate && $this->endDate) {
            if (!$this->checkStartDate($this->startDate)) {
                return;
            }
            if (!$this->checkEndDate($this->endDate)) {
                return;
            }
            $this->calculatePrice();
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);

            if (Booking::isOverlapping($this->property->id, $start, $end)) {
                Log::debug("Certaines dates sélectionnées ne sont pas disponibles.");
                $this->addError('errors', "Certaines dates sélectionnées ne sont pas disponibles.");
                return;
            }

            try {
                $booking = Booking::create([
                    'property_id' => $this->property->id,
                    'user_id' => auth()->id(),
                    'start_date' => $start->format('Y-m-d'),
                    'end_date' => $end->format('Y-m-d'),
                    'total_price' => $this->totalPrice,
                    'note' => $this->note,
                    'nights' => $this->nights,
                    'status' => 'pending',
                ]);
                Log::info("Réservation créée avec succès", [
                    'booking_id' => $booking->id,
                    'property_id' => $this->property->id,
                    'user_id' => auth()->id(),
                    'dates' => $start->format('Y-m-d') . ' - ' . $end->format('Y-m-d'),
                    'note' => $this->note
                ]);
                $this->dispatch('booking-success', [
                    'message' => 'Réservation effectuée avec succès !',
                    'property_name' => $this->property->name,
                    'booking_id' => $booking->id
                ]);
            } catch (\Exception $e) {
                Log::error("Erreur lors de la création de la réservation", [
                    'error' => $e->getMessage(),
                    'property_id' => $this->property->id,
                    'user_id' => auth()->id()
                ]);
                $this->addError('errors', 'Une erreur est survenue lors de la réservation. Veuillez réessayer.');
                return;
            }
        }

        $this->reset(['property', 'startDate', 'endDate', 'nights', 'totalPrice']);
    }

    public function closeBooking()
    {
        $this->reset(['property', 'startDate', 'endDate', 'nights', 'totalPrice']);
    }

    public function render()
    {
        return view('livewire.booking-manager');
    }
}
