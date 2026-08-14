<x-card-table :pagination="$types">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse($types as $type)
            <tr wire:key="type-{{ $type->id }}">
                <td>{{ $type->id }}</td>
                <td>{{ $type->name }}</td>
                <td class="text-center">
                    <i class="{{ $type->getActiveIconClass() }}"></i>
                </td>
                <td class="text-center">
                    <a href="{{ route('types.show', $type->id) }}" class="d-block text-reset">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
