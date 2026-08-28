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
        Schema::create(
            'website_enquiries',
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('status')->default(0);
                $table->string('type');
                $table->string('email')->nullable();
                $table->string('country_code')->nullable();
                $table->string('phone')->nullable();
                $table->string('subject');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_enquiries');
    }
};
