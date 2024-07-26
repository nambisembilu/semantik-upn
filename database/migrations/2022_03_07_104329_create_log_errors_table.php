<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_errors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('message');
            $table->integer('line');
            $table->longText('params')->nullable();
            $table->longText('stack_trace')->nullable();
            $table->longText('file');
            $table->longText('url')->nullable();
            $table->string('ip_source')->nullable();
            $table->string('client_code')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('error_code')->nullable();
            $table->string('http_code')->nullable();
            $table->date('date')->default(date('Y-m-d'));
            $table->timestamp('time')->useCurrent();
            $table->integer('log_status')->default(0)->comment("0=new,1=Process,2=Done,99=Unsolved");
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_errors');
    }
};
