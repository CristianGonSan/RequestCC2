<x-card-table :pagination="$companies">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @php
            $canView = can('manage_companies');
        @endphp
        @forelse($companies as $company)
            <tr wire:key="company-{{ $company->id }}">
                <td>{{ $company->id }}</td>
                <td>{{ $company->name }}</td>
                <td class="text-center">
                    <i class="{{ $company->getActiveIconClass() }}"></i>
                </td>
                <td class="text-center">
                    @if ($canView)
                        <a href="{{ route('companies.show', $company->id) }}" class="d-block text-reset">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    @else
                        <i class="fas fa-fw fa-lock text-muted"></i>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
