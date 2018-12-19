<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Interest;

class InterestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('interests')->truncate();

        $data = [
            [
                'name' => 'Art',
                'description' => 'For Passionate About Art',
            ],
            [
                'name' => 'Android',
                'description' => 'For Passionate About Mobile Apps',
            ],
            [
                'name' => 'Adventure',
                'description' => 'For Passionate About Adventure and Seeing Nature',
            ],
            [
                'name' => 'Bicycle',
                'description' => 'For People Who Loves Bike and Bicycle',
            ],
            [
                'name' => 'Book',
                'description' => 'For People Who Loves Book and adventure on minds',
            ],
            [
                'name' => 'Business',
                'description' => 'For People Who Business and Things',
            ],
            [
                'name' => 'Contest',
                'description' => 'For People Who Loves Contest of Anything',
            ],
            [
                'name' => 'Design',
                'description' => 'For People Who Loves Art and make Art Comes Life',
            ],
            [
                'name' => 'Dance',
                'description' => 'For People Who Loves Dancing',
            ],
            [
                'name' => 'Entertainment',
                'description' => 'For People Who Loves Entertaining their Minds',
            ],
            [
                'name' => 'Football',
                'description' => 'For People Who Passionate with Great Strategy of a Football',
            ],
            [
                'name' => 'Fashion',
                'description' => 'For People Who Loves Fashion',
            ],
            [
                'name' => 'Gaming',
                'description' => 'For People Who Loves Playful with their minds',
            ],
            [
                'name' => 'Greeting',
                'description' => 'For People Who Loves Greet Other People',
            ],
            [
                'name' => 'Healthy',
                'description' => 'For People Who Loves Nature and Stay Healthy',
            ],
            [
                'name' => 'Hobby',
                'description' => 'For People Who Loves make Something or doing Something',
            ],
            [
                'name' => 'IOT',
                'description' => 'For People Who Loves Compute on small Machine',
            ],
            [
                'name' => 'Indie',
                'description' => 'For People Who Loves Hipster Minds',
            ],
            [
                'name' => 'Language',
                'description' => 'For People Who Loves Interacting with Other People',
            ],
            [
                'name' => 'Movie',
                'description' => 'For People Who Loves Something Great Playing on Big Screen',
            ],
            [
                'name' => 'Meet up',
                'description' => 'For People Who Loves Meet Up with People',
            ],
            [
                'name' => 'Music',
                'description' => 'For People Who Loves Music',
            ],
        ];

        foreach ( $data as  $datas )
        {
            Interest::create($datas);
        }

    }
}
