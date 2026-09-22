<?php

namespace App\View\Components\ecommerce;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EcommerceMetrics extends Component
{
    public function __construct(
        public int|float $customerCount = 0,
        public int|float $orderCount = 0,
        public int|float $unitCount = 0,
        public int|float $driverCount = 0,
        public int|float $revenue = 0,
    ) {
    }

    public function render(): View|Closure|string
    {
        return view('components.ecommerce.ecommerce-metrics');
    }
}
