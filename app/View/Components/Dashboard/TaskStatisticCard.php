<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class TaskStatisticCard extends Component
{
    public $totalTasks;
    public $completedTasks;
    public $pendingTasks;

    public function __construct($totalTasks, $completedTasks, $pendingTasks)
    {
        $this->totalTasks = $totalTasks;
        $this->completedTasks = $completedTasks;
        $this->pendingTasks = $pendingTasks;
    }

    public function render()
    {
        return view('components.dashboard.task_statistic_card');
    }
} 