<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 20);
            $table->string('provider_id');
            $table->string('email')->nullable();
            $table->string('avatar', 2048)->nullable();
            $table->timestamps();
            $table->unique(['provider', 'provider_id']);
        });

        foreach (['google' => 'google_id', 'facebook' => 'facebook_id'] as $provider => $column) {
            DB::table('users')->whereNotNull($column)->orderBy('id')->get(['id', $column, 'email'])
                ->each(fn (object $user) => DB::table('social_accounts')->insert([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_id' => $user->{$column},
                    'email' => $user->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['google_id', 'facebook_id']);
            // Zalo does not share an email address.
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
        });

        Schema::dropIfExists('social_accounts');
    }
};
