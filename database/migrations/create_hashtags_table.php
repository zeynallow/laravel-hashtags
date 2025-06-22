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
        Schema::create('hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            
            // Add index for better performance
            $table->index('name');
        });

        Schema::create('hashtaggables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hashtag_id');
            $table->morphs('taggable');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('hashtag_id')
                  ->references('id')
                  ->on('hashtags')
                  ->onDelete('cascade');

            // Composite indexes for better performance
            $table->index(['hashtag_id', 'taggable_type', 'taggable_id']);
            $table->index(['taggable_type', 'taggable_id']);
            
            // Prevent duplicate relationships
            $table->unique(['hashtag_id', 'taggable_type', 'taggable_id'], 'hashtaggables_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hashtaggables');
        Schema::dropIfExists('hashtags');
    }
};
