<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class FoundationPageController extends Controller
{
    public function view(string $view): View
    {
        return view($view);
    }
}
