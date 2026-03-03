@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-aat-blue focus:ring-aat-blue rounded-md shadow-sm']) !!}>