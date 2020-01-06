<?php
//BeginLib
//EndLib
function module_valute_selector($razdel, $section = null)
{
   $__module_subpage = array();
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "Цены-валюта";
global $VALUTE_SELECTOR;
// Инициализируем язык
  $lang = se_getlang();//$sitearray['language'];
  $pricemoney = se_baseCurrency();
// Получаем из СЕССИИ тип валюты
if (isRequest('pricemoney')) {
    $_SESSION['pricemoney'] = getRequest('pricemoney');
}
if (!empty($_SESSION['pricemoney']))
{
    $pricemoney = $_SESSION['pricemoney'];
}
// ##################################
// #### Формируем селектор валют
// ####
$VALUTE_SELECTOR = '';
$money = new seTable('money_title');
$money->where("lang = '?'", $lang);
$moneylist = $money->getList();
if (!empty($moneylist))
foreach($moneylist as $rowmoney) 
{
    $sel = ($rowmoney['name'] == $pricemoney) ? 'selected' : '';
    $VALUTE_SELECTOR .=
        '<option value="'.$rowmoney['name'].'" '.$sel.'>&nbsp;'.$rowmoney['title'].'</option>';
}
unset($money);
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
//EndRazdel
}
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"e_ValuteSelect\" [part.style]>
<noempty:part.title><h3 class=\"contentTitle\"[part.style_title]><div id=\"bgcontentImg\"></div> <span id=\"title\">[part.title]</span> </h3> </noempty>
<noempty:part.image><img border=\"0\" class=\"contentImage\"[part.style_image] src=\"[part.image]\" alt=\"[part.image_alt]\"></noempty>
<noempty:part.text><div class=\"contentText\"[part.style_text]>[part.text]</div> </noempty>
    <div class=\"contentBody\">
        <form \"margin:0px;\" action=\"\" method=\"post\" enctype=\"multipart/form-data\">
            <div class=\"txtValuteSelect\">{$section->params[0]->value}&nbsp;</div> 
            <div class=\"divValuteSelect\">
                <select class=\"ValuteSelect\" name=\"pricemoney\" onChange=\"submit();\">
                    $VALUTE_SELECTOR
                </select> 
            </div> 
        </form> 
  </div> 
  </div> 
<!-- =============== END CONTENT ============= -->";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};