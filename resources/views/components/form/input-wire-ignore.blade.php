@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{--
        Copia de x-adminlte-input.
        Se añade un wrapper con wire:ignore para evitar que Livewire modifique el input.

        wire:loading.attr='readonly' + wire:target='save' por defecto:
        evita que el propio wire:model del input dispare su propio estado
        de loading al escribir. Se puede sobreescribir pasando wire:target
        explícito al consumir el componente (ej. wire:target="save,delete").
    --}}

    <div class="w-100" wire:ignore>
        {{-- Input --}}
        <input id="{{ $id }}" name="{{ $name }}"
            value="{{ $getOldValue($errorKey, $attributes->get('value')) }}"
            {{ $attributes->merge([
                'class' => $makeItemClass(),
                'wire:loading.attr' => 'readonly',
                'wire:target' => 'save',
            ]) }}>
    </div>

@overwrite
