<x-card-table :pagination="$customers">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse($customers as $customer)
            <tr wire:key="customer-{{ $customer->id }}">
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td class="text-center">
                    <i class="{{ $customer->getActiveIconClass() }}"></i>
                </td>
                <td class="text-center">
                    <a href="{{ route('customers.show', $customer->id) }}" class="d-block text-reset">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
