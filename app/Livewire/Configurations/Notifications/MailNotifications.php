<?php

namespace App\Livewire\Configurations\Notifications;

use App\Models\Configuration;
use App\Models\RequestModel;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class MailNotifications extends Component
{
    private $key = 'emailNotifications';

    public $newCreateRequest = '';
    public $newStatusChange = [];

    public $statusOptions;

    public $createRequest = [];
    public $statusChange = [];

    public function mount()
    {
        $value = Configuration::getValue($this->key, [
            'createRequest' => [],
            'statusChange' => []
        ]);

        $this->createRequest = $value['createRequest'] ?? [];
        $this->statusChange = $value['statusChange'] ?? [];

        $this->statusOptions = RequestModel::STATUSES_TEXT;
    }

    public function render()
    {
        return view('livewire.configurations.notifications.mail-notifications');
    }

    public function addCreateEmail()
    {
        if ($this->newCreateRequest) {
            if (in_array($this->newCreateRequest, $this->createRequest)) {
                $this->dispatch('duplicateMail');
            } else {
                $this->createRequest[] = $this->newCreateRequest;
                $this->newCreateRequest = '';
            }
        }
    }

    public function removeCreateEmail($index)
    {
        unset($this->createRequest[$index]);
        $this->createRequest = array_values($this->createRequest);
    }

    public function addStatusChangeEmail($statusKey)
    {
        if ($this->newStatusChange[$statusKey]) {
            if (in_array($this->newStatusChange[$statusKey], $this->statusChange[$statusKey] ?? [])) {
                $this->dispatch('duplicateMail');
            } else {
                $this->statusChange[$statusKey][] = $this->newStatusChange[$statusKey];
                $this->newStatusChange[$statusKey] = '';
            }
        }
    }

    public function removeStatusChangeEmail($statusKey, $index)
    {
        unset($this->statusChange[$statusKey][$index]);
        $this->statusChange[$statusKey] = array_values($this->statusChange[$statusKey]);
    }

    public function save()
    {
        Configuration::setValue($this->key, [
            'createRequest' => $this->createRequest,
            'statusChange' => $this->statusChange
        ]);

        $this->dispatch('configurationUpdated');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        $this->dispatch('cacheCleaned');
    }
}
