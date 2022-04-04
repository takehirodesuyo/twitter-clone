<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // ここに書いてあるseederが php artisan db:seed 実行
            UsersTableSeeder::class,
            TweetsTableSeeder::class,
            CommentsTableSeeder::class,
            FavoritesTableSeeder::class,
            FollowersTableSeeder::class,
        ]);
    }
    
}