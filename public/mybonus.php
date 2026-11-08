<?php
require_once('../include/bittorrent.php');
dbconn();
require_once(get_langfile_path());
//require(get_langfile_path("",true));
loggedinorreturn();
parked();

function bonusarray($option = 0){
	global $onegbupload_bonus,$fivegbupload_bonus,$tengbupload_bonus,$oneinvite_bonus,$customtitle_bonus,$vipstatus_bonus, $basictax_bonus, $taxpercentage_bonus, $bonusnoadpoint_advertisement, $bonusnoadtime_advertisement;
	global $lang_mybonus;

	$results = [];
    //[FORUM EDITION] 上传量 / 下载量兑换（种子流量）已移除

    //Invite
    if ($oneinvite_bonus > 0){
        $bonus = array();
        $bonus['points'] = $oneinvite_bonus;
        $bonus['art'] = 'invite';
        $bonus['menge'] = 1;
        $bonus['name'] = $lang_mybonus['text_buy_invite'];
        $bonus['description'] = $lang_mybonus['text_buy_invite_note'];
        $results[] = $bonus;
    }

    //Tmp Invite
    $tmpInviteBonus = \App\Models\BonusLogs::getBonusForBuyTemporaryInvite();
    if ($tmpInviteBonus > 0) {
        $bonus = array();
        $bonus['points'] = $tmpInviteBonus;
        $bonus['art'] = 'tmp_invite';
        $bonus['menge'] = 1;
        $bonus['name'] = $lang_mybonus['text_buy_tmp_invite'];
        $bonus['description'] = $lang_mybonus['text_buy_tmp_invite_note'];
        $results[] = $bonus;
    }

    //Custom Title
    $bonus = array();
    $bonus['points'] = $customtitle_bonus;
    $bonus['art'] = 'title';
    $bonus['menge'] = 0;
    $bonus['name'] = $lang_mybonus['text_custom_title'];
    $bonus['description'] = $lang_mybonus['text_custom_title_note'];
    $results[] = $bonus;


    //VIP Status
    $bonus = array();
    $bonus['points'] = $vipstatus_bonus;
    $bonus['art'] = 'class';
    $bonus['menge'] = 0;
    $bonus['name'] = $lang_mybonus['text_vip_status'];
    $bonus['description'] = $lang_mybonus['text_vip_status_note'];
    $results[] = $bonus;

    //Bonus Gift
    $bonus = array();
    $bonus['points'] = 100;
    $bonus['art'] = 'gift_1';
    $bonus['menge'] = 0;
    $bonus['name'] = $lang_mybonus['text_bonus_gift'];
    $bonus['description'] = $lang_mybonus['text_bonus_gift_note'];
    if ($basictax_bonus || $taxpercentage_bonus){
        $onehundredaftertax = 100 - $taxpercentage_bonus - $basictax_bonus;
        $bonus['description'] .= "<br /><br />".$lang_mybonus['text_system_charges_receiver']."<b>".($basictax_bonus ? $basictax_bonus.$lang_mybonus['text_tax_bonus_point'].add_s($basictax_bonus).($taxpercentage_bonus ? $lang_mybonus['text_tax_plus'] : "") : "").($taxpercentage_bonus ? $taxpercentage_bonus.$lang_mybonus['text_percent_of_transfered_amount'] : "")."</b>".$lang_mybonus['text_as_tax'].$onehundredaftertax.$lang_mybonus['text_tax_example_note'];
    }
    $results[] = $bonus;


    //No ad for 15 days
    $bonus = array();
    $bonus['points'] = $bonusnoadpoint_advertisement;
    $bonus['art'] = 'noad';
    $bonus['menge'] = $bonusnoadtime_advertisement * 86400;
    $bonus['name'] = $bonusnoadtime_advertisement.$lang_mybonus['text_no_advertisements'];
    $bonus['description'] = $lang_mybonus['text_no_advertisements_note'];
    $results[] = $bonus;

    //Attendance card
    $bonus = array();
    $bonus['points'] = \App\Models\BonusLogs::getBonusForBuyAttendanceCard();
    $bonus['art'] = 'attendance_card';
    $bonus['menge'] = 0;
    $bonus['name'] = $lang_mybonus['text_attendance_card'];
    $bonus['description'] = $lang_mybonus['text_attendance_card_note'];
    $results[] = $bonus;

    //Rainbow ID
    $bonus = array();
    $bonus['points'] = \App\Models\BonusLogs::getBonusForBuyRainbowId();
    $bonus['art'] = 'rainbow_id';
    $bonus['menge'] = 0;
    $bonus['name'] = $lang_mybonus['text_buy_rainbow_id'];
    $bonus['description'] = $lang_mybonus['text_buy_rainbow_id_note'];
    $results[] = $bonus;

    //Change username card
    $bonus = array();
    $bonus['points'] = \App\Models\BonusLogs::getBonusForBuyChangeUsernameCard();
    $bonus['art'] = 'change_username_card';
    $bonus['menge'] = 0;
    $bonus['name'] = $lang_mybonus['text_buy_change_username_card'];
    $bonus['description'] = $lang_mybonus['text_buy_change_username_card_note'];
    $results[] = $bonus;

    //Donate
    $bonus = array();
    $bonus['points'] = 1000;
    $bonus['art'] = 'gift_2';
    $bonus['menge'] = 0;
    $bonus['name'] = $lang_mybonus['text_charity_giving'];
    $bonus['description'] = $lang_mybonus['text_charity_giving_note'];
    $results[] = $bonus;


    //Cancel hit and run
    $bonus = array();
    $bonus['points'] = \App\Models\BonusLogs::getBonusForCancelHitAndRun();
    $bonus['art'] = 'cancel_hr';
    $bonus['menge'] = 0;
    $bonus['name'] = $lang_mybonus['text_cancel_hr_title'];
    $bonus['description'] = '<p>
            <span style="">' . $lang_mybonus['text_cancel_hr_label'] . '</span>
            <input type="number" name="hr_id" />
        </p>';
    $results[] = $bonus;

    //Buy medal
    //migrate to medal.php since v1.8
//    $medals = \App\Models\Medal::query()->where('get_type', \App\Models\Medal::GET_TYPE_EXCHANGE)->get();
//    foreach ($medals as $medal) {
//        $results[] = [
//            'points' => $medal->price,
//            'art' => 'buy_medal',
//            'menge' => 0,
//            'name' => $medal->name,
//            'description' => sprintf(
//                '<div style="display: flex;align-items: center"><div style="padding: 10px">%s</div><div><img src="%s" style="max-height: 120px"/></div></div><input type="hidden" name="medal_id" value="%s">',
//                $medal->description, $medal->image_large, $medal->id
//            ),
//            'medal_id' => $medal->id,
//        ];
//    }

    return $results;

//
//	switch ($option)
//	{
//		case 1: {//1.0 GB Uploaded
//			$bonus['points'] = $onegbupload_bonus;
//			$bonus['art'] = 'traffic';
//			$bonus['menge'] = 1073741824;
//			$bonus['name'] = $lang_mybonus['text_uploaded_one'];
//			$bonus['description'] = $lang_mybonus['text_uploaded_note'];
//			break;
//			}
//		case 2: {//5.0 GB Uploaded
//			$bonus['points'] = $fivegbupload_bonus;
//			$bonus['art'] = 'traffic';
//			$bonus['menge'] = 5368709120;
//			$bonus['name'] = $lang_mybonus['text_uploaded_two'];
//			$bonus['description'] = $lang_mybonus['text_uploaded_note'];
//			break;
//			}
//		case 3: {//10.0 GB Uploaded
//			$bonus['points'] = $tengbupload_bonus;
//			$bonus['art'] = 'traffic';
//			$bonus['menge'] = 10737418240;
//			$bonus['name'] = $lang_mybonus['text_uploaded_three'];
//			$bonus['description'] = $lang_mybonus['text_uploaded_note'];
//			break;
//			}
//		case 4: {//Invite
//			$bonus['points'] = $oneinvite_bonus;
//			$bonus['art'] = 'invite';
//			$bonus['menge'] = 1;
//			$bonus['name'] = $lang_mybonus['text_buy_invite'];
//			$bonus['description'] = $lang_mybonus['text_buy_invite_note'];
//			break;
//			}
//		case 5: {//Custom Title
//			$bonus['points'] = $customtitle_bonus;
//			$bonus['art'] = 'title';
//			$bonus['menge'] = 0;
//			$bonus['name'] = $lang_mybonus['text_custom_title'];
//			$bonus['description'] = $lang_mybonus['text_custom_title_note'];
//			break;
//			}
//		case 6: {//VIP Status
//			$bonus['points'] = $vipstatus_bonus;
//			$bonus['art'] = 'class';
//			$bonus['menge'] = 0;
//			$bonus['name'] = $lang_mybonus['text_vip_status'];
//			$bonus['description'] = $lang_mybonus['text_vip_status_note'];
//			break;
//			}
//		case 7: {//Bonus Gift
//			$bonus['points'] = 25;
//			$bonus['art'] = 'gift_1';
//			$bonus['menge'] = 0;
//			$bonus['name'] = $lang_mybonus['text_bonus_gift'];
//			$bonus['description'] = $lang_mybonus['text_bonus_gift_note'];
//			if ($basictax_bonus || $taxpercentage_bonus){
//				$onehundredaftertax = 100 - $taxpercentage_bonus - $basictax_bonus;
//				$bonus['description'] .= "<br /><br />".$lang_mybonus['text_system_charges_receiver']."<b>".($basictax_bonus ? $basictax_bonus.$lang_mybonus['text_tax_bonus_point'].add_s($basictax_bonus).($taxpercentage_bonus ? $lang_mybonus['text_tax_plus'] : "") : "").($taxpercentage_bonus ? $taxpercentage_bonus.$lang_mybonus['text_percent_of_transfered_amount'] : "")."</b>".$lang_mybonus['text_as_tax'].$onehundredaftertax.$lang_mybonus['text_tax_example_note'];
//				}
//			break;
//			}
//		case 8: {
//			$bonus['points'] = $bonusnoadpoint_advertisement;
//			$bonus['art'] = 'noad';
//			$bonus['menge'] = $bonusnoadtime_advertisement * 86400;
//			$bonus['name'] = $bonusnoadtime_advertisement.$lang_mybonus['text_no_advertisements'];
//			$bonus['description'] = $lang_mybonus['text_no_advertisements_note'];
//			break;
//			}
//		case 9: {
//			$bonus['points'] = 1000;
//			$bonus['art'] = 'gift_2';
//			$bonus['menge'] = 0;
//			$bonus['name'] = $lang_mybonus['text_charity_giving'];
//			$bonus['description'] = $lang_mybonus['text_charity_giving_note'];
//			break;
//			}
//        case 10: {
//            $bonus['points'] = \App\Models\BonusLogs::getBonusForCancelHitAndRun();
//            $bonus['art'] = 'cancel_hr';
//            $bonus['menge'] = 0;
//            $bonus['name'] = $lang_mybonus['text_cancel_hr_title'];
//            $bonus['description'] = '<p>
//            <span style="">' . $lang_mybonus['text_cancel_hr_label'] . '</span>
//            <input type="number" name="hr_id" />
//        </p>';
//            break;
//        }
//		default: break;
//	}
//	return $bonus;
}

