<div>
    @if ($property)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click.self="closeBooking">
            <div class="bg-white rounded-lg p-6 w-[640px] max-h-[90vh] overflow-y-auto">
                <img src="{{ asset('storage/' . $property->image) }}" alt="{{ $property->name }}"
                     class="w-full h-auto object-contain mb-4 rounded">

                <h2 class="text-2xl font-bold mb-2">{{ $property->name }}</h2>

                <p class="text-gray-700 mb-2">{{ $property->description }}</p>

                <p class="text-gray-600 mb-4">
                    📍 {{ $property->city }}<br>
                    💶 <span class="font-semibold">{{ $property->price_per_night }} € / nuit</span>
                </p>

                @if(count($this->getFormattedBookingPeriods()) > 0)
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <h3 class="font-semibold text-red-800 mb-2">📅 Dates déjà réservées :</h3>
                        <div class="space-y-1">
                            @foreach($this->getFormattedBookingPeriods() as $period)
                                <div class="text-sm text-red-700">
                                    • Du {{ $period['start'] }} au {{ $period['end'] }}
                                    <span class="text-gray-600">({{ $period['nights'] }} {{ $period['nights'] > 1 ? 'nuits' : 'nuit' }})</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex gap-2">
                    <div class="w-1/2">
                        <label for="startDate" class="block mb-1 font-medium">Début</label>
                        <input
                            type="date"
                            wire:model.live="startDate"
                            id="startDate"
                            class="border p-2 rounded w-full"
                            min="{{ now()->toDateString() }}"
                        >
                    </div>

                    <div class="w-1/2">
                        <label for="endDate" class="block mb-1 font-medium">Fin</label>
                        <input
                            type="date"
                            wire:model.live="endDate"
                            id="endDate"
                            class="border p-2 rounded w-full"
                            min="{{ $startDate ?? now()->toDateString() }}"
                        >
                    </div>
                </div>

                @error('errors') <div class="mt-2"><span class="text-red-600 text-sm">{{ $message }}</span></div> @enderror

                <p class="my-4">
                    <strong>Nombre de nuits :</strong> {{ $nights }} <br>
                    <strong>Total :</strong> {{ $totalPrice }} €
                </p>

                <div class="mb-4">
                    <label for="note" class="block mb-1 font-medium">Note</label>
                    <textarea
                        id="note"
                        wire:model.live="note"
                        rows="3"
                        class="border p-2 rounded w-full"
                        placeholder="Moyens de contacts et/ou demande particulière…"
                    ></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button wire:click="closeBooking"
                            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Annuler</button>
                    <button
                        wire:click="book"
                        @if(!$startDate || !$endDate || !$note) disabled @endif
                        class="px-4 py-2 rounded transition-colors duration-200
                               @if($startDate && $endDate && $note)
                                   bg-fourth text-white hover:bg-fourth/80 cursor-pointer
                               @else
                                   bg-gray-400 text-gray-600 cursor-not-allowed
                               @endif"
                    >
                        Réserver
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    window.addEventListener('start-reset', () => {
        document.getElementById('startDate').value = '';
    });
    window.addEventListener('end-reset', () => {
        document.getElementById('endDate').value = '';
    });
</script>
