<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class UpcomingDeadline extends Component
{
    public $upcomingDeadlines;

    public function __construct($upcomingDeadlines)
    {
        $this->upcomingDeadlines = $upcomingDeadlines;
    }

    public function render()
    {
        return view('components.dashboard.upcoming_deadline');
    }
} 