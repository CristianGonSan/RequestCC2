<?php

namespace App\Http\Controllers\Configurations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailNotificationsController extends Controller
{
    public function index() {
        return view('configurations.notifications.mail-settings');
    }
}
