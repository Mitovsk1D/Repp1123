<?php

namespace App\Http\Controllers\API;

use App\Models\Wishlist;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishlistController extends BaseController
{
    /**
     * Display the authenticated user's wishlist.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $wishlist = Wishlist::with('course.category')
            ->where('user_id', Auth::id())
            ->get()
            ->pluck('course');

        return $this->sendResponse($wishlist, 'Wishlist retrieved successfully.');
    }

    /**
     * Add a course to the authenticated user's wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        // Check if the course is already in the wishlist
        $existingWishlist = Wishlist::where('user_id', Auth::id())
            ->where('course_id', $request->course_id)
            ->first();

        if ($existingWishlist) {
            return $this->sendError(
                'Course already in wishlist',
                ['error' => 'This course is already in your wishlist'],
                422
            );
        }

        $wishlist = new Wishlist();
        $wishlist->user_id = Auth::id();
        $wishlist->course_id = $request->course_id;
        $wishlist->save();

        $course = Course::with('category')->find($request->course_id);

        return $this->sendResponse($course, 'Course added to wishlist successfully.');
    }

    /**
     * Remove a course from the authenticated user's wishlist.
     *
     * @param  int  $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($courseId)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->first();

        if (!$wishlist) {
            return $this->sendError('Course not found in wishlist.');
        }

        $wishlist->delete();

        return $this->sendResponse([], 'Course removed from wishlist successfully.');
    }
}
