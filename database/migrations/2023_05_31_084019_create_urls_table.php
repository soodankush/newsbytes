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
        Schema::create('urls', function (Blueprint $table) {
            $table->id();
            $table->string('hashed_url');
            $table->text('long_url');
            $table->unsignedInteger('click_counts');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('single_use')->default(0)->comment('1: Url is single use, 0: Url is not single use');
            $table->boolean('ownership_type')->default(0)->comment('1: Private Url, 0: Public Url');
            $table->boolean('active')->default(1)->comment('1: Hashed URL can be used, 0: Hashed URL cannot be used');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('urls');
    }
};
