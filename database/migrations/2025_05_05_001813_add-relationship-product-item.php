<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop old 'product' column
            $table->dropColumn('product');

            // Add foreign key to 'products' table
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the foreign key column
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            // Restore the old 'product' string column
            $table->string('product');
        });
    }
};
