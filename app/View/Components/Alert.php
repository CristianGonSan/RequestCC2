<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{

    public $type;
    public $message;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if (session()->has('success')) {
            $this->type = 'success';
            $this->message = session('success');
        } elseif (session()->has('error')) {
            $this->type = 'error';
            $this->message = session('error');
        } elseif (session()->has('warning')) {
            $this->type = 'warning';
            $this->message = session('warning');
        } elseif (session()->has('info')) {
            $this->type = 'info';
            $this->message = session('info');
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
