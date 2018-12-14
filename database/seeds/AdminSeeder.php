<?php

use App\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 		DB::table('users')->truncate();

        User::create(
        	['name' => 'cyka', 'role' => 1, 'email' => 'shayan@apba.org.pk', 'password' => bcrypt('shayanAPBA007'), 'image' => 'dummy_image.png']
        	);
    }
}
