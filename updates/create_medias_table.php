<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->nullable()->default('0')->comment('分组id');
            $table->string('title')->nullable()->default('')->comment('文件标题');
            $table->string('file_name')->comment('文件名');
            $table->string('path')->comment('路径');
            $table->string('type')->comment('类型');
            $table->integer('size')->comment('文件大小');
            $table->string('meta')->comment("属性");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medias');
    }
}
