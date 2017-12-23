<?php

use Hydrofon\Object;
use Illuminate\Database\Seeder;

class ObjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('local')) {
            factory(Object::class, 20)->create();
        }
    }
}
