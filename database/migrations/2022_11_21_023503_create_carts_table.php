<?php
/**
 * Class CartsTable
 *
 * @category ZStarter
 *
 * @ref     zCURD
 * @author  Book My Water <info@watercane.come>
 * @license https://watercane-dev.dze-labs.in Book My Water
 * @version <zStarter: 1.1.0>
 * @link    https://watercane-dev.dze-labs.in
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
 
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->comment('user_id');
            $table->text('session_id')->comment('always have session value');
            $table->integer('qty')->nullable()->comment('qty');
            $table->text('type')->comment('type');
            $table->text('cart_type')->nullable()->comment('This is cart type');
            $table->bigInteger('type_id')->comment('type_id');
            $table->double('price')->comment('price');
            $table->double('total')->nullable()->comment('price');
            $table->float('exclusive_price', 8, 2)->comment('tax not included');
            $table->float('exclusive_subtotal', 8, 2)->comment('tax not included');
            $table->json('details')->nullable()->comment('discount_data,instructions,weight,gift_text');
            $table->float('discount', 8, 2)->comment('discount');
            $table->float('tax', 8, 2)->default(0)->comment('calculate order tax info');
            $table->float('shipping', 8, 2)->default(0)->comment('calculated shipping according to product weight');
            $table->text('remark')->nullable()->comment('remark');
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
        Schema::dropIfExists('carts');
    }
}
