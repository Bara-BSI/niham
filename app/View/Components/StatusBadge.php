<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function render()
    {
        return view('components.status-badge');
    }
}
