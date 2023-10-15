<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use App\Models\BookmarkList;
use Faker\Factory;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	// User::create([
    	// 	'name' => 'Made Aditya',
     //        'username' => 'madeaditya02',
     //        'email' => 'imadeaditya4@gmail.com',
     //        'job' => 'Back End Web Developer',
     //        'bio' => (Factory::create())->realText(100),
     //        'email_verified_at' => now(),
     //        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
     //        'remember_token' => Str::random(10),
    	// ]);
        $user = User::create([
            'name' => 'Made Aditya',
            'username' => 'adityaa',
            'email' => 'imadeaditya4@gmail.com',
            'location' => 'Bali, Indonesia',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'picture' => "https://ui-avatars.com/api/?name=Made Aditya&background=random",
            'github_link' => "https://github.com/adityaa",
            'instagram_link' => "https://instagram.com/adityaa",
        ]);
        User::factory(15)->create();
        // BookmarkList::factory(4)->create();
        $tag = Tag::create(['tag' => 'Javascript', 'slug' => 'javascript']);
        Tag::create(['tag' => 'Laravel', 'slug' => 'laravel']);
        Tag::create(['tag' => 'React', 'slug' => 'react']);
        Tag::create(['tag' => 'Angular', 'slug' => 'angular']);
        Tag::create(['tag' => 'Alpine', 'slug' => 'alpine']);
        Tag::create(['tag' => 'Java', 'slug' => 'java']);
        Tag::create(['tag' => 'Sanctum', 'slug' => 'sanctum']);
        Tag::create(['tag' => 'Jetstream', 'slug' => 'jetstream']);
        Tag::create(['tag' => 'AI', 'slug' => 'ai']);
        Tag::create(['tag' => 'Midtrans', 'slug' => 'midtrans']);
        Tag::create(['tag' => 'Gatsby', 'slug' => 'gatsby']);
        Tag::create(['tag' => 'Tailwind', 'slug' => 'tailwind']);
        Tag::create(['tag' => 'Bootstrap', 'slug' => 'bootstrap']);
        $tag2 = Tag::create(['tag' => 'PHP', 'slug' => 'php']);
        Post::factory(60)->create();
        Post::find(1)->likes()->createMany([
            ['user_id' => 2], ['user_id' => 3], ['user_id' => 4]
        ]);
        Post::find(1)->comments()->createMany([
            ['user_id' => 1, 'comment_id' => null, 'comment' => 'lorem'],
            ['user_id' => 1, 'comment_id' => 1, 'comment' => 'lorem ipsum'],
            ['user_id' => 1, 'comment_id' => 1, 'comment' => 'lorem ipsum'],
            ['user_id' => 1, 'comment_id' => null, 'comment' => 'ipsum'],
            ['user_id' => 1, 'comment_id' => 4, 'comment' => 'ipsum reply'],
        ]);
        $user->bookmarks()->attach([1,2,3,4,6]);
        $user->tags()->attach([1,2,12]);
        Post::find(1)->tags()->attach([1,2]);
        Post::find(2)->tags()->attach([1,3]);
        $tag->followers()->createMany([
            ['user_id' => 2], ['user_id' => 3], ['user_id' => 4]
        ]);
        $tag2->followers()->createMany([
            ['user_id' => 1], ['user_id' => 3]
        ]);

        $ids = [];
        for ($i=3; $i < 17; $i++) { 
            array_push($ids, $i);
        }
        $tag->posts()->attach($ids);

        User::find(2)->followers()->create(['user_id' => 1]);
    }
}
