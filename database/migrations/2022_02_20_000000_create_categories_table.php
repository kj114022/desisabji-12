<?php
/**
 * File name: 2019_08_29_213822_create_categories_table.php
 * Last modified: 2020.04.30 at 06:25:41

 *
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 127);
            $table->string('service_slug', 250)->nullable();
            $table->string('meta_keyword', 250)->nullable();
            $table->string('service_icon', 250)->nullable();
            $table->string('super_category_id', 10)->nullable();
            $table->integer('rank')->default(0);
			$table->boolean('status')->default(1);
            $table->text('description')->nullable();
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
        Schema::drop('categories');
    }
}
