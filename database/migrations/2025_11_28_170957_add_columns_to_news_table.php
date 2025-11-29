<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->json('summarize_response_json')->nullable();
            $table->string('rewritten_title')->nullable();
            $table->text('rewritten_description')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('summarize_response_json');
            $table->dropColumn('rewritten_title');
            $table->dropColumn('rewritten_description');
        });
    }
};
