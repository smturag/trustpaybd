<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DatabaseUpdateController extends Controller
{
    /**
     * Show database update page
     */
    public function index()
    {
        try {
            // Get list of pending migrations
            $pendingMigrations = $this->getPendingMigrations();
            $ranMigrations = $this->getRanMigrations();
            
            return view('admin.database.update', compact('pendingMigrations', 'ranMigrations'));
        } catch (\Exception $e) {
            return view('admin.database.update', [
                'pendingMigrations' => [],
                'ranMigrations' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Run database migrations
     */
    public function runMigrations(Request $request)
    {
        try {
            // Start output buffering to capture Artisan output
            ob_start();
            
            // Run migrations
            Artisan::call('migrate', [
                '--force' => true,
            ]);
            
            $output = Artisan::output();
            ob_end_clean();

            // Log the migration
            Log::info('Database migrations run successfully', [
                'admin_id' => auth('admin')->id(),
                'output' => $output
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Database updated successfully!',
                    'output' => $output
                ]);
            }

            return redirect()->back()->with('success', 'Database updated successfully!');

        } catch (\Exception $e) {
            Log::error('Database migration failed', [
                'error' => $e->getMessage(),
                'admin_id' => auth('admin')->id()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Migration failed: ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Migration failed: ' . $e->getMessage());
        }
    }

    /**
     * Check database status
     */
    public function checkStatus()
    {
        try {
            $pendingMigrations = $this->getPendingMigrations();
            $hasPending = count($pendingMigrations) > 0;

            return response()->json([
                'success' => true,
                'has_pending' => $hasPending,
                'pending_count' => count($pendingMigrations),
                'pending_migrations' => $pendingMigrations
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending migrations
     */
    private function getPendingMigrations()
    {
        try {
            // Check if migrations table exists
            if (!Schema::hasTable('migrations')) {
                return ['migrations_table_not_found' => 'Migrations table does not exist'];
            }

            $ran = DB::table('migrations')->pluck('migration')->toArray();
            $migrations = [];
            
            $migrationFiles = glob(database_path('migrations/*.php'));
            
            foreach ($migrationFiles as $file) {
                $migration = str_replace('.php', '', basename($file));
                if (!in_array($migration, $ran)) {
                    $migrations[] = $migration;
                }
            }

            return $migrations;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get ran migrations
     */
    private function getRanMigrations()
    {
        try {
            if (!Schema::hasTable('migrations')) {
                return [];
            }

            return DB::table('migrations')
                ->orderBy('batch', 'desc')
                ->orderBy('id', 'desc')
                ->limit(10)
                ->pluck('migration')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Create migrations table if not exists
     */
    public function createMigrationsTable()
    {
        try {
            if (!Schema::hasTable('migrations')) {
                Schema::create('migrations', function ($table) {
                    $table->increments('id');
                    $table->string('migration');
                    $table->integer('batch');
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Migrations table created successfully'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Migrations table already exists'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create migrations table: ' . $e->getMessage()
            ], 500);
        }
    }
}
