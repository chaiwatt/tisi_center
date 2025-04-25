<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcertifyStdTypeAssignRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_std_type_assign_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bc_std_assign_id')->nullable()->comment('TB : bcertify_standard_type_assign .ID');
            $table->integer('roles')->nullable()->comment('TB : roles .ID');
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
        Schema::dropIfExists('bcertify_std_type_assign_roles');
    }
}
