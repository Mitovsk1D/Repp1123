<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedRoles();
        $this->seedUsers();
        $this->seedCategories();
        $this->seedProfessorsData();
        $this->seedStudentsData();
        $this->seedCourses();
        $this->seedCourseProfessor();
        $this->seedModules();
        $this->seedLessons();
        $this->seedQuizzes();
        $this->seedQuestions();
        $this->seedAnswers();
        $this->seedUserResponses();
        $this->seedUserProgress();
        $this->seedInterests();
        $this->seedStudentInterests();
        $this->seedAchievements();
        $this->seedForumThreads();
        $this->seedForumComments();
        $this->seedReviews();
        $this->seedWishlists();
        $this->seedNewsletterSubscriptions();
        $this->seedViews();
        $this->seedMessages();
    }

    private function loadJsonFile($filename)
    {
        $path = storage_path('app/json-data/' . $filename);
        return json_decode(file_get_contents($path), true);
    }

    private function seedRoles()
    {
        $roles = $this->loadJsonFile('roles.json');

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'id' => $role['id'],
                'name' => $role['name'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedUsers()
    {
        $users = $this->loadJsonFile('users.json');

        foreach ($users as $user) {
            DB::table('users')->insert([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']), // Hash the password
                'profile_picture' => $user['profile_picture'] ?? null,
                'role' => $user['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedCategories()
    {
        $categories = $this->loadJsonFile('categories.json');

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'id' => $category['id'],
                'name' => $category['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedProfessorsData()
    {
        $professorsData = $this->loadJsonFile('professors_data.json');

        foreach ($professorsData as $professor) {
            DB::table('professors_data')->insert([
                'id' => $professor['id'],
                'user_id' => $professor['user_id'],
                'bio' => $professor['bio'],
                'specialization' => $professor['specialization'],
                'education' => $professor['education'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedStudentsData()
    {
        $studentsData = $this->loadJsonFile('student_data.json');

        foreach ($studentsData as $student) {
            DB::table('students_data')->insert([
                'id' => $student['id'],
                'user_id' => $student['user_id'],
                'date_of_birth' => $student['date_of_birth'],
                'education_level' => $student['education_level'],
                'bio' => $student['bio'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedCourses()
    {
        $courses = $this->loadJsonFile('courses.json');

        foreach ($courses as $course) {
            DB::table('courses')->insert([
                'id' => $course['id'],
                'title' => $course['title'],
                'description' => $course['description'],
                'category_id' => $course['category_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedCourseProfessor()
    {
        $courseProfessors = $this->loadJsonFile('course_professor.json');

        foreach ($courseProfessors as $courseProfessor) {
            DB::table('course_professor')->insert([
                'id' => $courseProfessor['id'],
                'course_id' => $courseProfessor['course_id'],
                'professor_id' => $courseProfessor['professor_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedModules()
    {
        $modules = $this->loadJsonFile('modules.json');

        foreach ($modules as $module) {
            DB::table('modules')->insert([
                'id' => $module['id'],
                'course_id' => $module['course_id'],
                'title' => $module['title'],
                'description' => $module['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedLessons()
    {
        $lessons = $this->loadJsonFile('lessons.json');

        foreach ($lessons as $lesson) {
            DB::table('lessons')->insert([
                'id' => $lesson['id'],
                'module_id' => $lesson['module_id'],
                'title' => $lesson['title'],
                'content' => $lesson['content'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedQuizzes()
    {
        $quizzes = $this->loadJsonFile('quizzes.json');

        foreach ($quizzes as $quiz) {
            DB::table('quizzes')->insert([
                'id' => $quiz['id'],
                'module_id' => $quiz['module_id'],
                'title' => $quiz['title'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedQuestions()
    {
        $questions = $this->loadJsonFile('questions.json');

        foreach ($questions as $question) {
            DB::table('questions')->insert([
                'id' => $question['id'],
                'quiz_id' => $question['quiz_id'],
                'question_text' => $question['question_text'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedAnswers()
    {
        $answers = $this->loadJsonFile('answers.json');

        foreach ($answers as $answer) {
            DB::table('answers')->insert([
                'id' => $answer['id'],
                'question_id' => $answer['question_id'],
                'answer_text' => $answer['answer_text'],
                'is_correct' => $answer['is_correct'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedUserResponses()
    {
        $userResponses = $this->loadJsonFile('user_responses.json');

        foreach ($userResponses as $response) {
            DB::table('user_responses')->insert([
                'id' => $response['id'],
                'user_id' => $response['user_id'],
                'question_id' => $response['question_id'],
                'answer_id' => $response['answer_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedUserProgress()
    {
        $userProgresses = $this->loadJsonFile('user_progress.json');

        foreach ($userProgresses as $progress) {
            DB::table('user_progress')->insert([
                'id' => $progress['id'],
                'user_id' => $progress['user_id'],
                'lesson_id' => $progress['lesson_id'],
                'is_completed' => $progress['is_completed'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedInterests()
    {
        $interests = $this->loadJsonFile('interests.json');

        foreach ($interests as $interest) {
            DB::table('interests')->insert([
                'id' => $interest['id'],
                'name' => $interest['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedStudentInterests()
    {
        $studentInterests = $this->loadJsonFile('student_interests.json');

        foreach ($studentInterests as $studentInterest) {
            DB::table('student_interests')->insert([
                'id' => $studentInterest['id'],
                'student_id' => $studentInterest['student_id'],
                'interest_id' => $studentInterest['interest_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedAchievements()
    {
        $achievements = $this->loadJsonFile('achievements.json');

        foreach ($achievements as $achievement) {
            DB::table('achievements')->insert([
                'id' => $achievement['id'],
                'name' => $achievement['name'],
                'description' => $achievement['description'],
                'image' => $achievement['image'],
                'user_id' => $achievement['user_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedForumThreads()
    {
        $threads = $this->loadJsonFile('forum_threads.json');

        foreach ($threads as $thread) {
            DB::table('forum_threads')->insert([
                'id' => $thread['id'],
                'user_id' => $thread['user_id'],
                'title' => $thread['title'],
                'content' => $thread['content'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedForumComments()
    {
        $comments = $this->loadJsonFile('forum_comments.json');

        foreach ($comments as $comment) {
            DB::table('forum_comments')->insert([
                'id' => $comment['id'],
                'thread_id' => $comment['thread_id'],
                'user_id' => $comment['user_id'],
                'content' => $comment['content'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedReviews()
    {
        $reviews = $this->loadJsonFile('reviews.json');

        foreach ($reviews as $review) {
            DB::table('reviews')->insert([
                'id' => $review['id'],
                'user_id' => $review['user_id'],
                'course_id' => $review['course_id'],
                'rating' => $review['rating'],
                'comment' => $review['comment'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedWishlists()
    {
        $wishlists = $this->loadJsonFile('wishlist.json');

        foreach ($wishlists as $wishlist) {
            DB::table('wishlists')->insert([
                'id' => $wishlist['id'],
                'user_id' => $wishlist['user_id'],
                'course_id' => $wishlist['course_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedNewsletterSubscriptions()
    {
        $subscriptions = $this->loadJsonFile('newsletter_subscriptions.json');

        foreach ($subscriptions as $subscription) {
            DB::table('newsletter_subscriptions')->insert([
                'id' => $subscription['id'],
                'email' => $subscription['email'],
                'is_active' => $subscription['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedViews()
    {
        $views = $this->loadJsonFile('views.json');

        foreach ($views as $view) {
            DB::table('views')->insert([
                'id' => $view['id'],
                'user_id' => $view['user_id'],
                'course_id' => $view['course_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedMessages()
    {
        $messages = $this->loadJsonFile('messages.json');

        foreach ($messages as $message) {
            DB::table('messages')->insert([
                'id' => $message['id'],
                'sender_id' => $message['sender_id'],
                'receiver_id' => $message['receiver_id'],
                'message' => $message['message'],
                'sent_at' => $message['sent_at'],
                'is_read' => $message['is_read'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
