<?php
//BeginLib
//EndLib
function module_vizitka($razdel, $section = null)
{
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "Телефон";
if (empty($section->params[1]->value)) $section->params[1]->value = "E-mail";
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
//EndRazdel
}
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"vizitka\" [part.style]>
<noempty:part.title><h3 class=\"contentTitle\" [part.style_title]>[part.title]</h3> </noempty>
<noempty:part.image><img border=\"0\" class=\"contentImage\" [part.style_image] src=\"[part.image]\" alt=\"[part.image_alt]\"></noempty>
<noempty:part.text></noempty>
<style> .bgbody {background-image:url(./modules/vizitka/vizitka.jpg);}</style> 
[records]
</div> 
<!-- =============== END CONTENT ============= -->";
$__module_content['object'] = "
 <table class=\"bgbody\" border=\"0\" style=\"width:450px;height:306px;\">
<tr> <td valign=\"top\" align=\"left\" style=\"padding-top: 40px;\">
<table style=\"width:400px;\" cellSpacing=\"0\" cellPadding=\"0\" border=\"0\" >
 <tbody> 
 <tr> 
 <td vAlign=\"top\" align=\"left\" style=\"width:26px;\">&nbsp;</td> 
 <td vAlign=\"top\" Align=\"center\" style=\"width:146px; heigth=\"160px;\"\">
<div style=\"width:120px; heght:120px; overflow:hidden;\">
<noempty:record.image><img class=\"vizitkaImage\" src=\"[record.image_prev]\" border=\"0\" alt=\"[record.image_alt]\"></noempty>
</div> 
 &nbsp;</td> 
 <td vAlign=\"top\" align=\"left\" style=\"width:15px;\">&nbsp;</td> 
 <td vAlign=\"top\" align=\"left\" style=\"width:200px\">
 <p style=\"padding-top: 70px;\"><font face=\"Arial\" size=\"1\">[record.text3]</font> <br /><br />
<table style=\"width:100%\" cellSpacing=\"0\" cellPadding=\"0\" border=\"0\">
<tr> <td> 
<strong> <font class=\"fio\" face=\"Arial\" size=\"4\">
 [record.title]
 </font> </strong> 
</td> </tr> 
<tr style=\"height:20px;\"><td> &nbsp;</td> </tr> 
</table> 
</p> </td> </tr> 
 <tr> 
 <td vAlign=\"top\" align=\"left\">&nbsp;</td> 
 <td vAlign=\"top\" align=\"left\"><font face=\"Arial\" size=\"1\">
 {$section->params[0]->value}:&nbsp;&nbsp;[record.text1]</font> <br />
<font face=\"Arial\" size=\"1\">{$section->params[1]->value}:&nbsp;&nbsp;<a href=\"mailto:[record.text2]\">[record.text2]</font> </a> 
 <br /><font face=\"Arial\" size=\"1\">&nbsp;</font> 
  <td vAlign=\"top\" align=\"left\">&nbsp;</td> 
 <td vAlign=\"top\" align=\"left\" style=\"width:200px\"><font face=\"Arial\" size=\"1\">[record.note]
 </font> </td> </tr> </tbody> 
</table> 
    </td> </tr> </table> 
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};