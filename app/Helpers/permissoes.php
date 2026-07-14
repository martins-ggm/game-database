<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('ehAdmin')) {

    function ehAdmin(): bool
    {
        return Auth::check() && (bool) Auth::user()->admin;
    }
}
