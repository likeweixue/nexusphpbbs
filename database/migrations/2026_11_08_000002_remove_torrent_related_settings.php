<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * 论坛版：删除种子/字幕/候选相关的权限与魔力值设置项，文案改为论坛内容
     */
    public function up(): void
    {
        $existing = DB::table('settings')->pluck('name')->all();
        $touch = [];

        // 论坛版默认文案：魔力值说明可编辑
        if (!in_array('main.bonus_karma_note', $existing, true)) {
            $touch['main.bonus_karma_note'] = "欢迎来到魔力值系统。\n\n发帖、回帖等论坛行为都可以获得魔力值，具体奖励规则以站点公告为准。\n\n魔力值可以在本页兑换邀请、勋章、补签卡、改名卡、贵宾等。";
        }

        foreach ($touch as $name => $value) {
            DB::table('settings')->insert([
                'name' => $name,
                'value' => $value,
                'autoload' => 'yes',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 删除种子/字幕/候选相关的权限设置
        $authorityKeys = [
            'authority.torrentmanage', 'authority.torrent-delete', 'authority.torrentsticky',
            'authority.torrentonpromotion', 'authority.torrent_hr', 'authority.torrent-set-special-tag',
            'authority.torrent-approval', 'authority.torrent-approval-allow-automatic', 'authority.torrent-set-price',
            'authority.askreseed', 'authority.viewnfo', 'authority.torrentstructure',
            'authority.torrenthistory', 'authority.seebanned', 'authority.againstoffer',
            'authority.addoffer', 'authority.offermanage', 'authority.movetorrent',
            'authority.view_special_torrent', 'authority.upload', 'authority.uploadspecial',
            'authority.uploadsub', 'authority.delownsub', 'authority.submanage',
            'authority.updateextinfo', 'authority.commanage', 'authority.categories', 'authority.fields',
        ];
        DB::table('settings')->whereIn('name', $authorityKeys)->delete();

        // 删除做种魔力值相关设置
        $bonusKeys = [
            'bonus.perseeding', 'bonus.maxseeding', 'bonus.tzero', 'bonus.nzero', 'bonus.bzero', 'bonus.l',
            'bonus.zero_bonus_factor', 'bonus.min_size', 'bonus.donortimes', 'bonus.harem_addition',
            'bonus.official_addition', 'bonus.official_tag', 'bonus.zero_bonus_tag',
            'bonus.onegbupload', 'bonus.fivegbupload', 'bonus.tengbupload', 'bonus.hundredgbupload',
            'bonus.tengbdownload', 'bonus.hundredgbdownload', 'bonus.ratiolimit', 'bonus.dlamountlimit',
            'bonus.uploadtorrent', 'bonus.uploadsubtitle',
        ];
        DB::table('settings')->whereIn('name', $bonusKeys)->delete();
        DB::table('settings')->whereIn('name', ['main.seebanned', 'main.againstoffer'])->delete();
    }

    public function down(): void
    {
        // 不恢复
    }
};
