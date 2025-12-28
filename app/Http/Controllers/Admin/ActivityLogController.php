<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs listing
     */
    public function index(Request $request)
    {
        $query = AdminActivityLog::with(['causer'])->orderBy('created_at', 'desc');

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%")
                  ->orWhere('event', 'like', "%{$search}%");
            });
        }

        // Filter by log name
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by causer (admin)
        if ($request->filled('causer_id')) {
            $query->where('causer_type', 'App\Models\Admin')
                  ->where('causer_id', $request->causer_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by subject type
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        $logs = $query->paginate(50);

        // Get filter options
        $logNames = AdminActivityLog::select('log_name')
            ->distinct()
            ->whereNotNull('log_name')
            ->pluck('log_name');

        $events = AdminActivityLog::select('event')
            ->distinct()
            ->whereNotNull('event')
            ->pluck('event');

        $subjectTypes = AdminActivityLog::select('subject_type')
            ->distinct()
            ->whereNotNull('subject_type')
            ->pluck('subject_type');

        $admins = Admin::select('id', 'admin_name', 'username', 'email')->get();

        // Get statistics
        $stats = [
            'total' => AdminActivityLog::count(),
            'today' => AdminActivityLog::whereDate('created_at', today())->count(),
            'this_week' => AdminActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => AdminActivityLog::whereMonth('created_at', now()->month)->count(),
        ];

        // Get recent activity by event type
        $eventStats = AdminActivityLog::select('event', DB::raw('count(*) as count'))
            ->whereNotNull('event')
            ->groupBy('event')
            ->get()
            ->pluck('count', 'event')
            ->toArray();

        return view('admin.activity-logs', compact('logs', 'logNames', 'events', 'subjectTypes', 'admins', 'stats', 'eventStats'));
    }

    /**
     * Display specific activity log details
     */
    public function show($id)
    {
        $log = AdminActivityLog::with(['causer', 'subject'])->findOrFail($id);

        if (request()->ajax()) {
            $causerName = 'System';
            if ($log->causer) {
                $causerName = $log->causer->admin_name ?? $log->causer->name ?? $log->causer->username ?? 'Unknown';
            }
            
            return response()->json([
                'success' => true,
                'log' => $log,
                'causer_name' => $causerName,
                'causer_email' => $log->causer ? ($log->causer->email ?? '-') : '-',
                'subject_name' => $this->getSubjectName($log),
            ]);
        }

        return view('admin.activity-log-details', compact('log'));
    }

    /**
     * Delete activity log
     */
    public function destroy($id)
    {
        try {
            $log = AdminActivityLog::findOrFail($id);
            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Activity log deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete activity log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete activity logs
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_logs,id'
        ]);

        try {
            AdminActivityLog::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' activity logs deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete activity logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear old activity logs
     */
    public function clearOld(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1'
        ]);

        try {
            $date = now()->subDays($request->days);
            $count = AdminActivityLog::where('created_at', '<', $date)->delete();

            return response()->json([
                'success' => true,
                'message' => $count . ' old activity logs cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear old logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        $query = AdminActivityLog::with(['causer'])->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        $logs = $query->limit(1000)->get();

        $filename = 'activity_logs_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Log Name', 'Description', 'Event', 'Causer', 'Subject Type', 'Created At']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->log_name,
                    $log->description,
                    $log->event,
                    $log->causer ? $log->causer->name : 'System',
                    $log->subject_type,
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get subject name from log
     */
    private function getSubjectName($log)
    {
        if (!$log->subject) {
            return '-';
        }

        $subject = $log->subject;

        if (method_exists($subject, 'getName')) {
            return $subject->getName();
        }

        if (isset($subject->name)) {
            return $subject->name;
        }

        if (isset($subject->title)) {
            return $subject->title;
        }

        return class_basename($log->subject_type) . ' #' . $log->subject_id;
    }
}
