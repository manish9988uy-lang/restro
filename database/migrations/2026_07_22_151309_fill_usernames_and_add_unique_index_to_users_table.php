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
        // Fill usernames for existing users using their email or name
        User::whereNull('username')->orWhere('username', '')->each(function (User $user) {
            $baseUsername = strtolower(explode('@', $user->email)[0]);
            $username = $baseUsername;
            $counter = 1;

            while (User::where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $user->username = $username;
            $user->save();
        });

        // Now add unique index safely if not already present
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username');
            });
        } catch (\Throwable $e) {
            // Index already exists
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['username']);
            });
        } catch (\Throwable $e) {
            // Ignore if index doesn't exist
        }
    }
};
