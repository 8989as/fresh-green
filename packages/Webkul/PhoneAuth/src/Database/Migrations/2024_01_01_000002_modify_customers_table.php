<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'phone_verified')) {
                $table->boolean('phone_verified')->default(false)->after('phone');
            }
            if (!Schema::hasColumn('customers', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('phone_verified');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'phone_verified')) {
                $table->dropColumn('phone_verified');
            }
            if (Schema::hasColumn('customers', 'phone_verified_at')) {
                $table->dropColumn('phone_verified_at');
            }
        });
    }
};
