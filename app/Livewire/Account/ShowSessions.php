<?php

namespace App\Livewire\Account;

use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Component;
use Str;

class ShowSessions extends Component
{
    use Toast;

    public string $current_password;

    public function render(): View
    {
        return view('livewire.account.show-sessions', [
            'sessions' => $this->getSessions(),
        ]);
    }

    public function logoutOtherDevices(): void
    {
        $user = Auth::user();

        if (Hash::check($this->current_password, $user->password)) {
            Auth::logoutOtherDevices($this->current_password);
            $this->deleteSessions();

            $user->setRememberToken(Str::random(64));
            $user->save();

            $this->toastSuccess('Sesiones Cerradas');
            $this->resetErrorBag();
            $this->reset();
        } else {
            $this->addError('current_password', 'Contraseña incorrecta');
        }
    }

    private function getSessions(): Collection
    {
        $icons = [
            'mobile'  => 'fa-solid fa-mobile-screen-button',
            'tablet'  => 'fa-solid fa-tablet-screen-button',
            'desktop' => 'fa-solid fa-desktop',
        ];

        return DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(fn ($session) => [
                'id'                => $session->id,
                'ip_address'        => $session->ip_address,
                'user_agent'        => $session->user_agent,
                'last_activity'     => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'is_current_device' => $session->id === Session::getId(),
                'device_type'       => $type = $this->getDeviceType($session->user_agent),
                'icon'              => $icons[$type],
            ]
            );
    }

    private function deleteSessions(): void
    {
        DB::table('sessions')
            ->where('id', '!=', Session::getId())
            ->delete();
    }

    private function getDeviceType($userAgent): string
    {
        $mobileKeywords = ['Mobile', 'Android', 'iPhone', 'iPod', 'BlackBerry', 'Windows Phone', 'Opera Mini', 'IEMobile'];
        $tabletKeywords = ['iPad', 'Android', 'Kindle', 'Silk', 'Tablet'];

        foreach ($tabletKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false && stripos($userAgent, 'Mobile') === false) {
                return 'tablet';
            }
        }

        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return 'mobile';
            }
        }

        return 'desktop';
    }
}
