<?php

use App\Account;
use App\OrderStatus;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class OrderStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $userId = 1;
           $orderStatuses =
               array(
                   array(
                       'order_status'=>'Started',
                       'created_by'=>$userId
                   ),array(
                       'order_status'=>'In Progress',
                       'created_by'=>$userId
                   ),array(
                       'order_status'=>'Completed',
                       'created_by'=>$userId
                   ),array(
                       'order_status'=>'Cancelled',
                       'created_by'=>$userId
                   ),
               );

           OrderStatus::insert($orderStatuses);
    }
}
