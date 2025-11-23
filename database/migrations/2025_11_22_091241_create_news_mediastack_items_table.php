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
        Schema::create('news_mediastack_items', function (Blueprint $table) {
            $table->id();
            $table->timestamp('requested_at')->nullable();
            $table->json('response')->nullable();              // raw API response item
            $table->text('summarize_response')->nullable();   // Hindi summary
            $table->string('local_image_path')->nullable();   // local cached/overlayed image
            $table->string('original_image_url')->nullable();
            $table->string('gemini_api_url')->nullable();     // future image-gen url
            // publishing columns
            $table->timestamp('published_at_whatsapp')->nullable();
            $table->string('published_url_whatsapp')->nullable();
            $table->timestamp('published_at_facebook')->nullable();
            $table->string('published_url_facebook')->nullable();
            $table->timestamp('published_at_linkedin')->nullable();
            $table->string('published_url_linkedin')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('processed_at')->nullable();    // summarised + image overlaid
            $table->integer('batch_no')->default(1);          // batch identifier
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
