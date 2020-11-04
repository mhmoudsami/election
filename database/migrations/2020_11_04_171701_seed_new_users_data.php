<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SeedNewUsersData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = [
            [
                'email' => 'admin-01@mwatan.com',
                'name' => 'admin-01',
                'password' => 'wta34232241',
            ],
            [
                'email' => 'admin-02@mwatan.com',
                'name' => 'admin-02',
                'password' => 'wta55442336',
            ],
            [
                'email' => 'admin-03@mwatan.com',
                'name' => 'admin-03',
                'password' => 'wta21201261',
            ],
            [
                'email' => 'admin-04@mwatan.com',
                'name' => 'admin-04',
                'password' => 'wta34232241',
            ],
            [
                'email' => 'admin-05@mwatan.com',
                'name' => 'admin-05',
                'password' => 'wta11864787',
            ],
            [
                'email' => 'admin-06@mwatan.com',
                'name' => 'admin-06',
                'password' => 'wta50102269',
            ],
            [
                'email' => 'admin-07@mwatan.com',
                'name' => 'admin-07',
                'password' => 'wta90205043',
            ],
            [
                'email' => 'admin-08@mwatan.com',
                'name' => 'admin-08',
                'password' => 'wta60205247',
            ],
            [
                'email' => 'admin-09@mwatan.com',
                'name' => 'admin-09',
                'password' => 'wta01301022',
            ],
            [
                'email' => 'admin-10@mwatan.com',
                'name' => 'admin-10',
                'password' => 'wta10097822',
            ],
            [
                'email' => 'admin-11@mwatan.com',
                'name' => 'admin-11',
                'password' => 'wta03300121',
            ],
            [
                'email' => 'admin-12@mwatan.com',
                'name' => 'admin-12',
                'password' => 'wta29140028',
            ],
            [
                'email' => 'admin-13@mwatan.com',
                'name' => 'admin-13',
                'password' => 'wta02270175',
            ],
            [
                'email' => 'admin-14@mwatan.com',
                'name' => 'admin-14',
                'password' => 'wta71614009',
            ],
            [
                'email' => 'admin-15@mwatan.com',
                'name' => 'admin-15',
                'password' => 'wta11801067',
            ],
            [
                'email' => 'admin-16@mwatan.com',
                'name' => 'admin-16',
                'password' => 'wta40614017',
            ],
            [
                'email' => 'admin-17@mwatan.com',
                'name' => 'admin-17',
                'password' => 'wta21402243',
            ],
            [
                'email' => 'admin-18@mwatan.com',
                'name' => 'admin-18',
                'password' => 'wta40015421',
            ],
            [
                'email' => 'admin-19@mwatan.com',
                'name' => 'admin-19',
                'password' => 'wta08100102',
            ],
            [
                'email' => 'admin-20@mwatan.com',
                'name' => 'admin-20',
                'password' => 'wta24140255',
            ],
        ];

        foreach ($users as $user) {
            $user = User::create([
                'email' => $user['email'],
                'name' => $user['name'],
                'password' => Hash::make($user['password']),
                'email_verified_at' => now(),
            ])->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
