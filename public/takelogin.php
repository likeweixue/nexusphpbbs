<?php
require_once("../include/bittorrent.php");
header("Content-Type: text/html; charset=utf-8");
if (!mkglobal("username"))
	die();
dbconn();
require_once(get_langfile_path("", false, get_langfolder_cookie()));
failedloginscheck ();
cur_user_check () ;
$ip = getip();
function bark($text = "")
{
  global $lang_takelogin;
  $text =  ($text == "" ? $lang_takelogin['std_login_fail_note'] : $text);
  stderr($lang_takelogin['std_login_fail'], $text,false);
}
if ($iv == "yes") {
    check_code($_POST['imagehash'] ?? null, $_POST['imagestring'] ?? null, 'login.php', true);
}
//同时支持挑战-响应和传统密码两种登录方式
// 说明：挑战接口 / 前端 JS 不可用时，浏览器仍可直接提交密码登录。
$useChallengeResponse = \App\Models\Setting::getIsUseChallengeResponseAuthentication();
$clientResponse = isset($_POST['response']) && is_string($_POST['response']) ? $_POST['response'] : '';
$passwordInput = isset($_POST['password']) && is_string($_POST['password']) ? $_POST['password'] : '';
$hasResponse = $clientResponse !== '';
$hasPassword = $passwordInput !== '';
if ($useChallengeResponse && !$hasResponse && !$hasPassword) {
    failedlogins("Require response or password parameter.");
}
if (!$useChallengeResponse && !$hasPassword) {
    failedlogins("Require password parameter.");
}

$res = sql_query("SELECT id, passhash, secret, auth_key, enabled, status, two_step_secret, lang FROM users WHERE username = " . sqlesc($username));
$row = mysql_fetch_array($res);

if (!$row)
	failedlogins();
if ($row['status'] == 'pending')
	failedlogins($lang_takelogin['std_user_account_unconfirmed']);
if ($row["enabled"] == "no" && \App\Models\Setting::getSelfEnableBonus() <= 0) {
    bark($lang_takelogin['std_account_disabled']);
}

if (!empty($row['two_step_secret'])) {
    if (empty($_POST['two_step_code'])) {
        failedlogins($lang_takelogin['std_require_two_step_code']);
    }
    $ga = new \PHPGangsta_GoogleAuthenticator();
    if (!$ga->verifyCode($row['two_step_secret'], $_POST['two_step_code'])) {
        failedlogins($lang_takelogin['std_invalid_two_step_code']);
    }
}
$log = "user: {$row['id']}, ip: $ip";
$update = [];
if ($useChallengeResponse && $hasResponse) {
    $challenge = \Nexus\Database\NexusDB::cache_get(get_challenge_key($username));
    if (empty($challenge)) {
        failedlogins("expired");
    }
    $log .= ", useChallengeResponse, client response: $clientResponse";
    $expectedResponse = hash_hmac('sha256', $row['passhash'], $challenge);
    if (!hash_equals($expectedResponse, $clientResponse)) {
        do_log("$log, !hash_equals");
        login_failedlogins();
    }
} else {
    $passwordHash = hash('sha256', $row['secret'] . hash('sha256', $passwordInput));
    $log .= ", password login, fallback: " . ($useChallengeResponse ? 'yes' : 'no');
    if (!hash_equals($row['passhash'], $passwordHash)) {
        //兼容非常老的 md5 密码（没有 auth_key 的账号）
        $legacyHash = md5($row['secret'] . $passwordInput . $row['secret']);
        if (!empty($row['auth_key']) || !hash_equals($row['passhash'], $legacyHash)) {
            do_log("$log, password not match");
            login_failedlogins();
        }
        $log .= ", old md5 password matched, upgrade passhash";
        $update['passhash'] = $row['passhash'] = $passwordHash;
    }
}
\Nexus\Database\NexusDB::cache_del(get_challenge_key($username));
do_log("$log, login successful");
$userRep = new \App\Repositories\UserRepository();
$userRep->saveLoginLog($row['id'], $ip, 'Web', true);

//update user lang
$language = \Nexus\Database\NexusDB::getOne('language', 'site_lang_folder = ' . sqlesc(get_langfolder_cookie()));

if ($language && $language['id'] != $row["lang"]) {
    do_log(sprintf("update user: %s lang: %s => %s", $row["id"], $row["lang"], $language['id']));
    $update["lang"] = $language['id'];
}
if (empty($row['auth_key'])) {
    $row['auth_key'] = $update['auth_key'] = hash('sha256', mksecret(32));
}
if (!empty($update)) {
    \Nexus\Database\NexusDB::update('users', $update, 'id = ' . sqlesc($row["id"]));
    clear_user_cache($row["id"]);
}

if (isset($_POST["logout"]) && $_POST["logout"] == "yes")
{
	logincookie($row["id"], $row['auth_key'],900);
}
else
{
    logincookie($row["id"], $row['auth_key']);
}

if (!empty($_POST["returnto"]))
	nexus_redirect($_POST['returnto']);
else
	nexus_redirect("index.php");
?>