$allBonus = bonusarray();
$lockSeconds = 10;
$lockText = sprintf($lang_mybonus['lock_text'], $lockSeconds);
if ($bonus_tweak == "disable" || $bonus_tweak == "disablesave")
	stderr($lang_mybonus['std_sorry'],$lang_mybonus['std_karma_system_disabled'].($bonus_tweak == "disablesave" ? "<b>".$lang_mybonus['std_points_active']."</b>" : ""),false);

$action = htmlspecialchars($_GET['action'] ?? '');
$do = htmlspecialchars($_GET['do'] ?? '');
unset($msg);
if (isset($do)) {
	if ($do == "upload")
	$msg = $lang_mybonus['text_success_upload'];
    elseif ($do == "download")
    $msg = $lang_mybonus['text_success_download'];
	elseif ($do == "invite")
	$msg = $lang_mybonus['text_success_invites'];
    elseif ($do == "tmp_invite")
        $msg = $lang_mybonus['text_success_tmp_invites'];
	elseif ($do == "vip")
	$msg =  $lang_mybonus['text_success_vip']."<b>".get_user_class_name(UC_VIP,false,false,true)."</b>".$lang_mybonus['text_success_vip_two'];
	elseif ($do == "vipfalse")
	$msg =  $lang_mybonus['text_no_permission'];
	elseif ($do == "title")
	$msg = sprintf($lang_mybonus['text_success_custom_title'], $CURUSER['title']);
	elseif ($do == "transfer")
	$msg =  $lang_mybonus['text_success_gift'];
	elseif ($do == "noad")
	$msg =  $lang_mybonus['text_success_no_ad'];
	elseif ($do == "charity")
	$msg =  $lang_mybonus['text_success_charity'];
    elseif ($do == "cancel_hr")
        $msg =  $lang_mybonus['text_success_cancel_hr'];
    elseif ($do == "buy_medal")
        $msg =  $lang_mybonus['text_success_buy_medal'];
    elseif ($do == "attendance_card")
        $msg =  $lang_mybonus['text_success_buy_attendance_card'];
    elseif ($do == "rainbow_id")
        $msg =  $lang_mybonus['text_success_buy_rainbow_id'];
    elseif ($do == "change_username_card")
        $msg =  $lang_mybonus['text_success_buy_change_username_card'];
    elseif ($do == 'duplicated')
        $msg = $lockText;
	else
	$msg = '';
}
	stdhead($CURUSER['username'] . $lang_mybonus['head_karma_page']);

	$bonus = number_format($CURUSER['seedbonus'], 1);
