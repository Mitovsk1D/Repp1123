<?php

namespace App\Http\Controllers\API;

use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AchievementController extends BaseController
{
    /**
     * Display all achievements.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $achievements = Achievement::all();
        return $this->sendResponse($achievements, 'Achievements retrieved successfully.');
    }

    /**
     * Display the authenticated user's achievements.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function myAchievements()
    {
        $userAchievements = UserAchievement::with('achievement')
            ->where('user_id', Auth::id())
            ->get();

        $formattedAchievements = $userAchievements->map(function ($userAchievement) {
            return [
                'id' => $userAchievement->achievement->id,
                'name' => $userAchievement->achievement->name,
                'description' => $userAchievement->achievement->description,
                'icon' => $userAchievement->achievement->icon,
                'points' => $userAchievement->achievement->points,
                'earned_at' => $userAchievement->created_at,
            ];
        });

        return $this->sendResponse($formattedAchievements, 'User achievements retrieved successfully.');
    }

    /**
     * Display a specific user's achievements (admin only).
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAchievements($userId)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have permission to view other users\' achievements'], 403);
        }

        $user = User::find($userId);

        if (!$user) {
            return $this->sendError('User not found.');
        }

        $userAchievements = UserAchievement::with('achievement')
            ->where('user_id', $userId)
            ->get();

        $formattedAchievements = $userAchievements->map(function ($userAchievement) {
            return [
                'id' => $userAchievement->achievement->id,
                'name' => $userAchievement->achievement->name,
                'description' => $userAchievement->achievement->description,
                'icon' => $userAchievement->achievement->icon,
                'points' => $userAchievement->achievement->points,
                'earned_at' => $userAchievement->created_at,
            ];
        });

        return $this->sendResponse($formattedAchievements, 'User achievements retrieved successfully.');
    }

    /**
     * Award an achievement to a user (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function awardAchievement(Request $request)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have permission to award achievements'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'achievement_id' => 'required|exists:achievements,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        // Check if the user already has this achievement
        $existingAchievement = UserAchievement::where('user_id', $request->user_id)
            ->where('achievement_id', $request->achievement_id)
            ->first();

        if ($existingAchievement) {
            return $this->sendError(
                'Achievement already awarded',
                ['error' => 'This user already has this achievement'],
                422
            );
        }

        $userAchievement = new UserAchievement();
        $userAchievement->user_id = $request->user_id;
        $userAchievement->achievement_id = $request->achievement_id;
        $userAchievement->save();

        $achievement = Achievement::find($request->achievement_id);

        return $this->sendResponse([
            'achievement' => $achievement,
            'awarded_to' => User::find($request->user_id)->name,
            'awarded_at' => $userAchievement->created_at,
        ], 'Achievement awarded successfully.');
    }

    /**
     * Create a new achievement (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have permission to create achievements'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:achievements',
            'description' => 'required|string',
            'icon' => 'required|string',
            'points' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $achievement = new Achievement();
        $achievement->name = $request->name;
        $achievement->description = $request->description;
        $achievement->icon = $request->icon;
        $achievement->points = $request->points;
        $achievement->save();

        return $this->sendResponse($achievement, 'Achievement created successfully.');
    }

    /**
     * Update an achievement (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have permission to update achievements'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255|unique:achievements,name,' . $id,
            'description' => 'string',
            'icon' => 'string',
            'points' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $achievement = Achievement::find($id);

        if (!$achievement) {
            return $this->sendError('Achievement not found.');
        }

        if (isset($request->name)) {
            $achievement->name = $request->name;
        }

        if (isset($request->description)) {
            $achievement->description = $request->description;
        }

        if (isset($request->icon)) {
            $achievement->icon = $request->icon;
        }

        if (isset($request->points)) {
            $achievement->points = $request->points;
        }

        $achievement->save();

        return $this->sendResponse($achievement, 'Achievement updated successfully.');
    }

    /**
     * Delete an achievement (admin only).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have permission to delete achievements'], 403);
        }

        $achievement = Achievement::find($id);

        if (!$achievement) {
            return $this->sendError('Achievement not found.');
        }

        // Check if any users have this achievement
        $userCount = UserAchievement::where('achievement_id', $id)->count();
        if ($userCount > 0) {
            return $this->sendError(
                'Cannot delete achievement',
                ['error' => 'This achievement has been awarded to users. Remove the awards first.'],
                422
            );
        }

        $achievement->delete();

        return $this->sendResponse([], 'Achievement deleted successfully.');
    }

    /**
     * Revoke an achievement from a user (admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeAchievement(Request $request)
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have permission to revoke achievements'], 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'achievement_id' => 'required|exists:achievements,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $userAchievement = UserAchievement::where('user_id', $request->user_id)
            ->where('achievement_id', $request->achievement_id)
            ->first();

        if (!$userAchievement) {
            return $this->sendError('User does not have this achievement.');
        }

        $userAchievement->delete();

        return $this->sendResponse([], 'Achievement revoked successfully.');
    }
}
