<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'phone_verified')) {
                $table->boolean('phone_verified')->default(false);
            }
            
            if (!Schema::hasColumn('customers', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['phone_verified', 'phone_verified_at']);
        });
    }
};
