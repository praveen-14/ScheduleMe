<?php
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: Praveen
 * Date: 4/2/2017
 * Time: 1:45 AM
 */
class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();
        User::create(array(
            'name'     => 'Praveen',
            'email'    => 'praveensseen@gmail.com',
            'password' => Hash::make('123'),
        ));
    }
}