<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // This migration doesn't create tables, it just registers them with Laravel
        // All tables already exist in the database from the old PHP application
        
        // Add any missing columns that Laravel needs
        
        // Check if users table needs remember_token column
        if (!$this->columnExists('users', 'remember_token')) {
            DB::statement('ALTER TABLE users ADD COLUMN remember_token VARCHAR(100) NULL');
        }
        
        // Check if users table needs created_at and updated_at
        if (!$this->columnExists('users', 'created_at')) {
            DB::statement('ALTER TABLE users ADD COLUMN created_at TIMESTAMP NULL');
            DB::statement('ALTER TABLE users ADD COLUMN updated_at TIMESTAMP NULL');
            DB::statement('UPDATE users SET created_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP WHERE created_at IS NULL');
        }
        
        // Rename password_hash to password for Laravel compatibility
        if ($this->columnExists('users', 'password_hash') && !$this->columnExists('users', 'password')) {
            DB::statement('ALTER TABLE users RENAME COLUMN password_hash TO password');
        }
    }

    public function down(): void
    {
        // Don't drop existing tables on rollback
        // This would delete all your data!
    }
    
    private function columnExists($table, $column): bool
    {
        $result = DB::select(
            "SELECT column_name FROM information_schema.columns 
             WHERE table_name = ? AND column_name = ?",
            [$table, $column]
        );
        
        return count($result) > 0;
    }
};