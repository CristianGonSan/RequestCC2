<x-card-table :pagination="$moneyRequests">
    <x-slot:header>
        <x-livewire.table.search-pane :autofocus="false" />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse($moneyRequests as $request)
            <tr wire:key="request-{{ $request->id }}">
                <td>{{ $request->id }}</td>
                <td>{{ number_format($request->amount, 2) }}</td>
                <td>
                    <span class="badge badge-{{ $request->status->bootstrapColorClass() }}">
                        {{ $request->status->label() }}
                    </span>
                </td>
                <td>{{ $request->shortText('concept') }}</td>
                <td class="text-nowrap">{{ $request->created_at->format('d/m/Y H:i') }}</td>
                <td class="text-center">
                    <a href="{{ route('management.money-requests.show', $request->id) }}" class="d-block text-reset"
                        target="_blank">
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
