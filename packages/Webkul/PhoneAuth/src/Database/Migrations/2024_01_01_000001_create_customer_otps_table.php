<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('customer_otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id')->nullable();
            $table->string('phone');
            $table->string('otp');
            $table->timestamp('expires_at');
            $table->boolean('used')->default(false);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_otps');
    }
};
