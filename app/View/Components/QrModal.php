<?php

namespace App\View\Components;

use App\Models\Asset;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QrModal extends Component
{
    public function __construct(
        /**
         * Create a new component instance.
         */
        public Asset $asset
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.qr-modal');
    }
}
