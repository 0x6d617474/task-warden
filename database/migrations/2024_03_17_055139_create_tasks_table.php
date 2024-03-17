<?php

declare(strict_types=1);

use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', static function (Blueprint $table): void {
            $table->uuid('id');
            $table->string('title');
            $table->timestamp('closed')->nullable();
            $table->timestamp(Task::CREATED_AT)->nullable();
            $table->timestamp(Task::UPDATED_AT)->nullable();
            $table->timestamp(Task::DELETED_AT)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
