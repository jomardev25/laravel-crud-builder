<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCustomersTable.
 */
class CreateCustomersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('mysql')->create('customers', function(Blueprint $table) {
			$table->engine    = 'InnoDB';
			$table->charset	  = 'utf8';
			$table->collation = 'utf8_general_ci';
			$table->char('customer_id')->nullable();
			$table->char('account_num')->nullable();
			$table->char('account_name')->nullable();
			$table->char('name')->nullable();
			$table->primary(['customer_id','account_num']);
			$table->index(['account_name','name']);
		});
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customers');
	}
}
