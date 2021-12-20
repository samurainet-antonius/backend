<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MembersSeeder::class);
        
    }
}
