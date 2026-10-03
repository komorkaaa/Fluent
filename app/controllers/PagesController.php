<?php

namespace App\Controllers;

use App\Core\Controller;

class PagesController extends Controller {
    public function about(): void {
        $this->view('pages/about', [
            'title' => 'О компании — Fluent',
        ]);
    }

    public function contacts(): void {
        $this->view('pages/contacts', [
            'title' => 'Контакты — Fluent',
        ]);
    }

    public function privacy(): void {
        $this->view('pages/privacy', [
            'title' => 'Политика конфиденциальности — Fluent',
        ]);
    }
}