<div class="border rounded-lg overflow-hidden shadow-lg">
    <img src="{{ asset('storage/' . $property->image) }}" alt="{{ $property->name }}" class="w-full h-48 object-cover">

    <div class="p-4">
        <h2 class="text-xl font-bold text-primary">{{ $property->name }}</h2>
        <p class="text-gray-600">{{ $property->city }}</p>

        <p class="mt-2 text-gray-700">{{ Str::limit($property->description, 100) }}</p>

        <p class="mt-2 text-primary font-semibold">
            {{ number_format($property->price_per_night, 2) }} € / nuit
        </p>

        <div class="mt-4 flex justify-end">
            <x-button wire:click="$emit('bookProperty', {{ $property->id }})">
                Réserver
            </x-button>
        </div>
    </div>
</div>
