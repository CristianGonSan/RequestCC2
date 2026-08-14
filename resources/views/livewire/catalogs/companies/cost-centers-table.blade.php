<x-card-table :pagination="$costCenters">
    <x-slot:header>
        <x-livewire.table.search-pane :autofocus="false" />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse($costCenters as $costCenter)
            <tr wire:key="cost-center-{{ $costCenter->id }}">
                <td>{{ $costCenter->id }}</td>
                <td>{{ $costCenter->name }}</td>
                <td>{{ $costCenter->mediumText('description') }}</td>
                <td class="text-center">
                    <i class="{{ $costCenter->getActiveIconClass() }}"></i>
                </td>
                <td class="text-center">
                    <a href="{{ route('cost-centers.show', $costCenter->id) }}" class="d-block text-reset" target="_blank">
                        <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
