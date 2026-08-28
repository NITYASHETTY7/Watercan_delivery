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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('branch_id');
            $table->bigInteger('zone_id');
            $table->bigInteger('zone_pincode_id');
            $table->bigInteger('product_id');
            $table->bigInteger('parent_order_id')->nullable();
            $table->integer('type')->default(1)->comment('1: Express, 2: Subscription');      
            $table->bigInteger('assign_to')->nullable();
            $table->bigInteger('qty');
            $table->integer('status')->default(1)->comment('1: Pending, 2: Assigned, 3: In Route, 4: Delivered, 5: Cancelled');   
            $table->integer('payment_status')->default(1)->comment('1: Unpaid, 2: Paid');  
            $table->float('rate');
            $table->float('tax_amount')->nullable();
            $table->integer('tax')->nullable()->comment('in %');   
            $table->float('sub_total');                       
            $table->float('total');      
            $table->date('date')->nullable();      
            $table->date('start_date')->nullable();      
            $table->date('end_date')->nullable();      
            $table->integer('schedule_type')->nullable()->comment('1: Daily, 2: Weekly, 3: Monthly');  
            $table->integer('schedule_value')->nullable();  
            $table->longText('from')->nullable()->comment('Seller Info');
            $table->longText('to')->nullable()->comment('Buyer Info');    
            $table->string('txn_no');
            $table->text('remark');      
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
        Schema::dropIfExists('orders');
    }
};
