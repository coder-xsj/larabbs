<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function (Blueprint $table){
            // 当user_id 对应的users表数据被删除时，删除话题
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // 当category_id 对应的replies表数据被删除时，删除词条数据
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
        // 当user_id 对应的replies表数据被删除时，删除词条数据
        Schema::table('replies', function (Blueprint $table){
            // 这条是评论---用户
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // 这条是评论---帖子
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');


        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //移除外键约束
//        Schema::table('topics', function (Blueprint $table){
//           $table->dropForeign(['user_id']);
//        });
//
//        Schema::table('replies', function (Blueprint $table){
//           $table->dropForeign(['user_id']);
//           $table->dropForeign(['topics_id']);
//        });


    }
}
