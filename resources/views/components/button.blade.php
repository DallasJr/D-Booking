<button {{ $attributes->merge(['class' => 'px-4 py-2 bg-primary text-white rounded hover:bg-blue-800 transition']) }}>
    {{ $slot }}
</button>
