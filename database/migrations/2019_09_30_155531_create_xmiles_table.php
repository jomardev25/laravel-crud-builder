<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateXmilesTable.
 */
class CreateXmilesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('mysql')->create('xmiles', function(Blueprint $table) {
			$table->engine    = 'InnoDB';
			$table->charset	  = 'utf8';
			$table->collation = 'utf8_general_ci';
			$table->char('account_num')->nullable();
			$table->char('account_name')->nullable();
			$table->char('name')->nullable();
			$table->bigInteger('balance')->nullable();
			$table->primary(['account_num']);
			$table->unique(['name']);
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('xmiles');
	}
}
