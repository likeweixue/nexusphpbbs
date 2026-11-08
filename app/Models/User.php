<?php

namespace App\Models;

use App\Exceptions\NexusException;
use App\Support\Locale;
use Nexus\Database\NexusDB;

/**
 * 用户模型（纯 PHP 版，无 Laravel 依赖）。
 *
 * 数据以关联数组保存，支持数组式访问（$user['id']）与属性式访问（$user->id）。
 */
class User implements \ArrayAccess, \JsonSerializable
{
    use UserAttributeAccessTrait;

    public static string $table = 'users';

    public $timestamps = false;

    protected array $attributes = [];

    protected array $original = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $this->original = $attributes;
    }

    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PENDING = 'pending';

    const ENABLED_YES = 'yes';
    const ENABLED_NO = 'no';

    const CLASS_PEASANT = "0";
    const CLASS_USER = "1";
    const CLASS_POWER_USER = "2";
    const CLASS_ELITE_USER = "3";
    const CLASS_CRAZY_USER = "4";
    const CLASS_INSANE_USER = "5";
    const CLASS_VETERAN_USER = "6";
    const CLASS_EXTREME_USER = "7";
    const CLASS_ULTIMATE_USER = "8";
    const CLASS_NEXUS_MASTER = "9";
    const CLASS_VIP = "10";
    const CLASS_RETIREE = "11";
    const CLASS_UPLOADER = "12";
    const CLASS_MODERATOR = "13";
    const CLASS_ADMINISTRATOR = "14";
    const CLASS_SYSOP = "15";
    const CLASS_STAFF_LEADER = "16";

    public static array $classes = [
        self::CLASS_PEASANT => ['text' => 'Peasant'],
        self::CLASS_USER => ['text' => 'User', 'min_seed_points' => 0],
        self::CLASS_POWER_USER => ['text' => 'Power User', 'min_seed_points' => 40000],
        self::CLASS_ELITE_USER => ['text' => 'Elite User', 'min_seed_points' => 80000],
        self::CLASS_CRAZY_USER => ['text' => 'Crazy User', 'min_seed_points' => 150000],
        self::CLASS_INSANE_USER => ['text' => 'Insane User', 'min_seed_points' => 250000],
        self::CLASS_VETERAN_USER => ['text' => 'Veteran User', 'min_seed_points' => 400000],
        self::CLASS_EXTREME_USER => ['text' => 'Extreme User', 'min_seed_points' => 600000],
        self::CLASS_ULTIMATE_USER => ['text' => 'Ultimate User', 'min_seed_points' => 800000],
        self::CLASS_NEXUS_MASTER => ['text' => 'Nexus Master', 'min_seed_points' => 1000000],
        self::CLASS_VIP => ['text' => 'VIP'],
        self::CLASS_RETIREE => ['text' => 'Retiree'],
        self::CLASS_UPLOADER => ['text' => 'Uploader'],
        self::CLASS_MODERATOR => ['text' => 'Moderator'],
        self::CLASS_ADMINISTRATOR => ['text' => 'Administrator'],
        self::CLASS_SYSOP => ['text' => 'Sysop'],
        self::CLASS_STAFF_LEADER => ['text' => 'Staff Leader'],
    ];

    const DONATE_YES = 'yes';
    const DONATE_NO = 'no';

    public static $donateStatus = [
        self::DONATE_YES => ['text' => 'Yes'],
        self::DONATE_NO => ['text' => 'No'],
    ];

    const GENDER_FEMALE = 'Female';
    const GENDER_MALE = 'Male';
    const GENDER_UNKNOWN = 'N/A';

    public static array $genders = [
        self::GENDER_MALE => 'Male',
        self::GENDER_FEMALE => 'Female',
        self::GENDER_UNKNOWN => 'N/A',
    ];

    public static array $cardTitles = [
        'uploaded_human' => '上传量',
        'downloaded_human' => '下载量',
        'share_ratio' => '分享率',
//        'seed_time' => '做种时间',
        'bonus' => '魔力值',
        'seed_points' => '做种积分',
        'invites' => '邀请',
    ];

    public static array $notificationOptions = ['topic_reply', 'hr_reached'];

    private const USER_ENABLE_LATELY = "user_enable_lately:%s";

    public static function getUserEnableLatelyCacheKey(int $userId): string
    {
        return sprintf(self::USER_ENABLE_LATELY, $userId);
    }

    public function getClassTextAttribute(): string
    {
        return self::getClassText($this->class);
    }

    public static function getClassText($class)
    {
        if (!is_numeric($class)|| !isset(self::$classes[$class])) {
            return '';
        }
        $classText = self::$classes[$class]['text'];
        if ($class >= self::CLASS_VIP) {
            $alias = nexus_trans('user.class_names.' . $class);
        } else {
            $alias = Setting::get("account.{$class}_alias");
        }
        if (!empty($alias)) {
            $classText .= "({$alias})";
        }
        return $classText;
    }

    public static function listClass($min = self::CLASS_PEASANT, $max = self::CLASS_STAFF_LEADER): array
    {
        $result = [];
        foreach (self::$classes as $class => $info) {
            if ($class >= $min && $class <= $max) {
                $result[$class] = self::getClassText($class);
            }
        }
        return $result;
    }

    public static function exists($id): bool
    {
        return \Nexus\Database\NexusDB::table(self::$table)->where('id', $id)->exists();
    }

    /**
     * @see ExamRepository::isExamMatchUser()
     *
     * @return string
     */
    public function getDonateStatusAttribute(): string
    {
        if ($this->isDonating()) {
            return self::DONATE_YES;
        }
        return self::DONATE_NO;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'passhash', 'secret', 'stylesheet', 'editsecret', 'added', 'enabled', 'status',
        'leechwarn', 'leechwarnuntil', 'page', 'class', 'uploaded', 'downloaded', 'clientselect', 'showclienterror', 'last_home',
        'seedbonus', 'downloadpos', 'vip_added', 'vip_until', 'title', 'invites', 'attendance_card',
        'seed_points_per_hour', 'passkey', 'auth_key', 'last_login', 'lang', 'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'secret', 'passhash', 'passkey', 'auth_key'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'added' => 'datetime',
        'last_login' => 'datetime',
        'last_access' => 'datetime',
        'last_home' => 'datetime',
        'last_offer' => 'datetime',
        'forum_access' => 'datetime',
        'last_staffmsg' => 'datetime',
        'last_pm' => 'datetime',
        'last_comment' => 'datetime',
        'last_post' => 'datetime',
        'lastwarned' => 'datetime',
        'last_browse' => 'datetime:U',
        'last_music' => 'datetime:U',
        'last_catchup' => 'datetime:U',
        'donoruntil' => 'datetime',
        'warneduntil' => 'datetime',
        'noaduntil' => 'datetime',
        'vip_until' => 'datetime',
        'leechwarnuntil' => 'datetime',
    ];

    public static array $commonFields = [
        'id', 'username', 'email', 'class', 'status', 'added', 'avatar', 'passkey',
        'uploaded', 'downloaded', 'seedbonus', 'seedtime', 'leechtime',
        'invited_by', 'enabled', 'seed_points', 'last_access', 'invites',
        'lang', 'attendance_card', 'privacy', 'noad', 'downloadpos', 'donoruntil', 'donor',
        'downloadpos', 'vip_added', 'vip_until', 'title', 'invites', 'attendance_card',
        'seed_points_per_hour'
    ];

    public static function getDefaultUserAttributes(): array
    {
        return [
            'id' => 0,
            'username' => nexus_trans('user.deleted_username'),
            'class' => self::CLASS_PEASANT,
            'email' => '',
            'status' => self::STATUS_CONFIRMED,
            'added' => '1970-01-01 08:00:00',
            'avatar' => '',
            'uploaded' => 0,
            'downloaded' => 0,
            'seedbonus' => 0,
            'seedtime' => 0,
            'leechtime' => 0,
            'enabled' => self::ENABLED_NO,
            'seed_points' => 0
        ];
    }

    public static function defaultUser(): static
    {
        return new static(self::getDefaultUserAttributes());
    }

    public static function getClassName($class, $compact = false, $b_colored = false, $I18N = false)
    {
        $class_name = self::$classes[$class]['text'] ?? '';
        if ($class >= self::CLASS_VIP && $I18N) {
            $class_name = nexus_trans("user.class_names.$class");
        }
        $class_name_color = self::$classes[$class]['text'] ?? '';
        if ($compact) {
            $class_name = str_replace(" ", "",$class_name);
        }
        if ($class_name && $b_colored) {
            return "<b class='" . str_replace(" ", "",$class_name_color) . "_Name'>" . $class_name . "</b>";
        }
        return $class_name;
    }

    public function checkIsNormal(array $fields = ['status', 'enabled']): bool
    {
        $params = [
            'user_id' => $this->id,
            'username' => $this->username,
        ];
        if (in_array('status', $fields) && $this->getAttribute('status') != self::STATUS_CONFIRMED) {
            throw new NexusException(nexus_trans("user.user_is_not_confirmed", $params));
        }
        if (in_array('enabled', $fields) && $this->getAttribute('enabled') != self::ENABLED_YES) {
            throw new NexusException(nexus_trans("user.user_is_disabled", $params));
        }
        return true;
    }

    public function getLocaleAttribute()
    {
        $locale = null;
        $log = "user: " . $this->id;
        if (get_user_id() == $this->id) {
            $locale = Locale::getLocaleFromCookie();
            $log .= ", locale from cookie: $locale";
        }
        if (!$locale) {
            $lang = $this['lang'] ?? null;
            $locale = Locale::$languageMaps[$lang] ?? $lang;
            $log .= ", [NO_DATA_FROM_COOKIE], lang from database: $lang, locale: $locale";
        }
        do_log($log);
        return $locale;
    }

    public function getSiteLangFolderAttribute()
    {
        $result = $this['lang'] ?? null;
        return ($result && in_array($result, ['en', 'chs', 'cht'])) ? $result : 'en';
    }

    public function getUploadedTextAttribute()
    {
        return mksize($this['uploaded'] ?? 0);
    }

    public function getDownloadedTextAttribute()
    {
        return mksize($this['downloaded'] ?? 0);
    }

    public function getGenderTextAttribute()
    {
        return nexus_trans('user.genders.' . ($this['gender'] ?? ''));
    }

    protected function getTwoFactorAuthenticationStatusAttribute(): string
    {
        return $this->two_step_secret != "" ? "yes" : "no";
    }

    public static function getAccessAdminClassMin(): int
    {
        return intval(\App\Models\Setting::get('authority.staffmem', self::CLASS_MODERATOR));
    }

    public static function getMinSeedPoints($class)
    {
        $setting = Setting::get("account.{$class}_min_seed_points");
        if (is_numeric($setting)) {
            return $setting;
        }
        return self::$classes[$class]['min_seed_points'] ?? false;
    }

    public function isDonating(): bool
    {
        if (($this['donor'] ?? '') !== self::DONATE_YES) {
            return false;
        }
        $until = $this['donoruntil'] ?? null;
        if (empty($until) || $until === '0000-00-00 00:00:00') {
            return true;
        }
        return strtotime($until) >= time();
    }

    public function getIsDonatingAttribute(): bool
    {
        return $this->isDonating();
    }
}
