<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Filter by Action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by Model Type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Search filter (handles Name, ID, Model, IP)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%")
                  ->orWhere('model_id', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Paginate logs
        $logs = $query->paginate(20)->withQueryString();

        // Get unique model types for filter dropdown
        $modelTypes = AuditLog::select('model_type')
            ->distinct()
            ->orderBy('model_type')
            ->pluck('model_type');

        return view('dashboards.super_admin.audit_logs', compact('logs', 'modelTypes'));
    }
}
