<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // 所属クライアント
            $table->string('title'); // テンプレート名
            $table->foreignId('debit_account_id')->nullable(); // 借方科目
            $table->foreignId('credit_account_id')->nullable(); // 貸方科目
            $table->enum('amount_type', ['固定', '変動'])->default('固定'); // 金額タイプ
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_templates');
    }
};
