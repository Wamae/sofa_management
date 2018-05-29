<?php

use App\Account;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for($i = 0; $i <= 5;$i++){
            $account = new Account();
            $account->account = "SOFA:".$i;
            $account->company_name = $faker->company;
            $account->company_phone = $faker->phoneNumber;
            $account->company_address = $faker->address;
            $account->save();
        }
    }
}
