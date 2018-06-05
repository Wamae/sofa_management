<?php

use App\Account;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

            $account = new User();
            $account->name = "root";
            $account->first_name = $faker->firstNameMale;
            $account->last_name = $faker->lastName;
            $account->email = $faker->email;
            $account->phone = $faker->phoneNumber;
            $account->address = $faker->address;
            $account->password = Hash::make('root123');
            $account->save();
    }
}
