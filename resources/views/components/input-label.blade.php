@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-aat-blue']) }}>
    {{ $value ?? $slot }}
</label>