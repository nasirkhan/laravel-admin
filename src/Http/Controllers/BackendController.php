<?php

namespace Nasirkhan\Admin\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class BackendController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        return view('admin::index');
    }
}
