<?php

namespace App\Livewire\Account;

use Illuminate\Support\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Str;

class ShowSessions extends Component
{
    public $currentPassword;

    public function render()
    {
        return view('livewire.account.show-sessions', [
            'sessions' => $this->getSessions()
        ]);
    }

    public function logoutOtherDevices()
    {
        $user = Auth::user();

        if (Hash::check($this->currentPassword, $user->password)) {
            Auth::logoutOtherDevices($this->currentPassword);
            $this->deleteSessions();

            $user->setRememberToken(Str::random(60));
            $user->save();

            session()->flash('message', 'Sesiones cerradas con éxito.');
        } else {
            session()->flash('error', 'Contraseña incorrecta');
        }

        $this->reset('currentPassword');
    }

    private function getSessions()
    {
        $icons = [
            'mobile' => 'fa-solid fa-mobile-screen-button',
            'tablet' => 'fa-solid fa-tablet-screen-button',
            'desktop' => 'fa-solid fa-desktop'
        ];

        return DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) use ($icons) {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'is_current_device' => $session->id === Session::getId(),
                    'device_type' => $d = $this->getDeviceType($session->user_agent),
                    'icon' => $icons[$d]
                ];
            });
    }

    private function getDeviceType($userAgent)
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

    private function getIcon($deviceType) {}

    private function deleteSessions()
    {
        DB::table('sessions')
            ->where('id', '!=', Session::getId())
            ->delete();
    }
}
