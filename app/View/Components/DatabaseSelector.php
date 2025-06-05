<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class DatabaseSelector extends Component
{
    public $currentConnection;
    public $context;

    public function __construct($context = 'general')
    {
        $this->currentConnection = Session::get('db_connection', 'mysql');
        $this->context = $context;
    }

    public function render(): View
    {
        return view('components.database-selector');
    }
}