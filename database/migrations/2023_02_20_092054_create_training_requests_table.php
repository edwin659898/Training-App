<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('training_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->json('options');
            $table->json('trainings')->nullable();
            $training_id = DB::connection()->getQueryGrammar()->wrap('options->training_id');
            $table->unsignedBigInteger('training_id')->storedAs($training_id);
            $table->foreign('training_id')->references('id')->on('trainings');  
            $table->json('department_ids')->nullable();    
            $table->json('evidence')->nullable();
            $table->longText('sub_topics')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->json('trainers')->nullable();
            $trainer_id = DB::connection()->getQueryGrammar()->wrap('trainers->trainer_id');
            $table->unsignedBigInteger('trainer_id')->storedAs($trainer_id);
            $table->foreign('trainer_id')->references('id')->on('trainers');  
            $table->json('specialists')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('venue')->nullable();
            $table->integer('training_rating')->nullable();
            $table->longText('rating_comment')->nullable();
            $table->string('notified_user')->default('no');
            $table->string('assesment_notification_sent')->default('no');
            $table->string('sent_pdf')->default('no');
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
        Schema::dropIfExists('training_requests');
    }
};
