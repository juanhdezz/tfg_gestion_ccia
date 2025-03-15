<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DatabaseSelector extends Component
{
    public $currentConnection;

    public function __construct()
    {
        $this->currentConnection = Session::get('db_connection', 'mysql');
    }

    public function render(): View
    {
        return view('components.database-selector');
    }
}