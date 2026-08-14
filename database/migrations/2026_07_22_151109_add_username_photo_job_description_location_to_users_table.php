<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->string('photo')->nullable()->after('username');
            $table->string('job_description')->nullable()->after('email_verified_at');
            $table->string('location')->nullable()->after('job_description');
        });

        // Fill usernames for existing users using their email or name
        User::whereNull('username')->each(function (User $user) {
            $baseUsername = strtolower(explode('@', $user->email)[0]);
            $username = $baseUsername;
            $counter = 1;

            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $user->username = $username;
            $user->save();
        });

        // Now add unique index
        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'photo', 'job_description', 'location']);
        });
    }
};