if (!$action) {
	print("<table align=\"center\" width=\"97%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n");
	print("<tr><td class=\"colhead\" colspan=\"4\" align=\"center\"><font class=\"big\">".$SITENAME.$lang_mybonus['text_karma_system']."</font></td></tr>\n");
	if ($msg)
	print("<tr><td align=\"center\" colspan=\"4\"><font class=\"striking\"><b>". $msg ."</b></font></td></tr>");
?>
<tr><td class="text" align="center" colspan="4"><?php echo $lang_mybonus['text_exchange_your_karma']?><?php echo $bonus?><?php echo $lang_mybonus['text_for_goodies'] ?>
<br /><b><?php echo $lang_mybonus['text_no_buttons_note'] ?></b><br /><small style="color: orangered">(<?php echo $lockText ?>)</small></td></tr>
<?php

print("<tr><td class=\"colhead\" align=\"center\">".$lang_mybonus['col_option']."</td>".
"<td class=\"colhead\" align=\"left\">".$lang_mybonus['col_description']."</td>".
"<td class=\"colhead\" align=\"center\">".$lang_mybonus['col_points']."</td>".
"<td class=\"colhead\" align=\"center\">".$lang_mybonus['col_trade']."</td>".
"</tr>");


for ($i=0; $i < count($allBonus); $i++)
{
	$bonusarray = $allBonus[$i];
	if (
	    ($bonusarray['art'] == 'gift_1' && $bonusgift_bonus == 'no')
        || ($bonusarray['art'] == 'noad' && ($enablead_advertisement == 'no' || $bonusnoad_advertisement == 'no'))
        || ($bonusarray['art'] == 'cancel_hr' && !\App\Models\HitAndRun::getIsEnabled())
    ) {
        continue;
    }
    $bonusarrray['points'] = floatval($bonusarray['points']);

	print("<tr>");
	print("<form action=\"?action=exchange\" method=\"post\">");
	print("<td class=\"rowhead_center\"><input type=\"hidden\" name=\"option\" value=\"".$i."\" /><b>".($i + 1)."</b></td>");
	if ($bonusarray['art'] == 'title'){ //for Custom Title!
	    $otheroption_title = "<input type=\"text\" name=\"title\" style=\"width: 200px\" maxlength=\"30\" />";
	    print("<td class=\"rowfollow\" align='left'><h1>".$bonusarray['name']."</h1>".$bonusarray['description']."<br /><br />".$lang_mybonus['text_enter_titile'].$otheroption_title.$lang_mybonus['text_click_exchange']."</td><td class=\"rowfollow\" align='center'>".number_format($bonusarray['points'])."</td>");
	}
	elseif ($bonusarray['art'] == 'gift_1'){  //for Give A Karma Gift
			$otheroption = "<table width=\"100%\"><tr><td class=\"embedded\"><b>".$lang_mybonus['text_username']."</b><input type=\"text\" name=\"username\" style=\"width: 200px\" maxlength=\"24\" /></td><td class=\"embedded\"><b>".$lang_mybonus['text_to_be_given']."</b><input type=\"number\" name=\"bonusgift\" id=\"giftcustom\" style='width: 80px' min='100' />".$lang_mybonus['text_karma_points']."</td></tr><tr><td class=\"embedded\" colspan=\"2\"><b>".$lang_mybonus['text_message']."</b><input type=\"text\" name=\"message\" style=\"width: 400px\" maxlength=\"100\" /></td></tr></table>";
			print("<td class=\"rowfollow\" align='left'><h1>".$bonusarray['name']."</h1>".$bonusarray['description']."<br /><br />".$lang_mybonus['text_enter_receiver_name']."<br />$otheroption</td><td class=\"rowfollow nowrap\" align='center'>".$lang_mybonus['text_min']."100</td>");
	}
	elseif ($bonusarray['art'] == 'gift_2'){  //charity giving
			$otheroption = "<table width=\"100%\"><tr><td class=\"embedded\"><b>".$lang_mybonus['text_to_be_given']."</b><select name=\"bonuscharity\" id=\"charityselect\" > <option value=\"1000\"> 1,000</option><option value=\"2000\"> 2,000</option><option value=\"3000\" selected=\"selected\"> 3000</option> <option value=\"5000\"> 5,000</option> <option value=\"8000\"> 8,000</option> <option value=\"10000\"> 10,000</option><option value=\"20000\"> 20,000</option><option value=\"50000\"> 50,000</option></select>".$lang_mybonus['text_karma_points']."</td></tr></table>";
			print("<td class=\"rowfollow\" align='left'><h1>".$bonusarray['name']."</h1>".$bonusarray['description']."<br /><br />".$lang_mybonus['text_select_receiver_ratio']."<br />$otheroption</td><td class=\"rowfollow nowrap\" align='center'>".$lang_mybonus['text_min']."1,000<br />".$lang_mybonus['text_max']."50,000</td>");
	}
	else {  //for VIP or Upload
		print("<td class=\"rowfollow\" align='left'><h1>".$bonusarray['name']."</h1>".$bonusarray['description']."</td><td class=\"rowfollow\" align='center'>".number_format($bonusarray['points'])."</td>");
	}

	if($CURUSER['seedbonus'] >= $bonusarray['points'])
	{
	    $permission = 'sendinvite';
		if ($bonusarray['art'] == 'gift_1'){
			print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_karma_gift']."\" /></td>");
		}
		elseif ($bonusarray['art'] == 'noad'){
			if ($enablenoad_advertisement == 'yes' && get_user_class() >= $noad_advertisement)
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_class_above_no_ad']."\" disabled=\"disabled\" /></td>");
			elseif (!empty($CURUSER['noaduntil']) && strtotime($CURUSER['noaduntil']) >= TIMENOW)
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_already_disabled']."\" disabled=\"disabled\" /></td>");
			elseif (get_user_class() < $bonusnoad_advertisement)
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".get_user_class_name($bonusnoad_advertisement,false,false,true).$lang_mybonus['text_plus_only']."\" disabled=\"disabled\" /></td>");
			else
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_exchange']."\" /></td>");
		}
		elseif ($bonusarray['art'] == 'gift_2'){
			print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_charity_giving']."\" /></td>");
		}
		elseif($bonusarray['art'] == 'invite')
		{
			if (\App\Models\Setting::get('main.invitesystem') != 'yes')
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".nexus_trans('invite.send_deny_reasons.invite_system_closed')."\" disabled=\"disabled\" /></td>");
			elseif(!user_can($permission, false, 0)){
			$requireClass = get_setting("authority.$permission");
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".nexus_trans('invite.send_deny_reasons.no_permission', ['class' => \App\Models\User::getClassText($requireClass)])."\" disabled=\"disabled\" /></td>");}
			else
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_exchange']."\" /></td>");
		}
		elseif($bonusarray['art'] == 'tmp_invite')
		{
			if (\App\Models\Setting::get('main.invitesystem') != 'yes')
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".nexus_trans('invite.send_deny_reasons.invite_system_closed')."\" disabled=\"disabled\" /></td>");
			elseif(!user_can($permission, false, 0)){
			$requireClass = get_setting("authority.$permission");
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".nexus_trans('invite.send_deny_reasons.no_permission', ['class' => \App\Models\User::getClassText($requireClass)])."\" disabled=\"disabled\" /></td>");}
			else
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_exchange']."\" /></td>");
		}
		elseif ($bonusarray['art'] == 'class')
		{
			if (get_user_class() >= UC_VIP)
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['std_class_above_vip']."\" disabled=\"disabled\" /></td>");
			else
				print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_exchange']."\" /></td>");
		}
		elseif ($bonusarray['art'] == 'title')
			print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_exchange']."\" /></td>");
		elseif ($bonusarray['art'] == 'change_username_card') {
		    if (\App\Models\UserMeta::query()->where('uid', $CURUSER['id'])->where('meta_key', \App\Models\UserMeta::META_KEY_CHANGE_USERNAME)->exists()) {
                print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['text_change_username_card_already_has']."\" disabled=\"disabled\"/></td>");
            } else {
                print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_exchange']."\" /></td>");
            }
        } elseif ($bonusarray['art'] == 'rainbow_id') {
            if (\App\Models\UserMeta::query()->where('uid', $CURUSER['id'])->where('meta_key', \App\Models\UserMeta::META_KEY_PERSONALIZED_USERNAME)->whereNull('deadline')->exists()) {
                print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['text_rainbow_id_already_valid_forever']."\" disabled=\"disabled\"/></td>");
            } else {
                print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_exchange']."\" /></td>");
            }
		} else {
            print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['submit_exchange']."\" /></td>");
        }
	}
	else
	{
		print("<td class=\"rowfollow\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"".$lang_mybonus['text_more_points_needed']."\" disabled=\"disabled\" /></td>");
	}
	print("</form>");
	print("</tr>");

}

