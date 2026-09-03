@use(App\Enums\Requests\MoneyRequestStatus)

<div>
    <form wire:submit="save">
        <div class="card">
            <div class="card-body">
                <div class="mb-4">
                    <x-form.select-wire-ignore id="createRequestSelect" label="Al crearse una solicitud, notificar a:"
                        name="createRequest" multiple>
                        @foreach ($createRequest as $email)
                            <option value="{{ $email }}" selected>{{ $email }}</option>
                        @endforeach
                    </x-form.select-wire-ignore>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label fw-bold">Cuando una solicitud cambia de estado:</label>
                    @foreach (MoneyRequestStatus::cases() as $status)
                        <div class="mb-3">
                            <x-form.select-wire-ignore id="statusChangeSelect_{{ $status->value }}"
                                label="{{ $status->label() }}" label-class="text-{{ $status->bootstrapColorClass() }}"
                                name="statusChange.{{ $status->value }}" multiple>
                                @foreach ($statusChange[$status->value] ?? [] as $email)
                                    <option value="{{ $email }}" selected>{{ $email }}</option>
                                @endforeach
                            </x-form.select-wire-ignore>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mb-3">
            <x-livewire.loading-button type="submit" wire:target="save" label="Guardar" />
        </div>
    </form>
</div>

@push('js')
    <script>
        document.addEventListener("livewire:initialized", () => {
            const $wire = Livewire.first();
            const select2Builder = new LivewireSelect2Builder($wire);

            select2Builder.appendConfig({
                tags: true,
                tokenSeparators: [',', ' '],
                placeholder: 'correo@ejemplo.com',
                minimumInputLength: 0,
                createTag: (params) => {
                    const term = (params.term || '').trim();
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (!emailPattern.test(term)) {
                        return null;
                    }

                    return {
                        id: term,
                        text: term
                    };
                }
            });

            select2Builder.selector('#createRequestSelect')
                .wireModel('createRequest')
                .build();

            @foreach (MoneyRequestStatus::options() as $key => $name)
                select2Builder.selector('#statusChangeSelect_{{ $key }}')
                    .wireModel('statusChange.{{ $key }}')
                    .build();
            @endforeach
            });
    </script>
@endpush