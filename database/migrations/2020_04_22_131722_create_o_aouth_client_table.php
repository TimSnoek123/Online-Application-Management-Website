<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOAouthClientTable extends Migration
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

            $table->string('clientSecret');
            $table->string('responseMode');
            $table->string('responseType');
            $table->string('grantType');
            $table->unsignedBigInteger('source_company_id')->unique();


        });

        Schema::table('o_auth_clients', function (Blueprint $table) {
            $table->foreign('source_company_id')->references('id')->on('source_companies');
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
