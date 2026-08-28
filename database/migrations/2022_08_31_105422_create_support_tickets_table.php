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
            'support_tickets',
            function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->text('message');
                $table->text('priority')->nullable();
                $table->integer('status')->default(0);
                $table->string('subject');
                $table->text('reply')->nullable();
                $table->string('ticket_type_id')->nullable();
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
        Schema::dropIfExists('support_tickets');
    }
};
