<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formations', function (Blueprint $table) {
            // إضافة عمود created_by كمفتاح خارجي يشير إلى جدول المستخدمين (users)
            // يمكن أن يكون قابلاً للقيم الفارغة (nullable) ويتم تعيينه إلى NULL إذا تم حذف المستخدم المرتبط
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('duree');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formations', function (Blueprint $table) {
            // إزالة المفتاح الخارجي أولاً قبل حذف العمود
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn('created_by');
        });
    }
};