print("</table><br />");
?>

<?php
// ===== 魔力值说明（可编辑） =====
$karmaNoteEditable = user_can('staffmem');
$karmaNoteEditMode = $karmaNoteEditable && (isset($_GET['karmanote_edit']) || isset($_GET['karmanote_preview']));
$karmaNoteContent = get_setting('main.bonus_karma_note', '');
$karmaNotePreview = null;
if ($karmaNoteEditMode && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['karmanote_content'])) {
    $karmaNoteContent = (string)$_POST['karmanote_content'];
    if (!empty($_POST['karmanote_save'])) {
        saveSetting('main', ['bonus_karma_note' => $karmaNoteContent]);
        $Cache->delete_value('main_bonus_karma_note');
        nexus_redirect('mybonus.php');
    }
    $karmaNotePreview = format_comment($karmaNoteContent, false);
}

if ($karmaNoteEditMode) {
    print("<table width=\"97%\" cellpadding=\"3\">\n");
    print("<tr><td class=\"colhead\" align=\"center\"><font class=\"big\">".$lang_mybonus['text_what_is_karma']."</font></td></tr>\n");
    print("<tr><td class=\"text\" align=\"center\"><form method=\"post\" action=\"mybonus.php?karmanote_edit=1\">");
    print("<textarea name=\"karmanote_content\" rows=\"18\" style=\"width: 96%; font-family: monospace;\">".htmlspecialchars($karmaNoteContent)."</textarea>");
    print("<div style=\"margin-top: 6px; color: gray;\">支持 BBCode（[b][i][u][url][img][list][*] 等），保存后显示在魔力值页面。</div>");
    print("<table align=\"center\" cellpadding=\"4\"><tr>");
    print("<td class=\"embedded\"><input type=\"submit\" class=\"btn\" name=\"karmanote_save\" value=\"提交\"></td>");
    print("<td class=\"embedded\"><input type=\"submit\" class=\"btn2\" name=\"karmanote_preview\" value=\"预览\">");
    print("<input type=\"button\" class=\"btn2\" value=\"取消\" onclick=\"location.href='mybonus.php';\"></td>");
    print("</tr></table></form></td></tr>");
    if ($karmaNotePreview !== null) {
        print("<tr><td class=\"text\" align=\"left\">".$karmaNotePreview."</td></tr>");
    }
    print("</table><br />");
} else {
    if (!$karmaNoteContent) {
        $karmaNoteContent = $lang_mybonus['text_karma_note_default'] ?? '';
    }
    print("<table width=\"97%\" cellpadding=\"3\">\n");
    print("<tr><td class=\"colhead\" align=\"center\"><font class=\"big\">".$lang_mybonus['text_what_is_karma']."</font></td></tr>\n");
    print("<tr><td class=\"text\" align=\"left\">".format_comment($karmaNoteContent, false)."</td></tr>");
    if ($karmaNoteEditable) {
        print("<tr><td align=\"center\"><a href=\"mybonus.php?karmanote_edit=1\" class=\"btn\">".($lang_mybonus['text_edit_karma_note'] ?? '编辑')."</a></td></tr>");
    }
    print("</table><br />");
}
}

