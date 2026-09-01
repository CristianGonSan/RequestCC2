<div wire:ignore.self id="modalFulfillItem" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="modalFulfillItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            @if ($fulfillingItem)
                <form wire:submit="submitFulfillment">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFulfillItemLabel">
                            Suplir material
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body form-row">
                        <div class="col-12 mb-3">
                            <strong>{{ $fulfillingItem->material->name }}</strong>
                            @if ($fulfillingItem->material->code)
                                <span class="text-muted">({{ $fulfillingItem->material->code }})</span>
                            @endif
                            <div class="text-muted">
                                Restan {{ number_format($fulfillingItem->remaining_quantity, 3) }}
                                {{ $fulfillingItem->unit->symbol }}
                            </div>
                        </div>

                        <x-adminlte-input fgroup-class="col-md-6" name="fulfillQuantity"
                            label="Cantidad ({{ $fulfillingItem->unit->symbol }}) *" placeholder="0.000"
                            type="number" step="0.001" max="{{ $fulfillingItem->remaining_quantity }}" wire:model="fulfillQuantity" required />

                        <x-adminlte-input fgroup-class="col-md-6" name="fulfillCost" label="Costo ($) *"
                            placeholder="0.00" type="number" step="0.01" wire:model="fulfillCost" required />
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            Cancelar
                        </button>

                        <x-livewire.loading-button type="submit" label="Guardar" icon="check"
                            wire:target="submitFulfillment" />
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
