<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductReviews extends Component
{
    public $product;
    public $ratings;
    public $averageRating;
    public $ratingCounts;
    public $ratingPercentages;
    public $ratingCount;

    public function __construct($product, $ratings, $averageRating, $ratingCounts, $ratingPercentages, $ratingCount)
    {
        $this->product = $product;
        $this->ratings = $ratings;
        $this->averageRating = $averageRating;
        $this->ratingCounts = $ratingCounts;
        $this->ratingPercentages = $ratingPercentages;
        $this->ratingCount = $ratingCount;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-reviews');
    }
}
