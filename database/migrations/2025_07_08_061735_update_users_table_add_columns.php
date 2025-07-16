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
        Schema::table('users', function (Blueprint $table) {
            // Add columns only if they don't exist
            if (!Schema::hasColumn('users', 'mobile_no')) {
                $table->string('mobile_no')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('users', 'user_name')) {
                $table->string('user_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'role_name')) {
                $table->string('role_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'branch_name')) {
                $table->string('branch_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'department_name')) {
                $table->string('department_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'subdepartment_name')) {
                $table->string('subdepartment_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'taluka_name')) {
                $table->string('taluka_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->nullable();
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'mobile_no',
                'email',
                'user_name',
                'role_name',
                'branch_name',
                'department_name',
                'subdepartment_name',
                'taluka_name',
                'status',
                'deleted_at'
            ]);
        });
    }
};
