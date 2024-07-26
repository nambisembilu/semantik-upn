<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('questionnaire_id');
            $table->unsignedBigInteger('questionnaire_section_id')->nullable();
            $table->unsignedBigInteger('variable_id')->nullable();
            $table->unsignedBigInteger('indicator_id')->nullable();
            $table->string('code')->nullable();
            $table->text('title');
            $table->string('answer_type');
            $table->json('answer_content')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
