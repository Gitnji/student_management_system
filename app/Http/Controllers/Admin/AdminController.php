<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected function school()
    {
        return Auth::user()->school;
    }

    protected function authorizeSchool(int $schoolId): void
    {
        if ($schoolId !== Auth::user()->school_id) {
            abort(403);
        }
    }
}