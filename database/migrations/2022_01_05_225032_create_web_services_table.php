<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebServicesTable extends Migration
{
    public function up(): void
    {
        Schema::create('web_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->json('token');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_services');
    }
}
