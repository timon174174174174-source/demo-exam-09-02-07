<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');                            // название помещения
            $table->string('type');                            // Аудитория | Коворкинг | Кинозал
            $table->text('description')->nullable();           // описание
            $table->unsignedInteger('capacity')->default(0);   // вместимость, чел.
            $table->string('image')->nullable();               // иллюстрация
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
