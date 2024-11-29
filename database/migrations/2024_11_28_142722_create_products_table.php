<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Remove the foreign key and 'user_id' column since it's no longer needed
            // $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Removed this line
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->integer('quantity');
            $table->enum('category', [
                'Vegetable Seeds', 
                'Flower Seeds', 
                'Herb Seeds', 
                'Fruit Seeds and Trees'
            ]);
            $table->string('image_path')->nullable(); // Store image path
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
