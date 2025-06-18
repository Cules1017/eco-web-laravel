<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('type')->comment('1: featured_products, 2: new_products, 3: category_products');
            $table->string('list_categories')->nullable()->comment('Danh sách ID danh mục, phân cách bằng dấu phẩy');
            $table->integer('num')->default(8)->comment('Số lượng sản phẩm hiển thị');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
}; 