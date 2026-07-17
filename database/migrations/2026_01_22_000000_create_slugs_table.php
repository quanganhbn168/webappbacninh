<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('slugs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // The actual slug string
            $table->nullableMorphs('reference'); // reference_id, reference_type
            $table->timestamps();
            
            // Index is already created by nullableMorphs
            // $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('slugs');
    }
};
