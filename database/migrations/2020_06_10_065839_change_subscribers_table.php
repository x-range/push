<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->unsignedBigInteger('message_id')->nullable()->after('auth');
            $table->timestamp('notification_at')->nullable()->after('message_id');
            $table->timestamp('delivered_at')->nullable()->after('notification_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn('message_id');
            $table->dropColumn('notification_at');
            $table->dropColumn('delivered_at');
        });
    }
}
