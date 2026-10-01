<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class HomeController extends Controller
{
    public function index(): void
    {
        Database::connection();

        $this->view('home', [
            'title' => 'Fluent',
            'databaseConnected' => true,
        ]);
    }
}