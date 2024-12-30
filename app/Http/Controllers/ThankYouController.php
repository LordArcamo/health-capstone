<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class ThankYouController extends Controller
{
    public function show()
    {
        return Inertia::render('ThankYou');
    }
}
