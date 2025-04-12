<?php
/**
 * File name: 2019_08_29_213837_create_products_table.php
 * Last modified: 2020.05.03 at 10:56:45

 *
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 15);
            $table->string('name', 127);
            $table->string('meta_keyword', 200)->nullable()->default('');
            $table->string('product_slug', 200)->nullable()->default('');
            $table->double('price', 8, 2)->default(0);
            $table->double('discount_price', 8, 2)->nullable()->default(0);
            $table->text('description')->nullable();
            $table->text('long_description')->nullable();
            $table->double('capacity', 9, 2)->nullable()->default(0);
            $table->double('package_items_count', 9, 2)->nullable()->default(0); // added
            $table->string('unit', 127)->nullable()->default(''); // added
            $table->boolean('featured')->nullable()->default(0);
            $table->boolean('deliverable')->nullable()->default(1); // added
            $table->integer('market_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('rank')->unsigned()->default(0);
            $table->string('product_icon')->nullable()->default('');     
            $table->string('product_image')->nullable()->default('');    
            $table->boolean('status')->nullable()->default(1);        
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
        Schema::drop('products');
    }
}
