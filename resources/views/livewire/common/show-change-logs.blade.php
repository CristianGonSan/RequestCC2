@use('App\Models\Common\ChangeLog')

<div class="card mb-0" style="height: 65vh">
    <div class="card-header">
        <h2 class="card-title">Historial</h2>

        <div class="card-tools">
            <button wire:click="$refresh" class="btn btn-tool">
                <i wire:loading.class="fa-spin" class="fas fa-fw fa-arrows-rotate"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        @php($fieldLabels = __('fields.labels'))
        @forelse ($changeLogs as $changeLog)
            @php
                $details = $changeLog->detailsBag();
                $fields = $details->array('fields', $details->all());
            @endphp
            <div class="border-bottom mb-3 pb-3" wire:key="change-log-{{ $changeLog->id }}">
                <p class="mb-1">
                    <span class="badge badge-{{ $changeLog->event->bootstrapColorClass() }}">
                        {{ $changeLog->event->label() }}
                    </span>
                </p>
                <h6 class="mb-1">
                    @if ($changeLog->user)
                        {{ $changeLog->user->name }} - ({{ $changeLog->user->email }})
                    @else
                        Usuario no disponible
                    @endif
                </h6>
                <small class="text-muted d-block mb-2">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ $changeLog->created_at->format('d-m-Y h:i:s a') }} ||
                    {{ $changeLog->created_at->diffForHumans() }}
                </small>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th style="width: 20%">Campo</th>
                                <th style="width: 40%">Antes</th>
                                <th style="width: 40%">Después</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fields as $field => $values)
                                <tr>
                                    <td class="font-weight-bold">
                                        {{ $fieldLabels[$field] ?? str($field)->replace('_', ' ')->ucfirst() }}
                                    </td>
                                    <td>
                                        {{ ChangeLog::formatValue($values['before'] ?? null) }}
                                    </td>
                                    <td>
                                        {{ ChangeLog::formatValue($values['after'] ?? null) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="d-flex flex-column align-items-center justify-content-center text-muted h-100 py-5">
                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                No hay cambios registrados.
            </div>
        @endforelse
    </div>

    <div class="card-footer pb-0">
        {{ $changeLogs->links() }}
    </div>
</div>
