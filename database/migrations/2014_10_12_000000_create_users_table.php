<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->index()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('is_active')->default('1');
            $table->tinyInteger('is_delete')->default('0');
            $table->string('group_role');
            $table->string('last_login_at')->nullable();
            $table->string('last_login_ip',40)->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
