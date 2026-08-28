<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'website_pages',
            function (Blueprint $table) {
                $table->id();
                $table->text('title');
                $table->string('slug');
                $table->longText('content');
                $table->string('status');
                $table->longText('meta');
                $table->integer('is_permanent')->default(1);
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
        Schema::dropIfExists('website_pages');
    }
};
