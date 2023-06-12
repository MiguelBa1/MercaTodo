<?php

use App\Enums\DocumentTypeEnum;
use App\Models\City;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->bigInteger('phone');
            $table->bigInteger('document')->unique();
            $table->enum('document_type', array_column(DocumentTypeEnum::cases(), 'value'))->nullable();
            $table->boolean('status')->default(true);
            $table->string('address', 255);
            $table->foreignIdFor(City::class);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
