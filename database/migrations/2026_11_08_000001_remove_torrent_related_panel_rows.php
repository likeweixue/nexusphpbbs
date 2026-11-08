<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 论坛版：移除种子分类、自定义字段等种子相关面板入口
        DB::table('adminpanel')->whereIn('url', [
            'catmanage.php',
            'fields.php',
        ])->delete();
    }

    public function down(): void
    {
        // 不恢复
    }
};
