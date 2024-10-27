<?php

$forumselected = "<option value='all' selected='selected'>&raquo; �� ���� �������</option>";

$rf = se_db_query("
  SELECT id, name
  FROM forum_forums"
);

while ($forum=mysql_fetch_array($rf)) {
  $forumselected.="<option value='".$forum['id']."'>".$forum['name']."</option>";
}

$date=getdate();

$time1=mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
$time7=mktime(0, 0, 0, $date['mon'], $date['mday']-7, $date['year']);
$time30=mktime(0, 0, 0, $date['mon']-1, $date['mday'], $date['year']);
$time60=mktime(0, 0, 0, $date['mon']-2, $date['mday'], $date['year']);
$time90=mktime(0, 0, 0, $date['mon']-3, $date['mday'], $date['year']);
$time180=mktime(0, 0, 0, $date['mon']-6, $date['mday'], $date['year']);
$time365=mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']-1);
$forum_echo.="
<h3 class=forumTitle id=srch_Title>�����</h3>
<form action='' method='get'>
<input type='hidden' name='act' value='rsearch'>
<table class=tableForum id=tableSrch><tbody class=tableBody>
<tr>
	<td class=title id=srch_titleWords>
    	<div id=srch_Words>��������� ����� ��� �����:</div>
    </td>
	<td class=field id=srch_fieldWords>
    	<div id=srch_FPh><input id=srch_inpWords name='text' value=''></div>
    </td>
</tr>
<tr>
	<td class=title id=srch_titleInMess>
    	<div id=srch_InMess>� ���������� ������������:</div>
    </td>
	<td class=field id=srch_fieldInMess>
    	<div id=srch_Mess><input id=srch_inpInMess name='user' value=''></div>
    </td>
</tr>
<tr>
	<td class=title id=srch_titleWhere>
    	<div id=srch_Where>��� ������:</div>
    </td>
	<td class=field id=srch_fieldWhere>
    	<div id=srch_FPlc>
        <select id=srch_slcWhere name='forums[]' size='6' multiple='multiple'>
		$forumselected
		</select>
		</div>
    </td>
</tr>
<tr>
	<td class=title id=srch_titleInterv>
    	<div id=srch_Interv>������ ��������� ��...</div>
    </td>
	<td class=field id=srch_fieldInterv>
    	<div id=srch_SrcTim>
        <select id=srch_slcInterv name='time'>
			<option value='$time1'>����������� ����</option>
			<option value='$time7'>��������� ������</option>
			<option value='$time30'>��������� �����</option>
			<option value='$time60'>��������� ��� ������</option>
			<option value='$time90'>��������� ��� ������</option>
			<option value='$time180'>��������� �������</option>
			<option value='$time365'>��������� ���</option>
			<option value='0' selected='selected'>�� �����</option>
		</select>
        </div>
    </td>
</tr>
<tr>
	<td class=title id=srch_titleResAs>
    	<div id=srch_ResAs>�������� ���������� ������:</div>
    </td>
	<td class=field id=srch_fieldResAs>
    	<div id=srch_Reslt>
        <input id=srch_rdResAs type='radio' name='result_type' value='topics' checked='checked'>
        <label id=srch_lbRS for='result_topics'>��� ����</label><br>
		<input id=srch_rdResAs type='radio' name='result_type' value='messages'>
        <label id=srch_lbRS for='result_msg'>��� ���������</label>
        </div>
	</td>
</tr>
<TR><TD colspan=2>&nbsp;</TD></TR>
<TR><TD colspan=2><input class=buttonForum id=srch_btnStart type='submit' name='doGo' value='������ �����'></TD></TR>
<TR><TD colspan=2>&nbsp;</TD></TR>
</tbody></table>
</form>
";

?>