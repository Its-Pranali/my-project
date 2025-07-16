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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('mobile_no')->unique();
            $table->string('email',255)->unique();
            $table->string('user_name',255);
            $table->string('role_name',255);
            $table->string('branch_name',255);
            $table->string('department_name',255);
            $table->string('subdepartment_name',255);
            $table->string('taluka_name',255);
            $table->string('status',255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
