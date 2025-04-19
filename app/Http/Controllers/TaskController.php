<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
                     ->orderBy('end_date', 'asc')
                     ->paginate(10);
                     
        return view('tasks.index', compact('tasks'));
    }

    public function getTasks()
    {
        try {
            $tasks = Task::where('user_id', auth()->id())
                ->select('id', 'title', 'end_date', 'priority', 'status')
                ->orderBy('end_date', 'asc')
                ->get()
                ->map(function($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'start' => $task->end_date->format('Y-m-d'),
                        'className' => 'priority-' . $task->priority
                    ];
                });

            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error loading tasks!'], 500);
        }
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'priority' => 'required|in:low,medium,high'
            ]);

            // Set end_date sama dengan start_date
            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['start_date'], // Menggunakan start_date sebagai end_date
                'priority' => $validated['priority'],
                'status' => 'pending',
                'user_id' => auth()->id()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task created successfully',
                    'task' => $task
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Task created successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating task'
                ], 500);
            }

            return redirect()->back()->with('error', 'Error creating task')->withInput();
        }
    }

    public function edit(Task $task)
    {
        // Pastikan user hanya bisa edit task miliknya
        if ($task->user_id !== auth()->id()) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'You are not authorized to edit this task.');
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        try {
            // Pastikan user hanya bisa update task miliknya
            if ($task->user_id !== auth()->id()) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'You are not authorized to update this task.');
            }

            // Validasi input
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'end_date' => 'required|date',
                'priority' => 'required|in:low,medium,high',
                'status' => 'required|in:pending,completed'
            ]);

            // Update task
            $task->update($validated);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Task updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Task update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update task. Please try again.');
        }
    }

    public function destroy(Task $task)
    {
        try {
            if ($task->user_id !== auth()->id()) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'You are not authorized to delete this task.');
            }

            $task->delete();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Tugas berhasil dihapus');

        } catch (\Exception $e) {
            \Log::error('Task deletion failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('dashboard')
                ->with('error', 'Gagal menghapus tugas');
        }
    }

    public function updateStatus(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->status = $task->status === 'pending' ? 'completed' : 'pending';
        $task->save();

        return response()->json(['success' => true, 'status' => $task->status]);
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get tasks data
        $upcomingDeadlines = Task::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        // Get task statistics
        $totalTasks = Task::where('user_id', $user->id)->count();
        $completedTasks = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $pendingTasks = $totalTasks - $completedTasks;

        // Get priority statistics
        $highPriorityCount = Task::where('user_id', $user->id)
            ->where('priority', 'high')
            ->count();
        $mediumPriorityCount = Task::where('user_id', $user->id)
            ->where('priority', 'medium')
            ->count();
        $lowPriorityCount = Task::where('user_id', $user->id)
            ->where('priority', 'low')
            ->count();

        // Get weekly task counts
        $weeklyTaskCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->startOfWeek()->addDays($i);
            $weeklyTaskCounts[] = Task::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->count();
        }

        return view('dashboard', compact(
            'upcomingDeadlines',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'highPriorityCount',
            'mediumPriorityCount',
            'lowPriorityCount',
            'weeklyTaskCounts'
        ));
    }

    public function toggleStatus($id)
    {
        try {
            $task = Task::findOrFail($id);
            
            // Verify user owns this task
            if ($task->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }
            
            // Toggle the status
            $task->status = $task->status === 'completed' ? 'pending' : 'completed';
            $task->save();
            
            return response()->json([
                'success' => true,
                'message' => $task->status === 'completed' ? 'Tugas telah ditandai selesai' : 'Tugas ditandai belum selesai',
                'status' => $task->status
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error toggling task status:', [
                'task_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status tugas'
            ], 500);
        }
    }

    public function getStatistics()
    {
        $user = Auth::user();
        $totalTasks = Task::where('user_id', $user->id)->count();
        $completedTasks = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $pendingTasks = $totalTasks - $completedTasks;

        return response()->json([
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks
        ]);
    }
} 