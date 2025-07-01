<div class="container mx-auto p-6">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nos Propriétés') }}
        </h2>
    </x-slot>
    @if($showSuccessMessage)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md flex items-center justify-between"
            role="alert"
        >
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ $successMessage }}</span>
            </div>
            <button
                wire:click="hideSuccessMessage"
                class="text-green-700 hover:text-green-900 ml-4"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif
    <div class="mb-8 grid grid-cols-1 sm:grid-cols-5 gap-6 items-end">
        <div>
            <label for="search" class="block text-gray-700 font-medium mb-1">Rechercher</label>
            <input type="text" id="search" wire:model.defer="search" placeholder="Nom du bien..."
                   class="border-gray-300 focus:ring-secondary focus:border-secondary rounded-lg p-2 w-full shadow-sm">
        </div>

        <div>
            <label for="minPrice" class="block text-gray-700 font-medium mb-1">Prix minimum (€)</label>
            <input type="number" id="minPrice" wire:model.defer="minPrice" placeholder="0"
                   class="border-gray-300 focus:ring-secondary focus:border-secondary rounded-lg p-2 w-full shadow-sm">
        </div>

        <div>
            <label for="maxPrice" class="block text-gray-700 font-medium mb-1">Prix maximum (€)</label>
            <input type="number" id="maxPrice" wire:model.defer="maxPrice" placeholder="0"
                   class="border-gray-300 focus:ring-secondary focus:border-secondary rounded-lg p-2 w-full shadow-sm">
        </div>

        <div>
            <button wire:click="applyFilters"
                    class="w-full bg-secondary text-white py-2 px-4 rounded-lg shadow hover:drop-shadow-md transition">
                Appliquer les filtres
            </button>
        </div>

        <div>
            <button wire:click="resetFilters"
                    class="w-full bg-third text-gray-700 py-2 px-4 rounded-lg shadow hover:drop-shadow-md transition">
                Réinitialiser
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($properties as $property)
            <div class="border-8 rounded-lg overflow-hidden shadow flex flex-col">
                <img src="{{ asset('storage/' . $property->image) }}" class="w-full h-72 object-cover drop-shadow rounded-b-3xl">
                <div class="p-4 flex flex-col flex-1">
                    <h2 class="text-xl font-bold mb-2">{{ $property->name }}</h2>
                    <p class="text-gray-600">{{ $property->description }}</p>
                    <p class="mt-2 font-semibold">Prix : {{ $property->price_per_night }} €/nuit</p>

                    <div class="mt-auto pt-4">
                        @auth
                            <button wire:click="openBooking({{ $property->id }})"
                                    class="w-full bg-fourth text-white py-1 px-3 rounded">
                                Réserver
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full inline-block bg-gray-500 text-white py-1 px-3 rounded text-center">
                                Se connecter pour réserver
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <div class="mt-8">
        {{ $properties->links() }}
    </div>
    @livewire('booking-manager')
</div>

<script>
    window.addEventListener('filters-reset', () => {
        document.getElementById('search').value = '';
        document.getElementById('minPrice').value = '';
        document.getElementById('maxPrice').value = '';
    });
</script>
