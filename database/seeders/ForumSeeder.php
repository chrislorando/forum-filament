<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Category;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();
        User::factory()->admin()->create(['email' => 'admin@example.com', 'username' => 'admin']);
        User::factory()->moderator()->create(['email' => 'moderator@example.com', 'username' => 'moderator']);

        $categories = Category::factory(5)->create();

        foreach ($categories as $category) {
            $parentBoards = Board::factory(rand(2, 4))->create(['category_id' => $category->id, 'parent_id' => null]);

            foreach ($parentBoards as $parentBoard) {
                $childBoards = Board::factory(rand(2, 4))->create(['category_id' => $category->id, 'parent_id' => $parentBoard->id]);

                foreach ($childBoards as $board) {
                    $topics = Topic::factory(rand(3, 10))->create(['board_id' => $board->id]);

                    foreach ($topics as $topic) {
                        Post::factory(1)->firstPost()->create([
                            'topic_id' => $topic->id,
                            'user_id' => $topic->user_id,
                        ]);

                        Post::factory(rand(2, 15))->create(['topic_id' => $topic->id]);
                    }
                }
            }

            $parentTopics = Topic::factory(rand(2, 5))->create(['board_id' => $parentBoard->id]);

            foreach ($parentTopics as $topic) {
                Post::factory(1)->firstPost()->create([
                    'topic_id' => $topic->id,
                    'user_id' => $topic->user_id,
                ]);

                Post::factory(rand(2, 15))->create(['topic_id' => $topic->id]);
            }
        }
    }
}
