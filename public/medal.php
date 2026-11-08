<?php
require "../include/bittorrent.php";
dbconn();
loggedinorreturn();
$where = 'display_on_medal_page = 1';
$q = htmlspecialchars($_REQUEST['q'] ?? '');
if (!empty($q)) {
    $where .= ' and name like ' . sqlesc("%{$q}%");
}
$total = intval(get_row_count('medals', 'WHERE ' . $where));
$perPage = 20;
list($paginationTop, $paginationBottom, $limit, $offset) = pager($perPage, $total, "?");
$rows = [];
foreach (\Nexus\Database\NexusDB::select("select * from medals where $where order by priority desc, id desc $limit") as $medalRow) {
    $rows[] = new \App\Support\RowObject($medalRow);
}
$q = htmlspecialchars($q);
$title = nexus_trans('medal.label');
$columnNameLabel = nexus_trans('label.name');
$columnImageLargeLabel = nexus_trans('medal.fields.image_large');
$columnPriceLabel = nexus_trans('medal.fields.price');
$columnDurationLabel = nexus_trans('medal.fields.duration');
$columnDescriptionLabel = nexus_trans('medal.fields.description');
$columnBuyLabel = nexus_trans('medal.buy_btn');
$columnSaleBeginEndTimeLabel = nexus_trans('medal.fields.sale_begin_end_time');
$columnInventoryLabel = nexus_trans('medal.fields.inventory');
$columnBonusAdditionLabel = nexus_trans('medal.fields.bonus_addition');
$columnGiftLabel = nexus_trans('medal.gift_btn');
$columnGiftFeeLabel = nexus_trans('medal.fields.gift_fee');
$header = '<h1 style="text-align: center">'.$title.'</h1>';
$filterForm = <<<FORM
<div>
    <form id="filterForm" action="{$_SERVER['REQUEST_URI']}" method="get">
        <input id="q" type="text" name="q" value="{$q}" placeholder="username">
        <input type="submit">
        <input type="reset" onclick="document.getElementById('q').value='';document.getElementById('filterForm').submit();">
    </form>
</div>
FORM;
stdhead($title);
begin_main_frame();
$table = <<<TABLE
<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
<tr>
<td class="colhead">ID</td>
<td class="colhead">$columnImageLargeLabel</td>
<td class="colhead">$columnDescriptionLabel</td>
<td class="colhead" style="width: 115px">$columnSaleBeginEndTimeLabel</td>
<td class="colhead">$columnDurationLabel</td>
<td class="colhead">$columnBonusAdditionLabel</td>
<td class="colhead">$columnPriceLabel</td>
<td class="colhead">$columnInventoryLabel</td>
<td class="colhead">$columnBuyLabel</td>
<td class="colhead">$columnGiftLabel</td>
</tr>
</thead>
TABLE;
$now = now();
$table .= '<tbody>';
$userMedalRows = \Nexus\Database\NexusDB::select(
    'select user_medals.* from user_medals where user_medals.uid = ' . sqlesc($CURUSER['id'])
    . ' and (user_medals.expire_at is null or user_medals.expire_at > ' . sqlesc(date('Y-m-d H:i:s')) . ')'
);
$userMedals = [];
foreach ($userMedalRows as $userMedalRow) {
    $userMedals[intval($userMedalRow['medal_id'])] = new \App\Support\RowObject($userMedalRow);
}
foreach ($rows as $row) {
    $buyDisabled = $giftDisabled = ' disabled';
    $buyClass = $giftClass = '';
    try {
        $row->checkCanBeBuy();
        if (isset($userMedals[intval($row->id)])) {
            $buyBtnText = nexus_trans('medal.buy_already');
        } elseif ($CURUSER['seedbonus'] < $row->price) {
            $buyBtnText = nexus_trans('medal.require_more_bonus');
        } else {
            $buyBtnText = nexus_trans('medal.buy_btn');
            $buyDisabled = '';
            $buyClass = 'buy';
        }
        if ($CURUSER['seedbonus'] < $row->price * (1 + ($row->gift_fee_factor ?? 0))) {
            $giftBtnText = nexus_trans('medal.require_more_bonus');
        } else {
            $giftBtnText = nexus_trans('medal.gift_btn');
            $giftDisabled = '';
            $giftClass = 'gift';
        }
    } catch (\Exception $exception) {
        $buyBtnText = $giftBtnText = $exception->getMessage();
    }
    $buyAction = sprintf(
        '<input type="button" class="%s" data-id="%s" value="%s"%s>',
        $buyClass, $row->id, $buyBtnText, $buyDisabled
    );
    $giftAction = sprintf(
        '<input type="number" class="uid" %s style="width: 60px" placeholder="UID"><input type="button" class="%s" data-id="%s" value="%s"%s><span class="nowrap">%s: %s</span>',
         $giftDisabled, $giftClass, $row->id, $giftBtnText, $giftDisabled, $columnGiftFeeLabel, (($row->gift_fee_factor ?? 0) * 100).'%'
    );
    $table .= sprintf(
        '<tr><td>%s</td><td><img src="%s" style="max-width: 60px;max-height: 60px;" class="preview" /></td><td><h1>%s</h1>%s</td><td>%s ~<br>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
        $row->id,$row->image_large, $row->name, $row->description, $row->sale_begin_time ?? nexus_trans('nexus.no_limit'), $row->sale_end_time ?? nexus_trans('nexus.no_limit'), $row->durationText, (($row->bonus_addition_factor ?? 0) * 100).'%', number_format($row->price),  $row->inventory ?? nexus_trans('label.infinite'), $buyAction, $giftAction
    );
}
$table .= '</tbody></table>';
echo $header . $table . $paginationBottom;
end_main_frame();
$confirmBuyMsg = nexus_trans('medal.confirm_to_buy');
$confirmGiftMsg = nexus_trans('medal.confirm_to_gift');
$js = <<<JS
jQuery('.buy').on('click', function (e) {
    let medalId = jQuery(this).attr('data-id')
    layer.confirm("{$confirmBuyMsg}", function (index) {
        let params = {
            action: "buyMedal",
            params: {medal_id: medalId}
        }
        console.log(params)
        jQuery.post('ajax.php', params, function(response) {
            console.log(response)
            if (response.ret != 0) {
                layer.alert(response.msg)
                return
            }
            window.location.reload()
        }, 'json')
    })
})
jQuery('.gift').on('click', function (e) {
    let medalId = jQuery(this).attr('data-id')
    let uid = jQuery(this).prev().val()
    if (!uid) {
        layer.alert('Require UID')
        return
    }
    layer.confirm("{$confirmGiftMsg}" + uid + " ?", function (index) {
        let params = {
            action: "giftMedal",
            params: {medal_id: medalId, uid: uid}
        }
        console.log(params)
        jQuery.post('ajax.php', params, function(response) {
            console.log(response)
            if (response.ret != 0) {
                layer.alert(response.msg)
                return
            }
            window.location.reload()
        }, 'json')
    })
})
JS;
\Nexus\Nexus::js($js, 'footer', false);
stdfoot();

