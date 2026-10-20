@use('App\Models\Common\ChangeLog')

<div class="card mb-0" style="min-height: 65vh">
    <div class="card-header">
        <h2 class="card-title">Historial</h2>

        <div class="card-tools">
            <button wire:click="$refresh" class="btn btn-tool">
                <i wire:loading.class="fa-spin" class="fas fa-fw fa-arrows-rotate"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        @php
            $fieldLabels = __('fields.labels');
        @endphp

        @forelse ($changeLogs as $changeLog)
            @php
                $details = $changeLog->detailsBag();
                $fields = $details->array('fields');
            @endphp
            <div class="border-bottom mb-3 pb-3" wire:key="income-change-log-{{ $changeLog->id }}">
                <p class="mb-1">
                    @if ($details->has('count_edit'))
                        <span class="badge badge-{{ $changeLog->event->bootstrapColorClass() }}">
                            Edición #{{ $details->int('count_edit') }}
                        </span>
                    @endif
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

                <button class="btn btn-link px-0" type="button" data-toggle="collapse"
                    data-target="#income-change-log-details-{{ $changeLog->id }}" aria-expanded="false"
                    aria-controls="income-change-log-details-{{ $changeLog->id }}">
                    Ver detalles <i class="fas fa-chevron-down fa-xs ml-1"></i>
                </button>
                <div class="collapse" id="income-change-log-details-{{ $changeLog->id }}">
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
                                    @php
                                        $before = $values['before'] ?? null;
                                        $after = $values['after'] ?? null;
                                        $before = is_array($before) ? ($before['name'] ?? $before['id'] ?? null) : $before;
                                        $after = is_array($after) ? ($after['name'] ?? $after['id'] ?? null) : $after;
                                    @endphp
                                    <tr>
                                        <td class="font-weight-bold">
                                            {{ $fieldLabels[$field] ?? str($field)->replace('_', ' ')->ucfirst() }}
                                        </td>
                                        <td>
                                            {{ ChangeLog::formatValue($before) }}
                                        </td>
                                        <td>
                                            {{ ChangeLog::formatValue($after) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