// Bonus exchange
if ($action == "exchange") {
	if (isset($_POST["userid"]) || isset($_POST["points"]) || isset($_POST["bonus"]) || isset($_POST["art"]) || !isset($_POST['option']) || !isset($allBonus[$_POST['option']])){
		write_log("User " . $CURUSER["username"] . "," . $CURUSER["ip"] . " is trying to cheat at bonus system",'mod');
		die($lang_mybonus['text_cheat_alert']);
	}
	$option = intval($_POST["option"] ?? 0);
	$bonusarray = $allBonus[$option];
	$points = $bonusarray['points'];
	$userid = $CURUSER['id'];
	$art = $bonusarray['art'];

//	$bonuscomment = $CURUSER['bonuscomment'];
	$seedbonus=$CURUSER['seedbonus']-$points;

	if($CURUSER['seedbonus'] >= $points) {
        $bonusRep = new \App\Repositories\BonusRepository();
        $lockName = "user:$userid:exchange:bonus";
        $lock = new \Nexus\Database\NexusLock($lockName, $lockSeconds);
        if (!$lock->get()) {
            do_log("[LOCKED], $lockName, $lockText");
            nexus_redirect('mybonus.php?do=duplicated');
        }
		//[FORUM EDITION] 上传量/下载量兑换逻辑已移除
		//=== trade for one month VIP status ***note "SET class = '10'" change "10" to whatever your VIP class number is
		elseif($art == "class") {
			if (get_user_class() >= UC_VIP) {
				stdmsg($lang_mybonus['std_no_permission'],$lang_mybonus['std_class_above_vip'], 0);
				stdfoot();
				die;
			}
			$vip_until = date("Y-m-d H:i:s",(strtotime(date("Y-m-d H:i:s")) + 28*86400));
//			$bonuscomment = date("Y-m-d") . " - " .$points. " Points for 1 month VIP Status.\n " .htmlspecialchars($bonuscomment);
//			sql_query("UPDATE users SET class = '".UC_VIP."', vip_added = 'yes', vip_until = ".sqlesc($vip_until).", seedbonus = seedbonus - $points, bonuscomment=".sqlesc($bonuscomment)." WHERE id = ".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
            $bonusRep->consumeUserBonus($CURUSER['id'], $points, \App\Models\BonusLogs::BUSINESS_TYPE_BUY_VIP, $points. " Points for 1 month VIP Status.", ['class' => UC_VIP, 'vip_added' => 'yes', 'vip_until' => $vip_until]);
			nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=vip");
		}
		//=== trade for invites
		elseif($art == "invite") {
			if(!user_can('buyinvite'))
				die(get_user_class_name($buyinvite_class,false,false,true).$lang_mybonus['text_plus_only']);
			$invites = $CURUSER['invites'];
			$inv = $invites+$bonusarray['menge'];
//			$bonuscomment = date("Y-m-d") . " - " .$points. " Points for invites.\n " .htmlspecialchars($bonuscomment);
//			sql_query("UPDATE users SET invites = ".sqlesc($inv).", seedbonus = seedbonus - $points, bonuscomment=".sqlesc($bonuscomment)." WHERE id = ".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
            $bonusRep->consumeUserBonus($CURUSER['id'], $points, \App\Models\BonusLogs::BUSINESS_TYPE_EXCHANGE_INVITE, $points. " Points for invites.", ['invites' => $inv, ]);
            nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=invite");
		}
        //=== temporary invite
        elseif($art == "tmp_invite") {
            if(!user_can('buyinvite'))
                die(get_user_class_name($buyinvite_class,false,false,true).$lang_mybonus['text_plus_only']);
//            $invites = $CURUSER['invites'];
//            $inv = $invites+$bonusarray['menge'];
//			$bonuscomment = date("Y-m-d") . " - " .$points. " Points for invites.\n " .htmlspecialchars($bonuscomment);
//			sql_query("UPDATE users SET invites = ".sqlesc($inv).", seedbonus = seedbonus - $points, bonuscomment=".sqlesc($bonuscomment)." WHERE id = ".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
            $bonusRep->consumeToBuyTemporaryInvite($CURUSER['id']);
            nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=tmp_invite");
        }
		//=== trade for special title
		/**** the $words array are words that you DO NOT want the user to have... use to filter "bad words" & user class...
		the user class is just for show, but what the hell tongue.gif Add more or edit to your liking.
		*note if they try to use a restricted word, they will recieve the special title "I just wasted my karma" *****/
		elseif($art == "title") {
			//===custom title
			$title = $_POST["title"];
			$words = array("fuck", "shit", "pussy", "cunt", "nigger", "Staff Leader","SysOp", "Administrator","Moderator","Uploader","Retiree","VIP","Nexus Master","Ultimate User","Extreme User","Veteran User","Insane User","Crazy User","Elite User","Power User","User","Peasant","Champion");
			$title = str_replace($words, $lang_mybonus['text_wasted_karma'], $title);
//			$bonuscomment = date("Y-m-d") . " - " .$points. " Points for custom title. Old title is ".htmlspecialchars(trim($CURUSER["title"]))." and new title is $title\n " .htmlspecialchars($bonuscomment);
//			sql_query("UPDATE users SET title = ".sqlesc($title).", seedbonus = seedbonus - $points, bonuscomment = ".sqlesc($bonuscomment)." WHERE id = ".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
            $bonusRep->consumeUserBonus($CURUSER['id'], $points, \App\Models\BonusLogs::BUSINESS_TYPE_CUSTOM_TITLE, $points. " Points for custom title. Old title is ".htmlspecialchars(trim($CURUSER["title"]))." and new title is $title.", ['title' => $title, ]);
			nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=title");
		}
		elseif($art == "noad" && $enablead_advertisement == 'yes' && $enablebonusnoad_advertisement == 'yes') {
			if (($enablenoad_advertisement == 'yes' && get_user_class() >= $noad_advertisement) || strtotime($CURUSER['noaduntil']) >= TIMENOW || get_user_class() < $bonusnoad_advertisement)
				die($lang_mybonus['text_cheat_alert']);
			else{
				$noaduntil = date("Y-m-d H:i:s",(TIMENOW + $bonusarray['menge']));
//				$bonuscomment = date("Y-m-d") . " - " .$points. " Points for ".$bonusnoadtime_advertisement." days without ads.\n " .htmlspecialchars($bonuscomment);
//				sql_query("UPDATE users SET noad='yes', noaduntil='".$noaduntil."', seedbonus = seedbonus - $points, bonuscomment = ".sqlesc($bonuscomment)." WHERE id=".sqlesc($userid));
                $bonusRep->consumeUserBonus($CURUSER['id'], $points, \App\Models\BonusLogs::BUSINESS_TYPE_NO_AD, $points. " Points for ".$bonusnoadtime_advertisement." days without ads.", ['noad' => 'yes', 'noaduntil' => $noaduntil]);
				nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=noad");
			}
		}
		elseif($art == 'gift_2') // charity giving
		{
			$points = intval($_POST["bonuscharity"] ?? 0);
			if ($points < 1000 || $points > 50000){
				stdmsg($lang_mybonus['text_error'], $lang_mybonus['bonus_amount_not_allowed_two'], 0);
				stdfoot();
				die();
			}
			if($CURUSER['seedbonus'] >= $points) {
				$points2= number_format($points,1);
//				$bonuscomment = date("Y-m-d") . " - " .$points2. " Points as charity to users with ratio below ".htmlspecialchars(trim($ratiocharity)).".\n " .htmlspecialchars($bonuscomment);
				$charityReceiverCount = get_row_count("users", "WHERE enabled='yes'");
				if ($charityReceiverCount) {
//					sql_query("UPDATE users SET seedbonus = seedbonus - $points, charity = charity + $points, bonuscomment = ".sqlesc($bonuscomment)." WHERE id = ".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
                    $bonusRep->consumeUserBonus($CURUSER['id'], $points, \App\Models\BonusLogs::BUSINESS_TYPE_GIFT_TO_LOW_SHARE_RATIO, $points. " Points as charity giving.", ['charity' => \Nexus\Database\NexusDB::raw("charity + $points"), ]);
					$charityPerUser = $points/$charityReceiverCount;
					sql_query("UPDATE users SET seedbonus = seedbonus + $charityPerUser WHERE enabled='yes'") or sqlerr(__FILE__, __LINE__);
					nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=charity");
				}
				else
				{
					stdmsg($lang_mybonus['std_sorry'], $lang_mybonus['std_no_users_need_charity']);
					stdfoot();
					die;
				}
			}
		}
		elseif($art == "gift_1" && $bonusgift_bonus == 'yes') {
			//=== trade for giving the gift of karma
			$points = $_POST["bonusgift"];
			$message = $_POST["message"];
			//==gift for peeps with no more options
			$usernamegift = sqlesc(trim($_POST["username"]));
			$res = sql_query("SELECT id, seedbonus FROM users WHERE username=" . $usernamegift);
			$arr = mysql_fetch_assoc($res);
            if (empty($arr)) {
                stdmsg($lang_mybonus['text_error'], $lang_mybonus['text_receiver_not_exists'], 0);
                stdfoot();
                die;
            }
			$useridgift = $arr['id'];
			$userseedbonus = $arr['seedbonus'];
//			$receiverbonuscomment = $arr['bonuscomment'];
			if (!is_numeric($points) || $points < $bonusarray['points']) {
				//write_log("User " . $CURUSER["username"] . "," . $CURUSER["ip"] . " is hacking bonus system",'mod');
				stdmsg($lang_mybonus['text_error'], $lang_mybonus['bonus_amount_not_allowed']);
				stdfoot();
				die();
			}
			if($CURUSER['seedbonus'] >= $points) {
				$points2= number_format($points,1);
//				$bonuscomment = date("Y-m-d") . " - " .$points2. " Points as gift to ".htmlspecialchars(trim($_POST["username"])).".\n " .htmlspecialchars($bonuscomment);

				$aftertaxpoint = $points;
				if ($taxpercentage_bonus)
					$aftertaxpoint -= $aftertaxpoint * $taxpercentage_bonus * 0.01;
				if ($basictax_bonus)
					$aftertaxpoint -= $basictax_bonus;

				$points2receiver = number_format($aftertaxpoint,1);
//				$newreceiverbonuscomment = date("Y-m-d") . " + " .$points2receiver. " Points (after tax) as a gift from ".($CURUSER["username"]).".\n " .htmlspecialchars($receiverbonuscomment);
				if ($userid==$useridgift){
					stdmsg($lang_mybonus['text_huh'], $lang_mybonus['text_karma_self_giving_warning'], 0);
					stdfoot();
					die;
				}

//				sql_query("UPDATE users SET seedbonus = seedbonus - $points, bonuscomment = ".sqlesc($bonuscomment)." WHERE id = ".sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
                $bonusRep->consumeUserBonus($CURUSER['id'], $points, \App\Models\BonusLogs::BUSINESS_TYPE_GIFT_TO_SOMEONE, $points2 . " Points as gift to ".htmlspecialchars(trim($_POST["username"])));
				sql_query("UPDATE users SET seedbonus = seedbonus + $aftertaxpoint WHERE id = ".sqlesc($useridgift));
                \App\Models\BonusLogs::add($useridgift, $userseedbonus, $aftertaxpoint, $userseedbonus + $aftertaxpoint, " + " .$points2receiver. " Points (after tax) as a gift from ".($CURUSER["username"]), \App\Models\BonusLogs::BUSINESS_TYPE_RECEIVE_GIFT);

				//===send message
                $locale = get_user_locale($useridgift);
				$subject = nexus_trans("bonus.msg_someone_loves_you", [], $locale);
				$added = sqlesc(date("Y-m-d H:i:s"));
				$msg = nexus_trans("bonus.msg_you_have_been_given", [], $locale).$points2.nexus_trans("bonus.msg_after_tax", [], $locale).$points2receiver.nexus_trans("bonus.msg_karma_points_by", [], $locale).$CURUSER['username'];
				if ($message)
				{
					$msg .= "\n".nexus_trans("bonus.msg_personal_message_from", [], $locale).$CURUSER['username'].nexus_trans("bonus.msg_colon", [], $locale).$message;
				}
				\App\Models\Message::add([
					'sender' => 0,
					'subject' => $subject,
					'added' => now(),
					'msg' => $msg,
					'receiver' => $useridgift,
				]);
				$usernamegift = unesc($_POST["username"]);
                nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=transfer");
			}
			else{
				print("<table width=\"97%\"><tr><td class=\"colhead\" align=\"left\" colspan=\"2\"><h1>".$lang_mybonus['text_oups']."</h1></td></tr>");
				print("<tr><td align=\"left\"></td><td align=\"left\">".$lang_mybonus['text_not_enough_karma']."<br /><br /></td></tr></table>");
			}
		} elseif ($art == 'cancel_hr') {
		    if (empty($_POST['hr_id'])) {
		        stderr("Error","Invalid H&R ID: " . ($_POST['hr_id'] ?? ''), false, false);
            }
            $bonusRep->consumeToCancelHitAndRun($userid, $_POST['hr_id']);
            nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=cancel_hr");
//        } elseif ($art == 'buy_medal') {
//            if (empty($_POST['medal_id'])) {
//                stderr("Error","Invalid Medal ID: " . ($_POST['medal_id'] ?? ''), false, false);
//            }
//            $bonusRep->consumeToBuyMedal($userid, $_POST['medal_id']);
//            nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=buy_medal");
        } elseif ($art == 'attendance_card') {
            $bonusRep->consumeToBuyAttendanceCard($userid);
            nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=attendance_card");
        } elseif ($art == 'rainbow_id') {
            $bonusRep->consumeToBuyRainbowId($userid);
            nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=rainbow_id");
        } elseif ($art == 'change_username_card') {
            $bonusRep->consumeToBuyChangeUsernameCard($userid);
            nexus_redirect("" . get_protocol_prefix() . "$BASEURL/mybonus.php?do=change_username_card");
        }
	}
}
stdfoot();
?>
