<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('addresses')) {
            return;
        }

        // Clean up prior attempts (generated column) if present
        if (Schema::hasColumn('addresses', 'default_user_id')) {
            DB::statement('DROP INDEX `addresses_default_user_unique` ON `addresses`');
            DB::statement('ALTER TABLE `addresses` DROP COLUMN `default_user_id`');
        }

        // Enforce single default without updating the table inside the trigger: raise error if a second default would be set
        DB::unprepared(<<<SQL
        DROP TRIGGER IF EXISTS `addresses_before_insert_check_default`;
        CREATE TRIGGER `addresses_before_insert_check_default`
        BEFORE INSERT ON `addresses`
        FOR EACH ROW
        BEGIN
            IF NEW.is_default = 1 THEN
                IF (SELECT COUNT(*) FROM `addresses` WHERE `user_id` = NEW.user_id AND `is_default` = 1) > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'User already has a default address';
                END IF;
            END IF;
        END;
        SQL);

        DB::unprepared(<<<SQL
        DROP TRIGGER IF EXISTS `addresses_before_update_check_default`;
        CREATE TRIGGER `addresses_before_update_check_default`
        BEFORE UPDATE ON `addresses`
        FOR EACH ROW
        BEGIN
            IF NEW.is_default = 1 THEN
                IF (SELECT COUNT(*) FROM `addresses` WHERE `user_id` = NEW.user_id AND `is_default` = 1 AND `id` <> OLD.id) > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'User already has a default address';
                END IF;
            END IF;
        END;
        SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `addresses_before_insert_check_default`;');
        DB::unprepared('DROP TRIGGER IF EXISTS `addresses_before_update_check_default`;');
    }
};
