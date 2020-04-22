<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOAuthClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_auth_clients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('authUrl');
            $table->string('tokenUrl');
            $table->string('clientId');
            $table->json('scopes');
            $table->string('redirectUri'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('o_auth_client');
    }
}
