<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Exam;
use App\Models\Newsline;
use App\Models\OfficerSubscriber;
use App\Models\Order;
use App\Models\Page;
use App\Models\ResultRegistration;
use App\Models\Review;
use App\Models\SoldierSubscriber;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Pages - a few parents with children
        $officer = Page::factory()->create([
            'title' => 'Join as an Officer',
            'slug'  => 'join-as-officer',
            'menu_name' => 'Join as Officer',
        ]);

        Page::factory()->count(3)->create(['parent_id' => $officer->id]);

        Page::factory()->create([
            'title' => 'Join as Jawan / JCO',
            'slug'  => 'join-as-jawan',
            'menu_name' => 'Jawan/JCO',
        ]);

        // Catalog
        Book::factory()->count(12)->create();
        Exam::factory()->count(4)->create();

        // Registrations
        ResultRegistration::factory()->count(60)->create();

        // Subscribers - lots
        OfficerSubscriber::factory()->count(40)->create();
        SoldierSubscriber::factory()->count(40)->create();

        // Orders across all statuses
        Order::factory()->count(30)->create();

        // Content
        Newsline::factory()->count(4)->create();
        Review::factory()->count(6)->create();

        $this->command->info('Demo data seeded.');
    }
}
