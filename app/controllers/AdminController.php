<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

class AdminController extends Controller {
    protected function requireAdmin(): void {
        if (!Auth::isAdmin()) {
            $this->abortForbidden();
        }
    }
}
