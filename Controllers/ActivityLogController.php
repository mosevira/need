<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activityLogs = ActivityLog::where('user_id', Auth::id())->latest()->paginate(10);
        return view('storekeeper.activity_logs.index', compact('activityLogs'));
    }
}
