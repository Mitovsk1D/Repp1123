<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\EnrollmentController;
use App\Http\Controllers\API\LessonController;
use App\Http\Controllers\API\AchievementController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Course public routes
Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{id}', [CourseController::class, 'show']);
Route::get('courses/search/{query}', [CourseController::class, 'search']);
Route::get('courses/category/{categoryId}', [CourseController::class, 'getByCategory']);

// Category public routes
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

// Achievement public routes
Route::get('achievements', [AchievementController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);

    // User profile routes
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile', [UserController::class, 'updateProfile']);

    // Course review routes
    Route::get('courses/{courseId}/reviews', [ReviewController::class, 'getByCourse']);
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::put('reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);

    // Wishlist routes
    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist', [WishlistController::class, 'add']);
    Route::delete('wishlist/{courseId}', [WishlistController::class, 'remove']);

    // Enrollment routes
    Route::get('enrollments', [EnrollmentController::class, 'myEnrollments']);
    Route::post('enroll', [EnrollmentController::class, 'enroll']);
    Route::put('enrollments/{id}/progress', [EnrollmentController::class, 'updateProgress']);

    // Lesson and module routes
    Route::get('modules/{moduleId}/lessons', [LessonController::class, 'getByModule']);
    Route::get('lessons/{id}', [LessonController::class, 'show']);
    Route::get('courses/{courseId}/modules', [LessonController::class, 'getModulesByCourse']);

    // Achievement routes
    Route::get('my-achievements', [AchievementController::class, 'myAchievements']);

    // Admin routes
    Route::middleware('admin')->group(function () {
        // User management
        Route::get('users', [UserController::class, 'index']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);

        // Course management
        Route::post('courses', [CourseController::class, 'store']);
        Route::put('courses/{id}', [CourseController::class, 'update']);
        Route::delete('courses/{id}', [CourseController::class, 'destroy']);

        // Category management
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // Enrollment management
        Route::get('all-enrollments', [EnrollmentController::class, 'index']);

        // Achievement management
        Route::get('users/{userId}/achievements', [AchievementController::class, 'getUserAchievements']);
        Route::post('achievements', [AchievementController::class, 'store']);
        Route::put('achievements/{id}', [AchievementController::class, 'update']);
        Route::delete('achievements/{id}', [AchievementController::class, 'destroy']);
        Route::post('award-achievement', [AchievementController::class, 'awardAchievement']);
        Route::post('revoke-achievement', [AchievementController::class, 'revokeAchievement']);
    });

    // Professor routes
    Route::middleware('professor')->group(function () {
        // Course management (professor can only manage their own courses)
        Route::post('my-courses', [CourseController::class, 'storeProfessorCourse']);
        Route::put('my-courses/{id}', [CourseController::class, 'updateProfessorCourse']);
        Route::delete('my-courses/{id}', [CourseController::class, 'destroyProfessorCourse']);
        Route::get('my-courses', [CourseController::class, 'getProfessorCourses']);

        // Lesson and module management
        Route::post('modules', [LessonController::class, 'storeModule']);
        Route::put('modules/{id}', [LessonController::class, 'updateModule']);
        Route::delete('modules/{id}', [LessonController::class, 'destroyModule']);
        Route::post('lessons', [LessonController::class, 'store']);
        Route::put('lessons/{id}', [LessonController::class, 'update']);
        Route::delete('lessons/{id}', [LessonController::class, 'destroy']);

        // Enrollment management (for professor's courses)
        Route::get('courses/{courseId}/enrollments', [EnrollmentController::class, 'getByCourse']);
    });
});
