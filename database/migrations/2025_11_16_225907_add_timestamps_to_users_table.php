<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if columns exist before adding
        $hasCreatedAt = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'users' AND column_name = 'created_at'");
        $hasUpdatedAt = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'users' AND column_name = 'updated_at'");
        
        if (empty($hasCreatedAt)) {
            DB::statement('ALTER TABLE users ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
        }
        
        if (empty($hasUpdatedAt)) {
            DB::statement('ALTER TABLE users ADD COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
        }
        
        // Update existing records to have timestamps
        DB::statement('UPDATE users SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL');
        DB::statement('UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE updated_at IS NULL');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};