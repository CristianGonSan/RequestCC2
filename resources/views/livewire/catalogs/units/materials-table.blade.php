<x-card-table :pagination="$materials">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse($materials as $material)
            <tr wire:key="material-{{ $material->id }}">
                <td>{{ $material->id }}</td>
                <td>{{ $material->shortText('name') }}</td>
                <td>{{ $material->code ?? '-' }}</td>
                <td class="text-center">
                    @if ($material->is_external)
                        <i class="fas fa-globe text-info" title="Externo"></i>
                    @else
                        <i class="fas fa-house-chimney text-secondary" title="Interno"></i>
                    @endif
                </td>
                <td class="text-center">
                    <i class="{{ $material->getActiveIconClass() }}"></i>
                </td>
                <td class="text-center">
                    <a href="{{ route('materials.show', $material->id) }}" class="d-block text-reset"
                        target="_blank">
                        <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
