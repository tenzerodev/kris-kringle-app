<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            // 'CLAIM' = User picks button. 'RANDOM' = User clicks "Spin" and system picks.
            $table->enum('mode', ['CLAIM', 'RANDOM'])->default('CLAIM')->after('status');
            $table->text('notes')->nullable()->after('name'); // Instructions
            $table->date('exchange_date')->nullable()->after('notes');
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['mode', 'notes', 'exchange_date']);
        });
    }
};
