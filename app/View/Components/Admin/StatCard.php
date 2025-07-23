<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;
use Illuminate\View\View;

class StatCard extends Component
{
    public function __construct(
        public string  $label,
        public int|float|string $value,
        public string  $icon = 'circle',   // fallback
    ) {}

    public function render(): View
    {
        return view('components.admin.stat-card');
    }
}
