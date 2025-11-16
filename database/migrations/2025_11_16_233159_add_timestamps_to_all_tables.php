<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'social_links',
            'education',
            'experience',
            'experience_keywords',
            'experience_traits_global',
            'achievements',
            'tech_categories',
            'technologies',
            'technology_options',
            'user_technologies'
        ];

        foreach ($tables as $table) {
            // Check if table exists
            $tableExists = DB::select("SELECT to_regclass('public.{$table}')");
            
            if (!empty($tableExists) && $tableExists[0]->to_regclass !== null) {
                // Check if created_at exists
                $hasCreatedAt = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}' AND column_name = 'created_at'");
                
                // Check if updated_at exists
                $hasUpdatedAt = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}' AND column_name = 'updated_at'");
                
                if (empty($hasCreatedAt)) {
                    DB::statement("ALTER TABLE {$table} ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
                }
                
                if (empty($hasUpdatedAt)) {
                    DB::statement("ALTER TABLE {$table} ADD COLUMN updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
                }
                
                // Update existing records
                DB::statement("UPDATE {$table} SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL");
                DB::statement("UPDATE {$table} SET updated_at = CURRENT_TIMESTAMP WHERE updated_at IS NULL");
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'social_links',
            'education',
            'experience',
            'experience_keywords',
            'experience_traits_global',
            'achievements',
            'tech_categories',
            'technologies',
            'technology_options',
            'user_technologies'
        ];

        foreach ($tables as $table) {
            DB::statement("ALTER TABLE {$table} DROP COLUMN IF EXISTS created_at, DROP COLUMN IF EXISTS updated_at");
        }
    }
};