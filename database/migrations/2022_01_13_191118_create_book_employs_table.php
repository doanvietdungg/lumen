<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookEmploysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_employs', function (Blueprint $table) {
            $table->id();


            $table->bigInteger('id_book')->unsigned()->nullable()->unique();
            $table->bigInteger('id_employe')->unsigned()->nullable();


            $table->timestamps();
            $table->foreign('id_book')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('id_employe')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_employs');
    }
}
