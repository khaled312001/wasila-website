<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists
        if (!Schema::hasTable('contact_message_replies')) {
            return;
        }
        
        // Get the actual column types
        $contactMessagesIdType = null;
        $contactMessageRepliesIdType = null;
        
        try {
            $result = DB::select("SHOW COLUMNS FROM contact_messages WHERE Field = 'id'");
            if (!empty($result)) {
                $contactMessagesIdType = strtoupper($result[0]->Type);
            }
        } catch (\Exception $e) {
            // Ignore
        }
        
        try {
            $result = DB::select("SHOW COLUMNS FROM contact_message_replies WHERE Field = 'contact_message_id'");
            if (!empty($result)) {
                $contactMessageRepliesIdType = strtoupper($result[0]->Type);
            }
        } catch (\Exception $e) {
            // Ignore
        }
        
        // If types don't match, modify the column type
        if ($contactMessagesIdType && $contactMessageRepliesIdType && $contactMessagesIdType !== $contactMessageRepliesIdType) {
            try {
                DB::statement("ALTER TABLE contact_message_replies MODIFY COLUMN contact_message_id {$contactMessagesIdType} NOT NULL");
            } catch (\Exception $e) {
                \Log::warning('Failed to modify contact_message_id column type: ' . $e->getMessage());
            }
        }
        
        // Check if foreign keys already exist
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'contact_message_replies' 
            AND CONSTRAINT_NAME LIKE 'fk_%'
        ");
        
        $hasContactMessageFK = false;
        $hasAdminFK = false;
        
        foreach ($foreignKeys as $fk) {
            if (strpos($fk->CONSTRAINT_NAME, 'contact_message_id') !== false) {
                $hasContactMessageFK = true;
            }
            if (strpos($fk->CONSTRAINT_NAME, 'admin_id') !== false) {
                $hasAdminFK = true;
            }
        }
        
        // Add foreign key for contact_message_id
        if (!$hasContactMessageFK) {
            try {
                DB::statement("
                    ALTER TABLE contact_message_replies 
                    ADD CONSTRAINT fk_contact_message_replies_contact_message_id 
                    FOREIGN KEY (contact_message_id) REFERENCES contact_messages(id) ON DELETE CASCADE
                ");
            } catch (\Exception $e) {
                // Try without constraint name
                try {
                    DB::statement("
                        ALTER TABLE contact_message_replies 
                        ADD FOREIGN KEY (contact_message_id) REFERENCES contact_messages(id) ON DELETE CASCADE
                    ");
                } catch (\Exception $e2) {
                    // Log error but don't fail migration
                    \Log::warning('Failed to add foreign key for contact_message_id: ' . $e2->getMessage());
                }
            }
        }
        
        // Add foreign key for admin_id
        if (!$hasAdminFK) {
            try {
                DB::statement("
                    ALTER TABLE contact_message_replies 
                    ADD CONSTRAINT fk_contact_message_replies_admin_id 
                    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
                ");
            } catch (\Exception $e) {
                // Try without constraint name
                try {
                    DB::statement("
                        ALTER TABLE contact_message_replies 
                        ADD FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
                    ");
                } catch (\Exception $e2) {
                    // Log error but don't fail migration
                    \Log::warning('Failed to add foreign key for admin_id: ' . $e2->getMessage());
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys if they exist
        try {
            DB::statement('ALTER TABLE contact_message_replies DROP FOREIGN KEY fk_contact_message_replies_contact_message_id');
        } catch (\Exception $e) {
            // Ignore if doesn't exist
        }
        
        try {
            DB::statement('ALTER TABLE contact_message_replies DROP FOREIGN KEY fk_contact_message_replies_admin_id');
        } catch (\Exception $e) {
            // Ignore if doesn't exist
        }
    }
};

