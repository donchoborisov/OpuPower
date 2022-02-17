<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('contact')->delete();
        
        \DB::table('contact')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Doncho Borisov',
                'email' => 'director@app.com',
                'message' => 'this is a test for contact form',
                'created_at' => '2022-02-14 11:37:04',
                'updated_at' => '2022-02-14 11:37:04',
            ),
        ));
        
        
    }
}