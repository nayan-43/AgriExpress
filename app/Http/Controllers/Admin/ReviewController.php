<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * GET /admin/reviews
     */
    public function index(Request $request)
    {
        $reviews = Review::with(['product', 'user'])
            ->when($request->status === 'pending', fn ($q) => $q->pending())
            ->when($request->status === 'approved', fn ($q) => $q->approved())
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.reviews.index', compact('reviews'));
    }

    /**
     * PATCH /admin/reviews/{review}/status — approve or unpublish a review.
     */
    public function updateStatus(Request $request, Review $review)
    {
        $review->update(['status' => $request->boolean('status')]);

        return back()->with('status', $review->status ? 'Review approved.' : 'Review unpublished.');
    }

    /**
     * DELETE /admin/reviews/{review}
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('status', 'Review deleted.');
    }
}
