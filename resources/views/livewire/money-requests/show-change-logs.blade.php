@use('App\Models\Common\ChangeLog')
@use('App\Enums\Common\ChangeLogEvent')

<div class="card mb-0" style="min-height: 65vh">
    <div class="card-header">
        <h2 class="card-title">Historial</h2>

        <div class="card-tools">
            <button wire:click="$refresh" class="btn btn-tool">
                <i wire:loading.class="fa-spin" class="fas fa-fw fa-arrows-rotate"></i>
            </button>
        </div>
    </div>

    <div class="card-body p-0 d-flex flex-column">
        @php
            $fieldLabels = __('fields.labels');
        @endphp

        <div class="list-group list-group-flush flex-grow-1 overflow-auto">
            @forelse ($changeLogs as $changeLog)
                @php
                    $details = $changeLog->detailsBag();
                    $fields = $details->array('fields');
                    $isStatusChange = $changeLog->event === ChangeLogEvent::StatusChanged;
                @endphp
                <div class="list-group-item" wire:key="money-request-change-log-{{ $changeLog->id }}">
                    <p class="mb-1">
                        <span class="badge badge-{{ $changeLog->event_bs_color }}">
                            {{ $changeLog->event_label }}
                            @if ($changeLog->event === ChangeLogEvent::Edited && $details->has('count_edit'))
                                #{{ $details->int('count_edit') }}
                            @endif
                        </span>
                        @if ($details->has('legacy'))
                            <span class="badge badge-secondary">Legacy</span>
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

                    @if ($isStatusChange)
                        <p class="mb-0">
                            @if ($statusChanges[$changeLog->id]['legacy'])
                                {{ $statusChanges[$changeLog->id]['text'] }}
                            @else
                                Cambio de <span class="badge badge-{{ $statusChanges[$changeLog->id]['beforeColor'] }}">{{ $statusChanges[$changeLog->id]['before'] }}</span>
                                a <span class="badge badge-{{ $statusChanges[$changeLog->id]['afterColor'] }}">{{ $statusChanges[$changeLog->id]['after'] }}</span>
                            @endif
                        </p>
                    @else
                        <button class="btn btn-link px-0" type="button" data-toggle="collapse"
                            data-target="#money-request-change-log-details-{{ $changeLog->id }}" aria-expanded="false"
                            aria-controls="money-request-change-log-details-{{ $changeLog->id }}">
                            Ver detalles <i class="fas fa-chevron-down fa-xs ml-1"></i>
                        </button>
                        <div class="collapse" id="money-request-change-log-details-{{ $changeLog->id }}">
                            @if ($details->has('legacy'))
                                <div class="border rounded bg-light p-3" style="white-space: pre-line">{{ trim(strip_tags($details->string('legacy'))) }}</div>
                            @else
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
                                                    <td>{{ ChangeLog::formatValue($before) }}</td>
                                                    <td>{{ ChangeLog::formatValue($after) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="list-group-item d-flex flex-column align-items-center justify-content-center text-muted flex-grow-1">
                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                    No hay cambios registrados.
                </div>
            @endforelse
        </div>
    </div>

    <div class="card-footer pb-0">
        {{ $changeLogs->links() }}
    </div>
</div>
