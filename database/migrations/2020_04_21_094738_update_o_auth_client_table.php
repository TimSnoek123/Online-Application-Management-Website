<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOAuthClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('o_auth_clients', function (Blueprint $table) {
            $table->string('name')->index();
            $table->string('clientSecret');
            $table->string('responseMode');
            $table->string('responseType');
            $table->string('grantType');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('o_auth_clients', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('clientSecret');
            $table->dropColumn('responseMode');
            $table->dropColumn('responseType');
            $table->dropColumn('grantType');
        });
    }
}
