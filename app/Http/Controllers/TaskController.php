<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
                     ->orderBy('end_date', 'asc')
                     ->paginate(6);
                     
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
            Log::info('Attempting to create task', [
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'end_date' => 'required|date',
                'priority' => 'required|in:low,medium,high',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
            ]);

            Log::info('Validation passed', [
                'validated_data' => $validated
            ]);

            // Handle image upload if present
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/task_images', $imageName);
                $validated['image_path'] = 'task_images/' . $imageName;
                
                Log::info('Image uploaded', [
                    'image_name' => $imageName,
                    'image_path' => $validated['image_path']
                ]);
            }

            // Create task with proper date handling
            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'start_date' => now(),
                'end_date' => $validated['end_date'],
                'priority' => $validated['priority'],
                'status' => 'pending',
                'user_id' => auth()->id(),
                'image_path' => $validated['image_path'] ?? null
            ]);

            Log::info('Task created successfully', [
                'task_id' => $task->id,
                'task_data' => $task->toArray()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task created successfully',
                    'task' => $task
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Task created successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Task validation failed:', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Task creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating task: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error creating task')->withInput();
        }
    }

    public function edit(Task $task)
    {
        // Pastikan user hanya bisa edit task miliknya
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        try {
            // Pastikan user hanya bisa update task miliknya
            if ($task->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Validasi input
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'end_date' => 'required|date',
                'priority' => 'required|in:low,medium,high',
                'status' => 'required|in:pending,completed',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle image upload if present
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($task->image_path) {
                    Storage::delete('public/' . $task->image_path);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/task_images', $imageName);
                $validated['image_path'] = 'task_images/' . $imageName;
            }

            // Update task dengan menambahkan waktu ke end_date
            $validated['end_date'] = $validated['end_date'] . ' 23:59:59';
            $task->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $task
            ]);

        } catch (\Exception $e) {
            \Log::error('Task update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update task'
            ], 500);
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
                'message' => $task->status === 'completed' ? 'Tugas berhasil ditandai selesai' : 'Tugas ditandai belum selesai'
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