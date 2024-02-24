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
        \Illuminate\Support\Facades\DB::select("CREATE TABLE `credentials` (
        `id` INT(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
        `http_header` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
        `daily_usage` INT(11) NULL DEFAULT NULL,
        `daily_usage_limit` INT(11) NULL DEFAULT NULL,
        `monthly_usage` INT(11) NULL DEFAULT NULL,
        `monthly_usage_limit` INT(11) NULL DEFAULT NULL,
        `created_at` TIMESTAMP NULL DEFAULT NULL,
        `updated_at` TIMESTAMP NULL DEFAULT NULL,
        PRIMARY KEY (`id`) USING BTREE
    )
    COLLATE='utf8mb4_general_ci'
    ENGINE=InnoDB
    AUTO_INCREMENT=0
    ;
");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
