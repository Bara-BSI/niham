<?php

namespace App\View\Components;

use App\Models\Asset;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QrModal extends Component
{
    /**
     * Create a new component instance.
     */
    public Asset $asset;
    public function __construct(Asset $asset)
    {
        $this->asset = $asset;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.qr-modal');
    }
}
