<?php

use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class);
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('sku');
            $table->float('price');
            $table->string('weight');
            $table->string('quantity');
            $table->json('dimensions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
