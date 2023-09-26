<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingToFulfilledJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fulfilled_jobs', function (Blueprint $table) {
            $table->integer('driver_rating')->nullable()->after('fulfilled');
            $table->text('driver_comment')->nullable()->after('driver_rating');
            $table->integer('mechanic_rating')->nullable()->after('driver_comment');
            $table->text('mechanic_comment')->nullable()->after('mechanic_rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fulfilled_jobs', function (Blueprint $table) {
            $table->dropColumn('driver_rating');
            $table->dropColumn('driver_comment');
            $table->dropColumn('mechanic_rating');
            $table->dropColumn('mechanic_comment');
        });
    }
}
