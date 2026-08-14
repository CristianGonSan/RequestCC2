<?php

namespace App\Livewire\Configurations\Notifications;

use App\Enums\Requests\RequestStatus;
use App\Models\Setting;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Component;

class MailNotifications extends Component
{
    use Toast;

    public array $createRequest = [];
    public array $statusChange = [];

    public function mount(): void
    {
        $setting = Setting::dataBag('emailNotifications', [
            'createRequest' => [],
            'statusChange'  => [],
        ]);

        $this->createRequest = $setting->array('createRequest');
        $this->statusChange  = $setting->array('statusChange');
    }

    public function render(): View
    {
        return view('livewire.configurations.notifications.mail-notifications');
    }

    public function save(): void
    {
        $invalidEmails = $this->invalidEmails($this->createRequest);

        foreach (RequestStatus::options() as $key => $name) {
            $invalidEmails = \array_merge($invalidEmails, $this->invalidEmails($this->statusChange[$key] ?? []));
        }

        if (!empty($invalidEmails)) {
            $this->toastError('Correo(s) inválido(s): ' . \implode(', ', \array_unique($invalidEmails)));

            return;
        }

        Setting::set('emailNotifications', [
            'createRequest' => $this->createRequest,
            'statusChange'  => $this->statusChange,
        ]);

        $this->toastSuccess('Configuración de notificaciones guardada.');
    }

    private function invalidEmails(array $emails): array
    {
        return \array_values(\array_filter(
            $emails,
            static fn (string $email): bool => \filter_var($email, \FILTER_VALIDATE_EMAIL) === false
        ));
    }
}
