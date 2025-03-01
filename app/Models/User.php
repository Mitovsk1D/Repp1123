<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the professor data associated with the user.
     */
    public function professorData(): HasOne
    {
        return $this->hasOne(ProfessorData::class);
    }

    /**
     * Get the student data associated with the user.
     */
    public function studentData(): HasOne
    {
        return $this->hasOne(StudentData::class);
    }

    /**
     * Get the achievements for the user.
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * Get the forum threads created by the user.
     */
    public function forumThreads(): HasMany
    {
        return $this->hasMany(ForumThread::class);
    }

    /**
     * Get the forum comments created by the user.
     */
    public function forumComments(): HasMany
    {
        return $this->hasMany(ForumComment::class);
    }

    /**
     * Get the reviews created by the user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the user's wishlist courses.
     */
    public function wishlistCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'wishlists');
    }

    /**
     * Get the user's progress on lessons.
     */
    public function progress(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'user_progress')
            ->withPivot('is_completed')
            ->withTimestamps();
    }

    /**
     * Get the messages sent by the user.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the messages received by the user.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get the user responses for quizzes.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(UserResponse::class);
    }

    /**
     * Get the course views by the user.
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a professor.
     */
    public function isProfessor(): bool
    {
        return $this->role === 'professor';
    }

    /**
     * Check if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get the courses that the user has created (as a professor).
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'professor_id');
    }

    /**
     * Get the courses that the user has wishlisted.
     */
    public function wishlist()
    {
        return $this->belongsToMany(Course::class, 'wishlists')
            ->withTimestamps();
    }

    /**
     * Get the courses that the user is enrolled in.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the enrolled courses for the user.
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('progress', 'completed', 'completion_date')
            ->withTimestamps();
    }

    /**
     * Get the achievements that the user has earned.
     */
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withTimestamps();
    }

    /**
     * Get the user achievements for this user.
     */
    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
}
