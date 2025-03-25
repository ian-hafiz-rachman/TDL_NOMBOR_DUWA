<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get tasks statistics
        $totalTasks = Task::where('user_id', $user->id)->count();
        $completedTasks = Task::where('user_id', $user->id)
                             ->where('status', 'completed')
                             ->count();
        $pendingTasks = Task::where('user_id', $user->id)
                           ->where('status', '!=', 'completed')
                           ->count();

        // Get upcoming deadlines
        $upcomingDeadlines = Task::where('user_id', $user->id)
                                ->where('status', '!=', 'completed')
                                ->where('end_date', '>=', now())
                                ->orderBy('end_date', 'asc')
                                ->take(5)
                                ->get();

        // Format events for calendar
        $events = Task::where('user_id', $user->id)
                     ->get()
                     ->map(function($task) {
                         return [
                             'id' => $task->id,
                             'title' => $task->title,
                             'start' => $task->end_date->format('Y-m-d'),
                             'className' => 'priority-' . $task->priority
                         ];
                     });

        return view('dashboard', compact(
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'upcomingDeadlines',
            'events'
        ));
    }
} 