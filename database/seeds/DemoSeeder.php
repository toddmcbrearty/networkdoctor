<?php

use App\Device;
use App\DeviceType;
use App\Lendee;
use App\LendeeRelation;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'test@test.com',
            'password' => Hash::make('welcome'),
            'superadmin' => true,
        ]);

        //insert lendee relations
        $relations = [
            ['name' => 'employee'],
            ['name' => 'client'],
            ['name' => 'vendor'],
            ['name' => 'other']
        ];
        LendeeRelation::insert($relations);

        //insert device types
        $deviceTypes = [
            ['name' => 'Phone', 'updated_at' => new Datetime, 'created_at' => new Datetime],
            ['name' => 'Tablet', 'updated_at' => new Datetime, 'created_at' => new Datetime],
            ['name' => 'Computer', 'updated_at' => new Datetime, 'created_at' => new Datetime],
            ['name' => 'Wifi', 'updated_at' => new Datetime, 'created_at' => new Datetime],
            ['name' => 'Other', 'updated_at' => new Datetime, 'created_at' => new Datetime],
        ];
        DeviceType::insert($deviceTypes);

        factory(Device::class, 300)->create();

        //create lendees and assign devices to them.
        factory(Lendee::class, 50)->create()->each(function($lendee) {
            $device = Device::whereNull('lendee_id')->inRandomOrder()->first();
            $device->lendee_id = $lendee->id;
            $device->save();
        });
    }
}
