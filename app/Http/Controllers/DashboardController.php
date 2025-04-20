<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all tasks with pagination
        $tasks = Task::where('user_id', $user->id)
                    ->orderBy('end_date', 'asc')
                    ->paginate(10);
        
        // Upcoming deadlines - hanya tampilkan yang belum selesai (pending)
        $upcomingDeadlines = Task::where('user_id', $user->id)
            ->where('end_date', '>=', now())
            ->where('status', 'pending')
            ->orderBy('end_date', 'asc')
            ->get();

        // Overdue tasks - tugas yang terlewat dan belum selesai
        $overdueTasks = Task::where('user_id', $user->id)
            ->where('end_date', '<', now())
            ->where('status', 'pending')
            ->orderBy('end_date', 'desc')
            ->get();

        // Total tasks
        $totalTasks = Task::where('user_id', $user->id)->count();

        // Task statistics
        $highPriorityCount = Task::where('user_id', $user->id)
            ->where('priority', 'high')
            ->count();
        
        $mediumPriorityCount = Task::where('user_id', $user->id)
            ->where('priority', 'medium')
            ->count();
        
        $lowPriorityCount = Task::where('user_id', $user->id)
            ->where('priority', 'low')
            ->count();

        // Completion statistics
        $completedTasks = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        $pendingTasks = Task::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Weekly task counts for the current week
        $weeklyStats = [];
        $startOfWeek = now()->startOfWeek();
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            
            // Get total tasks for the day
            $totalTasksDay = Task::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->count();
            
            // Get completed tasks for the day
            $completedTasksDay = Task::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('created_at', $date)
                ->count();
            
            $weeklyStats[] = [
                'total' => $totalTasksDay,
                'completed' => $completedTasksDay
            ];
        }

        // Menghitung persentase dan membulatkannya
        $percentage = $totalTasks > 0 ? floor(($completedTasks / $totalTasks) * 100) : 0;

        return view('dashboard', compact(
            'tasks',
            'upcomingDeadlines',
            'overdueTasks',
            'totalTasks',
            'highPriorityCount',
            'mediumPriorityCount',
            'lowPriorityCount',
            'completedTasks',
            'pendingTasks',
            'weeklyStats',
            'percentage'
        ));
    }
} 