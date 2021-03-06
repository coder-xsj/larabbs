<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成10个用户数据
        $users = factory(User::class)->times(10)->create();


        $user = User::find(1);
        $user->name = 'shengjin_Xu';
        $user->email = '2449382518@qq.com';
        $user->avatar = 'http://larabbs.test/uploads/images/avatars/202012/24/1_1608820940_nLOmSIt01W.jpg';
        $user->save();

        // 初始化用户角色，将 1 号用户指派为『站长』
        $user->assignRole('Founder');

        // 将 2 号用户指派为『管理员』
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
