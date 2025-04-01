<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    public function store(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 0,
                'message' => 'You must be logged in to submit a rating.'
            ], 401); // 401 Unauthorized
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $product = Product::findOrFail($id);
        $user = Auth::user();

        $existingRating = Rating::where('product_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingRating) {
            return response()->json([
                'status' => 0,
                'message' => 'You have already rated this product.'
            ], 400); // 400 Bad Request
        }

        $rating = Rating::create([
            'product_id' => $id,
            'user_id'    => $user->id,
            'rating'     => $validated['rating'],
        ]);

        $this->updateProductRating($product);

        return response()->json([
            'status' => 1,
            'message' => 'Rating submitted!',
            'average_rating' => $product->average_rating,
            'rating_count' => $product->rating_count
        ]);
    }

    private function updateProductRating(Product $product)
    {
        $averageRating = Rating::where('product_id', $product->id)
            ->active()
            ->avg('rating');

        $ratingCount = Rating::where('product_id', $product->id)
            ->active()
            ->count();

        $product->update([
            'average_rating' => $averageRating,
            'rating_count'   => $ratingCount,
        ]);
    }

    public function getRatings($productId)
    {
        $ratings = Rating::where('product_id', $productId)->get();
        $averageRating = Rating::averageRating($productId);
        $ratingDistribution = [
            '5_stars' => Rating::ratingCountByStars($productId, 5),
            '4_stars' => Rating::ratingCountByStars($productId, 4),
            '3_stars' => Rating::ratingCountByStars($productId, 3),
            '2_stars' => Rating::ratingCountByStars($productId, 2),
            '1_star'  => Rating::ratingCountByStars($productId, 1),
        ];

        return view('components.ratings', compact('ratings', 'averageRating', 'ratingDistribution'));
    }
}
