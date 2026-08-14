@extends('adminlte::components.form.input-group-component')

{{-- Set errors bag internallly --}}

@php($setErrorsBag($errors ?? null))

{{-- Set input group item section --}}

@section('input_group_item')

    {{--
        Copia de x-adminlte-select.
        Se añade un wrapper con wire:ignore para evitar que Livewire modifique el select.

        wire:loading.attr='readonly' + wire:target='save' por defecto:
        evita que el propio wire:model del select dispare su propio estado
        de loading al cambiar. Se puede sobreescribir pasando wire:target
        explícito al consumir el componente (ej. wire:target="save,delete").
    --}}

    <div class="w-100" wire:ignore>
        {{-- Select --}}
        <select id="{{ $id }}" name="{{ $name }}"
            {{ $attributes->merge([
                'class' => $makeItemClass(),
                'wire:loading.attr' => 'readonly',
                'wire:target' => 'save',
            ]) }}>
            {{ $slot }}
        </select>
    </div>

@overwrite


{{-- Support to auto select the old submitted values --}}

@if ($errors->any() && $enableOldSupport)
    @push('js')
        <script>
            $(() => {

                let oldOptions = @json(collect($getOldValue($errorKey)));

                $('#{{ $id }} option').each(function() {
                    let value = $(this).val() || $(this).text();
                    $(this).prop('selected', oldOptions.includes(value));
                });
            });
        </script>
    @endpush
@endif
