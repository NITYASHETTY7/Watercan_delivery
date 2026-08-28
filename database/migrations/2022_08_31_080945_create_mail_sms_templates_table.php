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
            'mail_sms_templates',
            function (Blueprint $table) {
                $table->id();
                $table->text('subject');
                $table->text('purpose')->nullable();
                $table->text('code');
                $table->integer('type');
                $table->longText('content')->nullable();
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
        Schema::dropIfExists('mail_sms_templates');
    }
};
