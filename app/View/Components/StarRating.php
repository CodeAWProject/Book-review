<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class StarRating extends Component
{
    /**
     * Create a new component instance.
     */

     //readonly means that you can't modify this value
    public function __construct(
        public readonly ?float $rating
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.star-rating');
    }
}
