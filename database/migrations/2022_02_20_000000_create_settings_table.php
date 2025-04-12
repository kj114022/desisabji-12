<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;

class CreateSettingsTable extends Migration
{
	public function __construct()
	{
			$this->tablename = Config::get('settings.table');
			$this->keyColumn = Config::get('settings.keyColumn');
			$this->valueColumn = Config::get('settings.valueColumn');		
	}

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('app_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('key')->index();
			$table->text('value');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop($this->tablename);
	}
}
