<?php

use App\LendeeRelation;
use Illuminate\Database\Seeder;

class LendeeRelationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        LendeeRelation::insert([
            'employee',
            'client',
            'vendor',
            'other',
        ]);
    }
}
