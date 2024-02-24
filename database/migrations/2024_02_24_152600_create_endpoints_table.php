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
        \Illuminate\Support\Facades\DB::select("CREATE TABLE `endpoints` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `credenciales_id` INT(11) NULL DEFAULT NULL,
            `method` VARCHAR(10) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
            `url` VARCHAR(250) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`id`) USING BTREE
        )
        COLLATE='utf8mb4_general_ci'
        ENGINE=InnoDB
        AUTO_INCREMENT=0
        ;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
