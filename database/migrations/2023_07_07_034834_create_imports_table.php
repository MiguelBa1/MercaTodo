<?php

use App\Enums\ImportStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', array_column(ImportStatusEnum::cases(), 'value'))->default(ImportStatusEnum::PENDING->value);
            $table->json('errors')->nullable();
            $table->integer('total_rows')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
