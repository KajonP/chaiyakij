<?php

function formatDateThat($strDate)
{
	return formatDate($strDate);
}


function formatDateThatNoTime($strDate)
{
	return formatDate($strDate, true);
}

function formatDate($strDate, $notime = false)
{
	$strYear = date("Y", strtotime($strDate)) + 543;
	$strMonth = date("n", strtotime($strDate));
	$strDay = date("j", strtotime($strDate));
	$strHour = date("H", strtotime($strDate));
	$strMinute = date("i", strtotime($strDate));
	$strSeconds = date("s", strtotime($strDate));
	$strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
	$strMonthThai = $strMonthCut[$strMonth];
	if ($notime) {
		return "$strDay $strMonthThai $strYear";
	} else {
		return "$strDay $strMonthThai $strYear $strHour:$strMinute:$strSeconds";
	}
}

function formatDateTime($strDate)
{
    $data = date('Y-m-d',strtotime($strDate));
    $time = strtotime($data);
	return $time;
}
