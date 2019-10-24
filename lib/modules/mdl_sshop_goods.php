<?php
//BeginLib
// �����������������
// se_DivPages(�����_�������, �������_��_��������)
if (!function_exists('se_DivPages')) {
function se_DivPages($cnrowfull, $cnrowpage, $helptitle="������� ����� �������� � ������� enter ��� ��������") {
    $r = "";
    $cnpage = ceil($cnrowfull/$cnrowpage);
    if ($cnpage > 1) {
        //$squery = $_SERVER['QUERY_STRING'];
        if (empty($L_VARS['get'])) {
            // ������ ��� ����������, ���������� � $GET ��� $remove
            $link = array();
            $remove = array('page', 'sheet');
            foreach($_GET as $k => $v) if (!in_array($k, $remove)) $link[$k] = $k.'='.$v;
            $link['sheet'] = 'sheet=';
            $L_VARS['get'] = join('&', $link);
        }
        // -------------------------------
        if (!empty($_GET['sheet']))
            $sheet = htmlspecialchars($_GET['sheet'], ENT_QUOTES);
        else $sheet = 1;
        $r .= '<center><table border="0">';
        $r .= '<form style="margin:0px"
        onSubmit="if ((this.elements[0].value)>'.$cnpage.' || this.elements[0].value < 1) {
                       alert(\'�������� � ����� ������� �� ����������\'); return false; }
                   location.href=\'?'.$L_VARS['get'].'\'+(this.elements[0].value);
                   return false;" method="get" enctype="multipart/form-data">';
        //$r .= '<tr><td colspan="9" align="center">�������: <b>'.$cnrowfull.'</b>; �������: <b>'.$cnpage.'</b></td></tr>';
        $r .= "<tr>";
        $r_left = "";
        $r_right = "";
        $cnpw = 11;
        $in = 1; $ik = $cnpage;
        if ($cnpage > $cnpw) {
            $in = $sheet-floor($cnpw/2); $ik = $sheet+floor($cnpw/2);
            if ($in <= 1) { $in = 1; $ik = $sheet+($cnpw-$sheet); }
            if ($ik > $cnpage) { $in = $sheet-(($cnpw-1)-($cnpage-$sheet)); $ik = $cnpage; }
            if ($in > 1) {
                $in = $in + 3;
                $r_left .= '<td width="20px" align="center" class="pagen"><a href="?'.$L_VARS['get'].'1">1</a></td>
                            <td width="20px" align="center" class="pagen"><a href="?'.$L_VARS['get'].'2">2</a></td>';
               $r_left .= '<td width="20px" align="center" class="pagen">...</td>';
            }
            if ($ik < $cnpage) {
                $ik = $ik - 3;
                $r_right = '<td width="20px" align="center" class="pagen">...</td>';
                $r_right .= '<td width="20px" align="center" class="pagen"><a href="?'.$L_VARS['get'].''.($cnpage - 1).'">'.($cnpage - 1).'</a></td>';
                $r_right .= '<td width="20px" align="center" class="pagen"><a href="?'.$L_VARS['get'].''.$cnpage.'">'.$cnpage.'</a></td>';
            }
        }
        $r .= $r_left;
        for ($i = $in; $i <= $ik; $i++) {
            if ($i == $sheet)
                $r .= '<td width="20px" align="center">
                       <input class="pagenactive" title="'.$helptitle.'"
                        name="sheet" type="text" size="2" maxlength="'.strlen($cnpage).'" value="'.$i.'" OnKeyPress="EnsureNumeric()"></td>';
            else
                $r .= '<td width="20px" align="center" class="pagen"><a href=\'?'.$L_VARS['get'].''.$i.'\'>'.$i.'</a></td>';
        }
        $r .= $r_right;
        $r .= "</tr>";
        $r .= "</form></table></center>";
    }
    return $r;
}}
// -------------------------------------------------------------- //
// ������ ������ ����� (������ ����������� ��������, ����� ����� �����)
// se_FormatNumber(�����)
if (!function_exists('se_FormatNumber')) {
function se_FormatNumber($num) {
    $rnum = strstr(str_replace(",",".",$num), '.');
    if (trim($rnum)=='') $rnum = ".00";
    if (strlen(trim($rnum))<3) $rnum .= "0";
    $num = strval(floor($num));
    $res = "";
    $l = strlen($num)-1;
    $c = 0;
    for ($i = $l; $i >= 0; $i--) {
        $c++;
        $res = $num[$i].$res;
        if ($c>=3) {
            $res = " ".$res;
            $c = 0;
        }
    }
    return str_replace(" ", "&nbsp;", trim($res.$rnum));
}}
// -------------------------------------------------------------- //
// ������ ������ �������� ������ (���������� ������ ������ ����� ������� se_FormatNumber)
// se_FormatMoney(�����, ������)
if (!function_exists('se_formatMoney')) {
function se_formatMoney($price, $curr) {
// -------- oi?iao auaiaa ouo?
    $num = round($price,2);
    $rnum = strstr(str_replace(",",".",$price), '.');
    if (trim($rnum)=='') $rnum = ".00";
    if (strlen(trim($rnum))<3) $rnum .= "0";
    $num = strval(floor($num));
    $res = "";
    $l = strlen($num)-1;
    $c = 0;
    for ($i = $l; $i >= 0; $i--) {
        $c++;
        $res = $num[$i].$res;
        if ($c>=3) {
            $res = " ".$res;
            $c = 0;
        }
    }
    $price = str_replace(" ", "&nbsp;", trim($res.$rnum));
// -------- oi?iao auaiaa oai
    //$price = se_formatNumber($price);
    $res_setcurr = mysql_fetch_array(mysql_query("SELECT `name_front`, `name_flang`  FROM `money_title` WHERE (`kod` = '".$curr."')OR(`name` = '".$curr."') LIMIT 1"),MYSQL_BOTH);
    if (!empty($res_setcurr['name_front']))
        return $res_setcurr['name_front'].'&nbsp;'.trim($price);
    elseif  (!empty($res_setcurr['name_flang']))
        return trim($price).'&nbsp;'.$res_setcurr['name_flang'];
    else
        return trim($price);
}}
// -------------------------------------------------------------- //
// ��������������� ����� �������� ������
// se_MoneyConvert(�����, ������_��_�����, ������_��_������, ����_��_�������_��������������_������(�� ����� ����������� ����))
if (!function_exists('se_MoneyConvert')) {
function se_MoneyConvert($summa, $setvalut, $getvalut, $date_rep='') {
// ��������������� ���
    if (empty($date_rep)) $date_rep = date("Y-m-d");
    $res_setvalut = mysql_fetch_array(mysql_query("SELECT `kod` FROM `money_title` WHERE (`kod` = '".$setvalut."')OR(`name` = '".$setvalut."') LIMIT 1"),MYSQL_BOTH);
    $res_getvalut = mysql_fetch_array(mysql_query("SELECT `kod` FROM `money_title` WHERE (`kod` = '".$getvalut."')OR(`name` = '".$getvalut."') LIMIT 1"),MYSQL_BOTH);
    $res = mysql_fetch_array(mysql_query("
SELECT SQL_BIG_RESULT
  m.kurs,
  money_title.name,
  money_title.title,
  money_title.cbr_kod,
  money_title.kod,
  money_title.name_front,
  money_title.name_flang,
  concat_ws(' ',money_title.name_front,'1',money_title.name_flang,'=',money_title1.name_front, round(m.kurs/money1.kurs,2),
  money_title1.name_flang) as s,
  m.date_replace as d,
  m.kurs/money1.kurs as k,
  round(m.kurs/money1.kurs,2) as rk
FROM money_title LEFT OUTER JOIN (select * from money
WHERE (money.date_replace <= '$date_rep')
order by money.date_replace DESC)m ON (money_title.kod = m.kod),
(select * from money
WHERE (money.date_replace <= '$date_rep')
order by money.date_replace DESC) money1
  RIGHT OUTER JOIN money_title money_title1 ON (money1.kod = money_title1.kod)
WHERE  (money_title1.kod = '".$res_setvalut[0]."')AND(money_title.kod='".$res_getvalut[0]."')
GROUP BY  money_title.kod"),MYSQL_BOTH);
    $sum = 0;
    if (!empty($res['rk']) && ($res['rk'] > 0))
    $sum = $summa/$res['rk'];
    return $sum;
}}
if (!function_exists('Comment_CheckMail')) {
function Comment_CheckMail($name)
{
    if (preg_match("/[0-9a-z_\-]+@([0-9a-z_\-^\.]+\.[a-z]{2,4})$/i", $name, $matches))
    {
        if (function_exists('getmxrr'))
          if (getmxrr($matches[1], $arr))
              return true;
          else
              return false;
        else
           return true;
    }
    return false;
}
}
if (!function_exists('limit_string')) {
function limit_string($string, $len) {
    if(strlen($string) <= $len) return $string;
    $words = str_word_count($string, 2);
    $pos = 0;
    foreach($words as $indx => $str)
        if($indx < $len) $pos = $indx;
    if ($pos == 0) $string = substr($string, 0, $len);
    else $string = substr($string, 0, $pos);
    return rtrim($string, '!@#$^&*(;".,/?- ')."...";
}
}
if (!function_exists('ShowCatalog_array_search_key')) {
function ShowCatalog_array_search_key($val, $arr) {
    $res = array();
    if (is_array($arr))
        foreach ($arr as $key => $value) if ($value == $val) $res[] = $key;
    return $res;
}
}
if (!function_exists('shop_catalog_group_cn')) {
function shop_catalog_group_cn($ParentID, $arr_cat, $countgr) {
    $arr_k = ShowCatalog_array_search_key($arr_cat['id'][$ParentID], $arr_cat['upid']);
    if (!empty($arr_k))
        foreach ($arr_k as $k => $val)
            $countgr += shop_catalog_group_cn($val, $arr_cat, $arr_cat['cn'][$val]);
    return $countgr;
}
}
if (!function_exists('shop_catalog_goods_cn')){
// #### ������� ������������ ����� ������� � ������ � �� ���� �������� ��������� ��������
// ���������:
//     $node_id - ����� id ������ ������;
//     $arrGroups - ��������� �� ������, � ������� ������������
//                  ���������� �������� id �������� ��������.
function shop_catalog_goods_cn($node_id, &$arrGroups) {
  $goodsCount = 0; // �������������� ����� �������
  $row_group = se_db_fields_item('shop_group', "id='$node_id'", "typegroup, scount");
  if ($row_group['typegroup'] == 's')
    $goodsCount += $row_group['scount']; // ���������� ����� ������� � ����� ������ $node_id
  // �������� ���� ��������, ��������������� ������������� ������ ������
  $ssql = "SELECT `id`, `typegroup`, `scount` FROM `shop_group` WHERE `upid`='".$node_id."'";
  $sql_res = se_db_query($ssql);
  if (se_db_num_rows($sql_res) > 0)
    // �������� � ������ ��������� ���������� ����
    while ($row_group = se_db_fetch_array($sql_res)) {
      if (!in_array($row_group['id'], $arrGroups)) { // ���� id ������ ��� � �������,
        $arrGroups[] = $row_group['id'];          // �� �������� �� ����
        $goodsCount += shop_catalog_goods_cn($row_group['id'], $arrGroups); // � ���������
                                                                  // � ����������� �������
                                                                  // � ����������.
      } // end if
    } // end while
  return $goodsCount;
}
}
if (!function_exists('validate_count_shop')) {
function validate_count_shop($node_id, &$arrGroups) {
  $goodsCount = 0;
  // ������������ ���������� ������� � ������ � ����������� ��� ���� scount
  $ssql = "UPDATE `shop_group`".
          " SET `scount`=(SELECT COUNT(1)".
    "    FROM `shop_price`".
    "    WHERE id_group='".$node_id."')".
          " WHERE `id`='".$node_id."';";
  $sql_res = se_db_query($ssql);
  // ���� ����� ������� ������ ����, �� ��� ������ ������ "s"
  $row_group = se_db_fields_item('shop_group', "id='$node_id'", 'scount');
  if (intval($row_group['scount']) > 0) {
    $ssql = "UPDATE `shop_group`".
            " SET `typegroup`='s'".
            " WHERE `id`='".$node_id."';";
    $sql_res = se_db_query($ssql);
    $goodsCount += intval($row_group['scount']);
  }
  // �������� ���� ��������, ��������������� ������������� ������ ������
  $ssql = "SELECT `id` FROM `shop_group` WHERE `upid`='".$node_id."'";
  $sql_res = se_db_query($ssql);
  if (se_db_num_rows($sql_res) > 0)
    // �������� � ������ ��������� ���������� ����
    while ($row_group = se_db_fetch_array($sql_res)) {
      if (!in_array($row_group['id'], $arrGroups)) { // ���� id ������ ��� � �������,
        $arrGroups[] = $row_group['id'];          // �� �������� �� ����
        $goodsCount += validate_count_shop($row_group['id'], $arrGroups); // � ���������
                                                                  // � ����������� �������
                                                                  // � ����������.
      } // end if
    } // end while
  return $goodsCount;
}
}
//EndLib
function module_sshop_goods($razdel, $section = null)
{
   $__module_subpage = array();
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "====== КАТАЛОГ ГРУПП ТОВАРА ==========";
if (empty($section->params[1]->value)) $section->params[1]->value = "0";
if (empty($section->params[2]->value)) $section->params[2]->value = "Y";
if (empty($section->params[3]->value)) $section->params[3]->value = "Каталог";
if (empty($section->params[4]->value)) $section->params[4]->value = "/";
if (empty($section->params[5]->value)) $section->params[5]->value = "В данной группе товаров нет!";
if (empty($section->params[6]->value)) $section->params[6]->value = "50";
if (empty($section->params[7]->value)) $section->params[7]->value = "Y";
if (empty($section->params[8]->value)) $section->params[8]->value = "Y";
if (empty($section->params[9]->value)) $section->params[9]->value = "Y";
if (empty($section->params[10]->value)) $section->params[10]->value = "";
if (empty($section->params[11]->value)) $section->params[11]->value = "====== РЕЖИМ ОТОБРАЖЕНИЯ ТОВАРОВ ========";
if (empty($section->params[12]->value)) $section->params[12]->value = "vit";
if (empty($section->params[13]->value)) $section->params[13]->value = "Вид";
if (empty($section->params[14]->value)) $section->params[14]->value = "Таблица с картинками";
if (empty($section->params[15]->value)) $section->params[15]->value = "Витрина";
if (empty($section->params[16]->value)) $section->params[16]->value = "Режим таблицы";
if (empty($section->params[17]->value)) $section->params[17]->value = "Режим витрины";
if (empty($section->params[18]->value)) $section->params[18]->value = "Y";
if (empty($section->params[19]->value)) $section->params[19]->value = "";
if (empty($section->params[20]->value)) $section->params[20]->value = "====== ВСПЛЫВАЮЩИЕ ПОДСКАЗКИ =========";
if (empty($section->params[21]->value)) $section->params[21]->value = "Добавить в корзину";
if (empty($section->params[22]->value)) $section->params[22]->value = "Показать все фото товара";
if (empty($section->params[23]->value)) $section->params[23]->value = "Сортировать по этому параметру";
if (empty($section->params[24]->value)) $section->params[24]->value = "Введите номер страницы для перехода ";
if (empty($section->params[25]->value)) $section->params[25]->value = "";
if (empty($section->params[26]->value)) $section->params[26]->value = "====== ПАРАМЕТРЫ ПОКАЗА ПРАЙС-ЛИСТА ========";
if (empty($section->params[27]->value)) $section->params[27]->value = "Показывать";
if (empty($section->params[28]->value)) $section->params[28]->value = "Все";
if (empty($section->params[29]->value)) $section->params[29]->value = "Цены";
if (empty($section->params[30]->value)) $section->params[30]->value = "Всего товаров";
if (empty($section->params[31]->value)) $section->params[31]->value = "";
if (empty($section->params[32]->value)) $section->params[32]->value = "=========== РЕЖИМ ТАБЛИЦЫ ==============";
if (empty($section->params[33]->value)) $section->params[33]->value = "N";
if (empty($section->params[34]->value)) $section->params[34]->value = "Y";
if (empty($section->params[35]->value)) $section->params[35]->value = "Y";
if (empty($section->params[36]->value)) $section->params[36]->value = "Y";
if (empty($section->params[37]->value)) $section->params[37]->value = "Y";
if (empty($section->params[38]->value)) $section->params[38]->value = "Y";
if (empty($section->params[39]->value)) $section->params[39]->value = "Y";
if (empty($section->params[40]->value)) $section->params[40]->value = "Y";
if (empty($section->params[41]->value)) $section->params[41]->value = "N";
if (empty($section->params[42]->value)) $section->params[42]->value = "Группа товара";
if (empty($section->params[43]->value)) $section->params[43]->value = "Артикул";
if (empty($section->params[44]->value)) $section->params[44]->value = "Фото";
if (empty($section->params[45]->value)) $section->params[45]->value = "Наименование";
if (empty($section->params[46]->value)) $section->params[46]->value = "Описание";
if (empty($section->params[47]->value)) $section->params[47]->value = "200";
if (empty($section->params[48]->value)) $section->params[48]->value = "Произв.";
if (empty($section->params[49]->value)) $section->params[49]->value = "Количество в наличии";
if (empty($section->params[50]->value)) $section->params[50]->value = "Наличие";
if (empty($section->params[51]->value)) $section->params[51]->value = "Аналоги";
if (empty($section->params[52]->value)) $section->params[52]->value = "Есть аналоги";
if (empty($section->params[53]->value)) $section->params[53]->value = "Цена";
if (empty($section->params[54]->value)) $section->params[54]->value = "Цена опт";
if (empty($section->params[55]->value)) $section->params[55]->value = "Купить";
if (empty($section->params[56]->value)) $section->params[56]->value = "";
if (empty($section->params[57]->value)) $section->params[57]->value = "======== РЕЖИМ ВИТРИНЫ ==========";
if (empty($section->params[58]->value)) $section->params[58]->value = "2";
if (empty($section->params[59]->value)) $section->params[59]->value = "Y";
if (empty($section->params[60]->value)) $section->params[60]->value = "Y";
if (empty($section->params[61]->value)) $section->params[61]->value = "Y";
if (empty($section->params[62]->value)) $section->params[62]->value = "Y";
if (empty($section->params[63]->value)) $section->params[63]->value = "Y";
if (empty($section->params[64]->value)) $section->params[64]->value = "Y";
if (empty($section->params[65]->value)) $section->params[65]->value = "N";
if (empty($section->params[66]->value)) $section->params[66]->value = "Y";
if (empty($section->params[67]->value)) $section->params[67]->value = "Y";
if (empty($section->params[68]->value)) $section->params[68]->value = "Артикул";
if (empty($section->params[69]->value)) $section->params[69]->value = "Производитель";
if (empty($section->params[70]->value)) $section->params[70]->value = "Есть&nbsp;аналоги";
if (empty($section->params[71]->value)) $section->params[71]->value = "Количество в наличии";
if (empty($section->params[72]->value)) $section->params[72]->value = "Наличие";
if (empty($section->params[73]->value)) $section->params[73]->value = "Цена";
if (empty($section->params[74]->value)) $section->params[74]->value = "Цена опт";
if (empty($section->params[75]->value)) $section->params[75]->value = "Краткое описание";
if (empty($section->params[76]->value)) $section->params[76]->value = "Подробное описание";
if (empty($section->params[77]->value)) $section->params[77]->value = "";
if (empty($section->params[78]->value)) $section->params[78]->value = "======= О ТОВАРЕ ПОДРОБНО ===========";
if (empty($section->params[79]->value)) $section->params[79]->value = "Техническое описание товара";
if (empty($section->params[80]->value)) $section->params[80]->value = "нет фото";
if (empty($section->params[81]->value)) $section->params[81]->value = "Данный товар отсутствует в каталоге!";
if (empty($section->params[82]->value)) $section->params[82]->value = "Y";
if (empty($section->params[83]->value)) $section->params[83]->value = "Y";
if (empty($section->params[84]->value)) $section->params[84]->value = "Y";
if (empty($section->params[85]->value)) $section->params[85]->value = "Y";
if (empty($section->params[86]->value)) $section->params[86]->value = "Y";
if (empty($section->params[87]->value)) $section->params[87]->value = "Y";
if (empty($section->params[88]->value)) $section->params[88]->value = "Y";
if (empty($section->params[89]->value)) $section->params[89]->value = "Y";
if (empty($section->params[90]->value)) $section->params[90]->value = "Y";
if (empty($section->params[91]->value)) $section->params[91]->value = "Артикул";
if (empty($section->params[92]->value)) $section->params[92]->value = "Производитель";
if (empty($section->params[93]->value)) $section->params[93]->value = "Количество в наличии";
if (empty($section->params[94]->value)) $section->params[94]->value = "Наличие";
if (empty($section->params[95]->value)) $section->params[95]->value = "Цена";
if (empty($section->params[96]->value)) $section->params[96]->value = "Цена опт";
if (empty($section->params[97]->value)) $section->params[97]->value = "Ещё фото";
if (empty($section->params[98]->value)) $section->params[98]->value = "Краткое описание";
if (empty($section->params[99]->value)) $section->params[99]->value = "Подробное описание";
if (empty($section->params[100]->value)) $section->params[100]->value = "";
if (empty($section->params[101]->value)) $section->params[101]->value = "======= СОПУТСТВУЮЩИЕ ТОВАРЫ И АНАЛОГИ =======";
if (empty($section->params[102]->value)) $section->params[102]->value = "N";
if (empty($section->params[103]->value)) $section->params[103]->value = "Y";
if (empty($section->params[104]->value)) $section->params[104]->value = "Y";
if (empty($section->params[105]->value)) $section->params[105]->value = "Y";
if (empty($section->params[106]->value)) $section->params[106]->value = "Y";
if (empty($section->params[107]->value)) $section->params[107]->value = "Y";
if (empty($section->params[108]->value)) $section->params[108]->value = "Y";
if (empty($section->params[109]->value)) $section->params[109]->value = "Y";
if (empty($section->params[110]->value)) $section->params[110]->value = "Y";
if (empty($section->params[111]->value)) $section->params[111]->value = "Группа товара";
if (empty($section->params[112]->value)) $section->params[112]->value = "Артикул";
if (empty($section->params[113]->value)) $section->params[113]->value = "Фото";
if (empty($section->params[114]->value)) $section->params[114]->value = "Наименование";
if (empty($section->params[115]->value)) $section->params[115]->value = "Описание";
if (empty($section->params[116]->value)) $section->params[116]->value = "Производитель";
if (empty($section->params[117]->value)) $section->params[117]->value = "Количество в наличии";
if (empty($section->params[118]->value)) $section->params[118]->value = "Наличие";
if (empty($section->params[119]->value)) $section->params[119]->value = "Цена";
if (empty($section->params[120]->value)) $section->params[120]->value = "Цена опт";
if (empty($section->params[121]->value)) $section->params[121]->value = "Купить";
if (empty($section->params[122]->value)) $section->params[122]->value = "";
if (empty($section->params[123]->value)) $section->params[123]->value = "======= Р Е Ж И М   В Ы В О Д А   А Н А Л О Г О В =======";
if (empty($section->params[124]->value)) $section->params[124]->value = "N";
if (empty($section->params[125]->value)) $section->params[125]->value = "";
if (empty($section->params[126]->value)) $section->params[126]->value = "======= К О М М Е Н Т А Р И И   К   Т О В А Р У =======";
if (empty($section->params[127]->value)) $section->params[127]->value = "N";
if (empty($section->params[128]->value)) $section->params[128]->value = "Комментарии к товару";
if (empty($section->params[129]->value)) $section->params[129]->value = "5";
if (empty($section->params[130]->value)) $section->params[130]->value = "Оставить комментарий";
if (empty($section->params[131]->value)) $section->params[131]->value = "Ваше имя";
if (empty($section->params[132]->value)) $section->params[132]->value = "Гость";
if (empty($section->params[133]->value)) $section->params[133]->value = "Ваш e-mail";
if (empty($section->params[134]->value)) $section->params[134]->value = "Ваше сообщение";
if (empty($section->params[135]->value)) $section->params[135]->value = "Комментариев к данному товару нет";
if (empty($section->params[136]->value)) $section->params[136]->value = "Ответ Администратора";
if (empty($section->params[137]->value)) $section->params[137]->value = "Сообщение не отправлено из-за ошибок";
if (empty($section->params[138]->value)) $section->params[138]->value = "Имя не задано";
if (empty($section->params[139]->value)) $section->params[139]->value = "Неверный e-mail";
if (empty($section->params[140]->value)) $section->params[140]->value = "Пустое сообщение";
if (empty($section->params[141]->value)) $section->params[141]->value = "Y";
if (empty($section->params[142]->value)) $section->params[142]->value = "Введите число с картинки";
if (empty($section->params[143]->value)) $section->params[143]->value = "Неверное число с картинки";
if (empty($section->params[144]->value)) $section->params[144]->value = "";
if (empty($section->params[145]->value)) $section->params[145]->value = "======= З А Г О Л О В К И   Т А Б Л И Ц =======";
if (empty($section->params[146]->value)) $section->params[146]->value = "Запрошенный товар";
if (empty($section->params[147]->value)) $section->params[147]->value = "Сопутствующие товары";
if (empty($section->params[148]->value)) $section->params[148]->value = "Похожие товары";
if (empty($section->params[149]->value)) $section->params[149]->value = "";
if (empty($section->params[150]->value)) $section->params[150]->value = "=========  К Н О П К И  ==============";
if (empty($section->params[151]->value)) $section->params[151]->value = "Назад";
if (empty($section->params[152]->value)) $section->params[152]->value = "Отправить";
if (empty($section->params[153]->value)) $section->params[153]->value = "N";
if (empty($section->params[154]->value)) $section->params[154]->value = "Просмотр заказа";
if (empty($section->params[155]->value)) $section->params[155]->value = "";
if (empty($section->params[156]->value)) $section->params[156]->value = "";
if (empty($section->params[157]->value)) $section->params[157]->value = "===== ПЕРЕНАПРАВЛЕНИЕ НА ДРУГИЕ СТРАНИЦЫ =====";
if (empty($section->params[158]->value)) $section->params[158]->value = "shopcart";
if (empty($section->params[159]->value)) $section->params[159]->value = "Y";
global $language,$PRICELIST,$SHOWGOODS,$SHOWPARAM,$MANYPAGE,$ACCOMP_SIMILAR,$COMMENTS,$MANYPAGECOMM;
global $SHOWPATH,$QUICKSEARCH, $skin,$cartcount,$sitearray,$_orderby, $sid;
global $SE_GROUPLIST,$SE_SUB_GRNAME,$SE_SUB_COUNTITEM,$SE_SUB_PATH,$SE_SUB_BUTTNAME,$SHOW_GOOD_NAIM;
global $SESSION_VARS, $ADMIN_MESSAGE, $ADMIN_BLOCK;
//session_register('typeshopgoods','shopcart', 'pricemoney', 'limitpage', 'shopcatgr');
$sid = session_id();
// Обнуляем переменные
$SHOWPATH = "";  // Путь по каталогу
$PARAM_VALUTA = ""; // Выбор валюты
$PARAM_COUNTGOODS = ""; // Выбор количества отображаемых товаров на странице
$MANYPAGE = "";  // Блок многостраничности
$MANYPAGECOMM = ""; //
$PRICELIST = ""; // Прайслист
$SHOWGOODS = ""; //
$ACCOMPANY = ""; // Сопутствующие товары
$SIMILAR = "";   // Похожие товары (аналоги)
$COMMENTS = "";  // Комментарии
//$SE_SUB_PATH='Путь по каталогу';
// Инициализируем язык
if (!empty($sitearray['language']))
  $lang = $sitearray['language'];
else
  $lang="rus";
// Путь к папкам с рисунками
$path_imggroup = '/images/'.$lang.'/shopgroup/';
$path_imgprice = '/images/'.$lang.'/shopprice/';
$path_imgall = '/images/'.$lang.'/shopimg/';
$wwwdir=getcwd();
$basegroup=0;
if (isset($section->params[1]->value)) { // если установлен ID базовой ветви
  $id_vetv=intval($section->params[1]->value);
  $basegroup=intval(@se_db_fields_item('shop_group',"id='$id_vetv'",'id'));
  // Назначаем номер ID базовой ветви
}
// Получаем номер группы товара
if (isset($_GET['shopcatgr']))
  $shopcatgr = intval($_GET['shopcatgr']);
elseif (!empty($_SESSION['shopcatgr']))
  $shopcatgr = $_SESSION['shopcatgr'];
else
  $shopcatgr = 0;
// Обнуляем сессию
$_SESSION['shopcatgr'] = 0;
// Получаем номер товара
if (!empty($_GET['viewgoods']))
  $viewgoods = intval(htmlspecialchars($_GET['viewgoods'], ENT_QUOTES));
else
  $viewgoods = 0;
// #########################################
// ### Задаем вид отображения товаров (таблица/витрина)
// ###
if ($section->params[2]->value=='N') // если переключатель "вид" не отображается, то
  $viewlist = $section->params[12]->value; // вид каталога по умолчанию (vit)
elseif (!empty($_SESSION['viewlist']))
  $viewlist = $_SESSION['viewlist'];
else
  $viewlist = $section->params[12]->value;
// ########################################
// ### Перенаправление на субстраницы
// ###
if ((empty($_sub)) && (se_db_is_item("shop_price","id_group='$shopcatgr'"))
    && ($shopcatgr != 0) && empty($viewgoods)) {
// Решаем, куда перенаправлять
  if ($viewlist=='vit') {
    $_SESSION['viewlist'] = 'vit';
    $_razdel=$razdel;
    $_sub=1;
  }
  else {
    $_SESSION['viewlist'] = 'tab';
    $_razdel=$razdel;
    $_sub=2;
  }
}
if (!empty($viewgoods)) {
// если выбран товар - направляем на подробное описание товара
    $_razdel=$razdel;
    if ($_sub == 4)
        $_sub = 4;
    else
        $_sub = 3;
//  header("Location: /".$_page."?razdel=".$razdel."&sub=3&viewgoods=".$viewgoods);
//  exit();
}
// #########################################
// ##################### !!! ВАЛИДАЦИЯ МАГАЗИНА !!! ####################
if (isset($_POST['goValidate'])) {
  $arrG=array();
  $tt = validate_count_shop(0, $arrG);
  $ADMIN_MESSAGE =
    "<div>Число позиций товаров во всем прайсе: ".$tt." Валидация прошла успешно.<br>".
      "<a href='$_page'>Нажмите на эту ссылку для перегрузки страницы</a>".
    "</div>";
//  header("Location: /".$_page);
//  $ssql = "delete from `shop_price` where `id_group`='0'";
//  $ssql = "update `shop_price` set `id_group`='65' where `id_group`='1'";
//  $result=se_db_query($ssql);
//  echo '1 --> 65';
//  echo '0 goods in 0 group. Ok.';
}
if ((isset($_POST['goDelFromGroup'])) && (isset($_POST['a_del_from_group_no']))) {
  $a_del_from_group_no = $_POST['a_del_from_group_no'];
  $dsql = "select COUNT(1) FROM `shop_price` WHERE `id_group`='".$a_del_from_group_no."'";
  $del_count = se_db_fetch_array(se_db_query($dsql));
  if ($del_count[0] > 0) {
    se_db_delete("shop_price", "`id_group`='".$a_del_from_group_no."'");
  }
  $dsql = "select `name` FROM `shop_group` WHERE `id`='".$a_del_from_group_no."'";
  $del_gr_name = se_db_fetch_array(se_db_query($dsql));
  $ADMIN_MESSAGE =
    "<div>Удалено ".$del_count[0]." записей из группы ".$a_del_from_group_no." (".$del_gr_name[0].")<br>".
      "<a href='$_page'>Нажмите на эту ссылку для перегрузки страницы</a>".
    "</div>";
}
$ADMIN_BLOCK = '';
if ($SESSION_VARS['GROUPUSER'] >= 2) {
  $ADMIN_BLOCK =
    '<form method="post">'.
      '<table><tbody>'.
        '<tr>'.
          '<td>'.
            '<b>Функции администратора (доступ только для администратора)</b><br>&nbsp;<br>'.
            'После работы с товарами прайс-листа вручную непосредственно на сервере поля каталога <br>"Количество товаров в группе: "'.
            ' могут перестать соответствовать их реальному количеству. <br>'.
            'Валидация магазина - подсчет реального количества товаров в каждой группе (для правильного отображения в группах товаров). ' .
            'После нажатия этой кнопки отображаемые поля <br>"Количество товаров в группе: " будут гарантированно отображать правильное количество товаров.'.
          '</td>'.
        '</tr>'.
        '<tr>'.
          '<td>'.
            '<input class="buttonSend" type="Submit" name="goValidate" value="Пересчитать количество товаров в группах">'.
          '</td>'.
        '</tr>'.
        '<tr>'.
          '<td>'.
            '<hr>'.
          '</td>'.
        '</tr>'.
        '<tr>'.
          '<td>Удаление товаров из группы:<br>'.
            'Введите номер группы, из которой хотите удалить товары, и нажмите "Удалить".<br>'.
            '(Удаление не каскадное. Товары будут удалены только из той группы, которая указана.)<br>'.
            'Номер группы можно посмотреть, зайдя в магазине в эту группу и в адресной строке браузера найдя выражение '.
            'shopcatgr=... Это число и есть номер группы. <br>'.
            '<input class="inp" type="text" name="a_del_from_group_no" size="5">&nbsp;'.
            '<input class="buttonSend" type="Submit" name="goDelFromGroup" value="Удалить товары из группы">'.
          '</td>'.
        '</tr>'.
        '<tr>'.
          '<td>'.
            '<hr>'.
          '</td>'.
        '</tr>'.
      '</tbody></table>'.
    '</form>';
}
// #####################################################################
// Получаем число выводимых товаров на одной странице
if (!empty($_GET['pagen'])) {
    $pagen = htmlspecialchars($_GET['pagen'], ENT_QUOTES);
    $_SESSION['limitpage'] = $pagen;
    $_GET['sheet']=1;
} elseif (!empty($_POST['pagen'])) {
    $pagen = htmlspecialchars($_POST['pagen'], ENT_QUOTES);
    $_SESSION['limitpage'] = $pagen;
    $_GET['sheet']=1;
} elseif (!empty($_SESSION['limitpage']))
    $pagen = $_SESSION['limitpage'];
else $pagen = "30";
//################### Работа с общими свойствами субстраниц ###############
//#########################################################################
if (isset($_sub) && (($_sub==1) || ($_sub==2) || ($_sub==3) || ($_sub==4))
    && ($_razdel==$razdel)) {
// ############################### SEARCH ##########################
  // ######## Заполняем строку $searchby
  $searchby='';
 /* if (isset($_SESSION['CATALOGSRH']['quickword'])) {  // Если строка поиска не пуста
  // Заполняем для поиска
      $searchby = " AND ((REPLACE(`shop_price`.`article`,' ','') LIKE '%".str_replace(' ','',$quickword)."%')".
                    " OR (`shop_price`.`name` LIKE '%$quickword%')".
                    " OR (`shop_price`.`note` LIKE '%$quickword%')".
                    " OR (`shop_price`.`text` LIKE '%$quickword%'))";
  }
  else {*/
  // Заполняем для вывода каталога
      if (!empty($_SESSION['CATALOGSRH']['category'])) {
          $shopcatgr = $_SESSION['CATALOGSRH']['category'];
          if ($shopcatgr >= 0)
              $searchby .= " AND (`id_group`='$shopcatgr') ";
          else $searchby = '';
      }
      else
          $searchby .= " AND (`id_group`='$shopcatgr') ";
/*  }*/
// ###############################  END SEARCH ##########################
// Получаем номер листа
if (!empty($_GET['sheet']))
  $sheet = htmlspecialchars($_GET['sheet'], ENT_QUOTES);
else
  $sheet = "1";
list($SE_SUB_GRNAME,$SE_SUB_COUNTITEM)=
             se_db_fields_item('shop_group',"id='$shopcatgr'","name,scount");
$arrG = array(); // нужен для shop_catalog_goods_cn
$SE_SUB_COUNTITEM = shop_catalog_goods_cn($shopcatgr, $arrG);
// ### Выводим список товаров для выбранной группы
$class = "tableRowOdd";
if (intval($pagen)==0)
  $limitpage = "";
else {
  if ((!empty($sheet))&&($sheet > 1))
    $limitpage = "LIMIT ".($pagen*$sheet-$pagen).",".$pagen;
  else
    $limitpage = "LIMIT ".$pagen;
}
// Сотрировка $orderby = 'aa', 'ad', 'na', 'nd', 'ma', 'md', 'pa' или 'pd'
if (!empty($_GET['orderby'])) {
    $orderby = htmlspecialchars($_GET['orderby'], ENT_QUOTES);
    $_SESSION['orderby'] = $orderby;
} elseif (!empty($_POST['orderby'])) {
    $orderby = htmlspecialchars($_POST['orderby'], ENT_QUOTES);
    $_SESSION['orderby'] = $orderby;
} elseif (!empty($_SESSION['orderby'])) $orderby = $_SESSION['orderby'];
else $orderby = "aa";
$sorderby = '';
$imgsort_a = ''; $imgsort_n = ''; $imgsort_p = ''; $imgsort_o = '';
$imgsort_m = ''; $imgsort_g = '';
$classsort_g = 'OrderPassive'; $classsort_a = 'OrderPassive'; $classsort_n = 'OrderPassive';
$classsort_m = 'OrderPassive'; $classsort_p = 'OrderPassive'; $classsort_o = 'OrderPassive';
switch ($orderby) {
  case 'ga': { $sorderby = ' ORDER BY group_name ASC'; $imgsort_g = '<img class="imgSortOrder" src="/./modules/sshop_goods/asc.gif" alt="по возрастанию" title="по возрастанию">'; $classsort_g = "OrderActive";} break;
  case 'gd': { $sorderby = ' ORDER BY group_name DESC'; $imgsort_g = '<img class="imgSortOrder" src="/./modules/sshop_goods/desc.gif" alt="по убыванию" title="по убыванию">'; $classsort_g = "OrderActive";} break;
  case 'aa': { $sorderby = ' ORDER BY shop_price.article ASC'; $imgsort_a = '<img class="imgSortOrder" src="/./modules/sshop_goods/asc.gif" alt="по возрастанию" title="по возрастанию">'; $classsort_a = "OrderActive";} break;
  case 'ad': { $sorderby = ' ORDER BY shop_price.article DESC'; $imgsort_a = '<img class="imgSortOrder" src="/./modules/sshop_goods/desc.gif" alt="по убыванию" title="по убыванию">'; $classsort_a = "OrderActive"; } break;
  case 'na': { $sorderby = ' ORDER BY shop_price.name ASC'; $imgsort_n = '<img class="imgSortOrder" src="/./modules/sshop_goods/asc.gif" alt="по возрастанию" title="по возрастанию">'; $classsort_n = "OrderActive"; } break;
  case 'nd': { $sorderby = ' ORDER BY shop_price.name DESC'; $imgsort_n = '<img class="imgSortOrder" src="/./modules/sshop_goods/desc.gif" alt="по убыванию" title="по убыванию">'; $classsort_n = "OrderActive"; } break;
  case 'ma': { $sorderby = ' ORDER BY shop_price.manufacturer ASC'; $imgsort_m = '<img class="imgSortOrder" src="/./modules/sshop_goods/asc.gif" alt="по возрастанию" title="по возрастанию">'; $classsort_m = "OrderActive"; } break;
  case 'md': { $sorderby = ' ORDER BY shop_price.manufacturer DESC'; $imgsort_m = '<img class="imgSortOrder" src="/./modules/sshop_goods/desc.gif" alt="по убыванию" title="по убыванию">'; $classsort_m = "OrderActive"; } break;
  case 'pa': { $sorderby = ' ORDER BY shop_price.price ASC'; $imgsort_p = '<img class="imgSortOrder" src="/./modules/sshop_goods/asc.gif" alt="по возрастанию" title="по возрастанию">'; $classsort_p = "OrderActive"; } break;
  case 'pd': { $sorderby = ' ORDER BY shop_price.price DESC'; $imgsort_p = '<img class="imgSortOrder" src="/./modules/sshop_goods/desc.gif" alt="по убыванию" title="по убыванию">'; $classsort_p = "OrderActive"; } break;
  case 'oa': { $sorderby = ' ORDER BY shop_price.price_opt ASC'; $imgsort_o = '<img class="imgSortOrder" src="/./modules/sshop_goods/asc.gif" alt="по возрастанию" title="по возрастанию">'; $classsort_o = "OrderActive"; } break;
  case 'od': { $sorderby = ' ORDER BY shop_price.price_opt DESC'; $imgsort_o = '<img class="imgSortOrder" src="/./modules/sshop_goods/desc.gif" alt="по убыванию" title="по убыванию">'; $classsort_o = "OrderActive"; } break;
  default: { $sorderby = ' ORDER BY shop_price.article ASC'; $imgsort_a = '<img class="imgSortOrder" src="/./modules/sshop_goods/asc.gif" alt="по возрастанию" title="по возрастанию">'; $classsort_a = "OrderActive";} break;
}
// Конец функции сортировки
// Запрос получения таблицы товаров (прайс-лист)
$sqlsp="select SQL_CALC_FOUND_ROWS shop_price.*, shop_group.name group_name
        FROM `shop_price`, `shop_group`
        WHERE (`shop_price`.`enabled`='Y') AND (`shop_group`.`id`= `shop_price`.`id_group`)
        ".$searchby."
        ".$sorderby."
        ".$limitpage.";";
$pricequery = se_db_query($sqlsp);
  list($cnrow) = mysql_fetch_row(mysql_query("select FOUND_ROWS()"));
if (intval($pagen)>0)
 $MANYPAGE = se_divpages($cnrow, $pagen, $section->params[24]->value); // "Введите номер страницы для перехода"
} // ########################## Конец работы с субстраницами ###############
// Передаем $shopcatgr в сессию для следующего прохода
// $_SESSION['shopcatgr'] = $shopcatgr;
// Тип валюты
if (!empty($_GET['pricemoney'])) {
    $pricemoney = htmlspecialchars($_GET['pricemoney'], ENT_QUOTES);
    $_SESSION['pricemoney'] = $pricemoney;
} elseif (!empty($_POST['pricemoney'])) {
    $pricemoney = htmlspecialchars($_POST['pricemoney'], ENT_QUOTES);
    $_SESSION['pricemoney'] = $pricemoney;
} elseif (!empty($_SESSION['pricemoney']))
    $pricemoney = $_SESSION['pricemoney'];
else {
    $pricemoney = se_db_fields_item('main',"lang='$lang'",'basecurr');
    if (empty($pricemoney)) 
        $pricemoney ='RUR';
}
// если массива, содержащего группы каталога, не существует, то создаем его
if (empty($menu_grouplist)) {
    // Заполняем двумерный массив menu_grouplist значениями таблицы shop_group (группы товаров)
    $menu_grouplist = array();
    $res = se_db_query("select `id`,`upid`,`name`,`typegroup`,`scount`,`page`,`lang`,`position` FROM `shop_group`".
                       " WHERE `lang`='$lang' ORDER BY `position` ASC;");
    if (!empty($res))
        WHILE ($line=se_db_fetch_array($res)) {
            $id = $line['id'];
            $menu_grouplist['id'][$id] = $id;
            $menu_grouplist['upid'][$id] = $line['upid'];
            $menu_grouplist['name'][$id] = $line['name'];
            $menu_grouplist['typegroup'][$id] = $line['typegroup'];
            $menu_grouplist['scount'][$id] = $line['scount'];
            $menu_grouplist['page'][$id] = $line['page'];
          //!!!!!!!!!!!!!!!!!!!!!!!!!!!
            if ($menu_grouplist['page'][$id] == $_page)
               $shopcatgr = $id;
        }
}
if (!empty($menu_grouplist['id'][$shopcatgr]))
  $typegroup = $menu_grouplist['typegroup'][$shopcatgr];
else
  $typegroup = "";
if (!empty($menu_grouplist['id'][$shopcatgr]))
  $scount = $menu_grouplist['scount'][$shopcatgr];
else
  $scount = "";
// ########### Добавление в корзину (shopcart)
if (!empty($_SESSION['shopcart'])) {
  $incart = $_SESSION['shopcart'];
} elseif (!empty($_COOKIE['shopcart']))
  $shopcart = $_COOKIE['shopcart'];
else $incart = array();
if (!empty($_POST['addcart'])) {
    // загружаем товар с идентификатором из базы выбранный в корзину товар (addcart)
    $sqltxt = "select `id`,`presence_count` FROM `shop_price`".
              " WHERE `id`='".$_POST['addcart']."' LIMIT 1;";
    $row_reload = se_db_fetch_array(se_db_query($sqltxt), MYSQL_ASSOC);
    if (isset($_POST['addcartcount']) && intval($_POST['addcartcount'])>0)
      $addcartcount = intval($_POST['addcartcount']);
    else
      $addcartcount = 1;
    if (!empty($incart[$_POST['addcart']])) {
      if ( ($row_reload['presence_count']>=0 &&
            ((intval($incart[$_POST['addcart']]) + $addcartcount) <= $row_reload['presence_count']))
          || ($row_reload['presence_count']==null) )
        $incart[$_POST['addcart']] = intval($incart[$_POST['addcart']]) + $addcartcount;
    }
    else
      $incart[$_POST['addcart']] = $addcartcount;
    $_SESSION['shopcart'] = $incart;
    if ($section->params[159]->value != 'N')
      header("Location: http://".$_SERVER['HTTP_HOST']."/".$section->params[158]->value);
    else
      header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//      header("Location: #");
    exit();
}
// ########################################
//############ ПУТЬ ПО КАТАЛОГУ
// $addpath - добавление в конце пути типа "/ Техническое описание товара"
// $viewgoods - товар, выбранный из прайс-листа
// $shopcatgr - группа каталога
// $k - номер id группы товара (или id товара)
// $aboutgoods - массив полей выбранного товара
// $menu_grouplist - двумерный массив таблицы "группы товара"
  $k = 0; // номер группы товара
  $addpath = "";
  if (!empty($viewgoods)) { // --- если находимся на странице товара, то создаем строку наименования товара ---
    $aboutgoods = se_db_fetch_array(se_db_query("select `id`,`id_group`,`enabled`,`name`,`article`".
                                                " FROM `shop_price`".
                                                " WHERE (`id`='$viewgoods') AND (`enabled`='Y') LIMIT 1"));
    // if (!empty($menu_grouplist['id'][$aboutgoods['id_group']]))
    //   $k = $menu_grouplist['id'][$aboutgoods['id_group']]; // получаем номер группы товаров для этого товара
    $k = $aboutgoods['id_group']; // какой группе принадлежит товар
    // добавляем строку с наименованием товара и артикулом"
    $addpath = '<span class=separPath> '.$section->params[4]->value.'</span>'.
               '<span class=txtActivePath> '.$aboutgoods['name'].'</span>';
  }                         // -----------------------------------------
  elseif (!empty($shopcatgr)) { // --- если находимся в группе товаров ---
    if (!empty($menu_grouplist['id'][$shopcatgr]))
      $k = $menu_grouplist['id'][$shopcatgr]; // получаем номер группы товаров
  }                             // ---------------------------------------
  $path_group = array(); // --- создаем массив пути ---
  while (!empty($menu_grouplist['id'][$k])) {
    if (($menu_grouplist['id'][$k]==$shopcatgr) && (empty($viewgoods)))
      $path_group[] .= '<span class=txtActivePath>'.$menu_grouplist['name'][$k].'</span>'; // если выбранная группа, и не выбран товар, то без ссылки
    else
      $path_group[] .= '<a class="lnkPath" href="?shopcatgr='.$menu_grouplist['id'][$k].'">'.$menu_grouplist['name'][$k].'</a>'; // иначе - со ссылкой
    $k = $menu_grouplist['upid'][$k]; // забираемся вверх по каталогу
  }                      // ---------------------------
  if (!empty($shopcatgr)||!empty($viewgoods))
    $path_group[] .= '<a class="lnkPath" href="?shopcatgr=0">'.$section->params[3]->value.'</a>'; // Начальный путь каталога в строке пути
  krsort($path_group);
  $SHOWPATH = '<table class="tablePath" width="100%" border="0" cellpadding="3">'.
              '  <tr>'.
              '    <td class="cellPath">'.
                     join("<span class=separPath> {$section->params[4]->value} </span>", $path_group).$addpath.
              '    </td>'.
              '  </tr>'.
              '</table>';
//######################
// ######################### ПАРАМЕТРЫ ДЛЯ ПОДРОБНОГО ПРОСМОТРА ТОВАРА (валюта, количество на странице)
// if (!empty($viewgoods))  // если выбран просмотр товара, то...
// ########### Валюта
  $PARAM_VALUTA =
    '<div class="SelectPrice">'.
    '  <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">'.
    '    <select name="pricemoney" onChange="submit();">';
  $res = mysql_query("select SQL_CACHE `name`,`title`,`lang` FROM `money_title` WHERE lang='$lang'");
  while ($rowmoney = se_db_fetch_array($res, MYSQL_ASSOC)) {
    if ($rowmoney['name'] == $pricemoney)
      $sel = "selected";
    else
      $sel = "";
    $PARAM_VALUTA .=
    '      <option value="'.$rowmoney['name'].'" '.$sel.'>&nbsp;'.$rowmoney['title'].'</option>';
  }
  $PARAM_VALUTA .=
    '    </select>'.
    '  </form>'.
    '</div>';
// ########### Количество товаров на странице
  $selpagen15 = $selpagen30 = $selpagen60 = $selpagen90 = $selpagenall = "";
  $selpagen = "selpagen".$pagen;
  if (intval($pagen)==0)
    $selpagenall = "selected";
  else
    $$selpagen = "selected";
  $PARAM_COUNTGOODS =
    '<div class="SelectCountGoods">'.
    '  <form style="margin:0px" action="" method=post enctype="multipart/form-data">'.
    '    <select  name="pagen" onChange="submit();">'.
    '      <option value="15" '.$selpagen15.'>&nbsp;15</option>'.
    '      <option value="30" '.$selpagen30.'>&nbsp;30</option>'.
    '      <option value="60" '.$selpagen60.'>&nbsp;60</option>'.
    '      <option value="90" '.$selpagen90.'>&nbsp;90</option>'.
    '      <option value="all" '.$selpagenall.'>&nbsp;'.$section->params[28]->value.'</option>'.
    '  </select>'.
    '  </form>'.
    '</div>';
  if (se_db_is_item("shop_group", "upid='$shopcatgr'")) { // если у нашей группы существуют подргупы, то
  // ############## РИСУЕМ КАТАЛОГ С ЕГО ВЕТКАМИ #################
    $cnstr = intval($section->params[6]->value);   // Количество символов в описании группы
    // Чтение подгрупп текущей группы
    $ssql=
      "select shop_group.scount as cn, shop_group.id, shop_group.upid, shop_group.name, shop_group.picture, shop_group.commentary".
      "  FROM shop_group".
      "  WHERE (shop_group.lang = '$lang') AND (shop_group.upid='$shopcatgr') AND (shop_group.active='Y')".
      "  GROUP BY shop_group.id".
      "  ORDER BY shop_group.position";
    $resgr = se_db_query($ssql);
    if (!empty($resgr)) {
      $SE_GROUPLIST = // Таблица групп каталога
          '<table class="tableTable" width="100%" border="0">'.
          '<tbody>';
      if ($shopcatgr > 0) { // Если это не корневая ветка, то выводим ее картинку, имя и комментарий
          // Если 'N', то не показывать картинку открытой группы (если выводим товары, то тоже не показываем)
          if (($section->params[8]->value=='N') || (($_sub==1)||($_sub==2)||($_sub==3)||($_sub==4)))
            $img = '';
          else {
          // иначе выводим картинку
              list($name,$comm,$picture)=se_db_fields_item('shop_group', "id='$shopcatgr'", "name, commentary, picture");
              if (!empty($picture))
                  $img =
                    '<img class="imgtlbGroupImg" width="150" align="left" src="'.$path_imggroup.$picture.'" border="0" alt="'.se_db_output($name).'"'.
                    '  title="'.se_db_output($name).'">';
              elseif (file_exists('././modules/sshop_goods/no_foto.gif'))
                  $img =
                      '<a class="lnkGroupImg" href="?shopcatgr='.$shopcatgr.'">'.
                      '  <img class="imgtlbGroupImg" width="150" align="left" src="/./modules/sshop_goods/no_foto.gif" border="0"'.
                      '    alt="'.se_db_output($name).'" title="'.se_db_output($name).'">'.
                      '</a>';
              else
                  $img = '';
          }
          $SE_GROUPLIST.= // Выводим картинку группы, имя группы и комментарий
            '<tr>'.
            '  <td class="celltlbGroupName">'. // Ячейка наименования группы
            '    <dl>'.
            '      <dt>'.$img.se_db_output($name).'</dt>'.
            '      <dd>'.se_db_output($comm).'</dd>'.
            '    </dl>'.
            '  </td>'.
            '</tr>';
      }
      $SE_GROUPLIST .=
          '<tr vAlign="top">'.
          '  <td>';
      // Создаем список подгрупп
      while ($line=se_db_fetch_array($resgr)) {
        if ($shopcatgr > 0) {
          // ############# ВЕТКА КАТАЛОГА (НЕ КОРНЕВАЯ) ###############
          // Если 'N', то не показывать фотографии подгрупп в списке подгрупп
          if (($section->params[9]->value=='N') || (($_sub==1)||($_sub==2)||($_sub==3)||($_sub==4)))
            $img = '';
          else {
          // иначе показываем фотографию подгруппы
              if (!empty($line['picture']))
                  $img =
                      '<a class="lnkSubGroupImg" href="?shopcatgr='.$line['id'].'">'.
                      '  <img class="imgtlbSubGroupImg" width="150" align="left" src="'.$path_imggroup.$line['picture'].'" border="0" '.
                      '  alt="'.se_db_output($line['name']).'"'.
                      '  title="'.se_db_output($line['name']).'">'.
                      '</a>';
              elseif (file_exists('././modules/sshop_goods/no_foto.gif'))
                  $img =
                      '<a class="lnkSubGroupImg" href="?shopcatgr='.$line['id'].'">'.
                      '  <img class="imgtlbSubGroupImg" width="150" align="left" src="/./modules/sshop_goods/no_foto.gif" border="0"'.
                      '    alt="'.se_db_output($line['name']).'" title="'.se_db_output($line['name']).'">'.
                      '</a>';
              else
                  $img = '';
          }
          $arrG = array(); // нужен для shop_catalog_goods_cn
          $SE_GROUPLIST.=
            '<table class="tableTable" border="0" width=100%>'.
            '<tbody>'.
            '  <tr>'.
            '    <td class="celltlbSubGroup" width=50%>'.
            '     '.$img.'<a class="lnkSubGroup" href="?shopcatgr='.$line['id'].'">'.se_db_output($line['name']).'</a> '.
                  '<span class="subgrCountGoods">('.shop_catalog_goods_cn(se_db_output($line['id']), $arrG).')</span>'.
            '    </td>'.
            '  </tr>'.
            '</tbody>'.
            '</table>';
        }
        else { // $shopcatgr == 0, значит корневая группа
          // ######################## КОРНЕВОЙ КАТАЛОГ ############################
          if (!empty($line['picture']))
            $img =
                   '<a class="lnkGroupImg" href="?shopcatgr='.$line['id'].'">'.
                   '  <img class="imgtlbGroupImg" width="150" align="left" src="'.$path_imggroup.$line['picture'].' " border="0"'.
                   '    alt="'.se_db_output($line['name']).'" title="'.se_db_output($line['name']).'">'.
                   '</a>';
          elseif (file_exists('././modules/sshop_goods/no_foto.gif'))
            $img =
                   '<a class="lnkGroupImg" href="?shopcatgr='.$line['id'].'">'.
                   '  <img class="imgtlbGroupImg" width="150" align="left" src="/./modules/sshop_goods/no_foto.gif" border="0"'.
                   '    alt="'.se_db_output($line['name']).'" title="'.se_db_output($line['name']).'">'.
                   '</a>';
          else
            $img = '';
          // Если 'N', то не показывать картинки групп корневого каталога
          if ($section->params[7]->value == 'N')
            $img = '';
          $SE_GROUPLIST.=
            '<table class="tableTable" border="0" width=100%>'.
            '<tbody>'.
            '  <tr>'.
            '    <td class="celltlbGroupImg" width=150>'.$img.'</td>'.  // Картинка группы
            '    <td class="celltlbGroupName" valign="top">'.               // Ячейка наименования группы
            '      <dl>'.
            '        <dt>'.
            '          <a class="lnkGroupTitle" href="?shopcatgr='.$line['id'].'">'.se_db_output($line['name']).'</a>'. // Ссылка наименования группы
            '        </dt>';
          $ressub=se_db_query("select id, name FROM shop_group WHERE upid='".$line['id']."';");
          if (!empty($ressub)) {
            $SE_GROUPLIST.= '<dd>';
            $ii=0;
            $iicount=se_db_num_rows($ressub);
            // Рисуем подгруппы, разделенные "|"
            while ($linesub=se_db_fetch_array($ressub)) {
              $SE_GROUPLIST.=
                '<a class="lnkSubGrTitle" href="?shopcatgr='.$linesub['id'].'" title="'.se_db_output($linesub['name']).'">'.se_db_output($linesub['name']).'</a>';
              if ($ii < $iicount-1)
                $SE_GROUPLIST.=
                  '<span class="vline"> | </span>';
              $ii++;
            }
            $SE_GROUPLIST.= '</dd>';
          }
          $SE_GROUPLIST.=
            '      </dl>'.
            '    </td>'.
            '  </tr>'.
            '</tbody>'.
            '</table>'.
            '<hr class="horline" width="100%">';
        } // конец прорисовки каталога
      } // end while
      $SE_GROUPLIST.=
        '    </td>'.
        '  </tr>'.
        '</tbody>'.
        '</table>';
    }
  }
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
//EndRazdel
}
else{
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==1)){
//BeginSubPage1
// #######################################
// ########### КАТАЛОГ ТОВАРОВ 
// ########### Режим ВИТРИНЫ
// ###########
// Если мы перешли на 1 субстраницу, значит - витрина
  $_SESSION['viewlist'] = 'vit';
// ########### ВЫВОДИМ ТОВАРЫ
  $PRICELIST=''; 
  $ncell=intval($section->params[58]->value); // Число колонок в витрине товаров по умолчанию
  if ($ncell<1) 
    $ncell=3;
  if ($ncell>4)
    $ncell=4;
  $widthproc=(100/$ncell);
  $ktr=0;
  if (!empty($pricequery))
  while ($row = se_db_fetch_array($pricequery)) 
  {
    $ktr++;
    if ($row['presence_count'] < 1) { 
      // Настройка отображения количества, имеющегося в наличии
      $row['presence_count'] = '--';
    }
    if (($row['presence_count'] < 1) && ($section->params[18]->value != "N")) // {$section->params[18]->value} - прятать кнопку корзины при нулевом количестве 
        $addcart = "&nbsp;";
    else {
        if (!empty($incart[$row['id']]) && ($incart[$row['id']] > 0)) 
          $classcart = "buttonAdd2Cart";
        else 
          $classcart = "buttonAddCart";
        $addcart = '<form style="margin:0px;" action="" method="post">';
        // Количество товаров в корзину
        if ($section->params[90]->value!='N') 
          $addcart .= '<input class="cartscount" name="addcartcount" value="1" size="3">';
        // Кнопка Добавить в корзину
        $addcart .= '<input class="buttonSend" id="'.$classcart.'" name="" type="submit" 
                       value="'.$section->params[155]->value.'" title="'.$section->params[21]->value.'">
                     <input type="hidden" name="addcart" value="'.$row['id'].'"></form>';
    }
    if (intval($row['price_opt']) == 0) 
      $row['price_opt'] = "";
    else 
      $row['price_opt'] = se_formatMoney(se_MoneyConvert($row['price_opt'], 
                                                         $row['curr'], 
                                                         $pricemoney, 
                                                         date("Ymd")), $pricemoney);
    $row['price'] = se_formatMoney(se_MoneyConvert($row['price'], 
                                                   $row['curr'], 
                                                   $pricemoney, 
                                                   date("Ymd")), $pricemoney);
    // Создание превьюшки, если ее нет
    if (!empty($row['img'])) {
      $sourceimg=$row['img'];
      $extimg=explode('.',$sourceimg);
      $previmg=@$extimg[0].'_prev.'.@$extimg[1];
      if (!file_exists($wwwdir.$path_imgprice.$previmg)) {
        @require_once('lib/lib_images.php');
        ThumbCreate($wwwdir.$path_imgprice.$previmg, $wwwdir.$path_imgprice.$sourceimg, @$extimg[1], 150);
      }
    }
    // Отображение превью фотографии в витрине
    if (!empty($row['img'])) { // Если есть картинка
      // если файл с указанной картинкой существует
      if (file_exists($wwwdir.$path_imgprice.substr($row['img'], 0, strrpos($row['img'],".")).'_prev'.substr($row['img'], strrpos($row['img'],"."))))
        $img = '<a href="?viewgoods='.$row['id'].'" title="'.$row['name'].'">'.
               '  <img class="gvimg" src="'.$path_imgprice.substr($row['img'], 0, strrpos($row['img'],".")).
               '_prev'.substr($row['img'], strrpos($row['img'],".")).'" border="0">'.
               '</a>';    
      else { // если нет файла по указанному пути, то выводим картинку "Нет фото"
        if (file_exists('././modules/sshop_goods/no_foto.gif'))
          $img = '<a href="?viewgoods='.$row['id'].'" title="'.$row['name'].'">'.
                 '  <img class="gvimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                 '</a>'; 
        else 
          $img = '';
      }
    } else { // Если путь к картинке не прописан (задумывалось, что картинки нет)
        if (file_exists('././modules/sshop_goods/no_foto.gif'))
          $img = '<a href="?viewgoods='.$row['id'].'" title="'.$row['name'].'">'.
                 '  <img class="gvimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                 '</a>'; 
        else 
          $img = ''; 
    }
    // Если есть еще фотографии к товару, то создаем активную ссылку на фотоальбом
    if (se_db_is_item("shop_img","id_price=".$row['id']))
      $more_photo = '<a href="#" onclick="window.open(\'/modules/sshop_goods/sshop_fotos.php?shop='.$_page.'&goods='.$row['id'].'&subg=0\',\'Window\',\'scrollbars=auto, toolbar=no, width=700, height=500, resizable=yes\')" 
      title="'.$section->params[22]->value.'">'.$section->params[97]->value.'</a>';
    else $more_photo = '';
    // Рисуем горизонтальный ряд витрины, заполняя его ячейками
    if ((($ktr-1)%$ncell)==0) 
      $PRICELIST .= '<tr class=tableRow>';
    $PRICELIST .= 
    '<td width="'.$widthproc.'%" class="celltlbVitrina" height="100%" valign="top">
      <!-- Товар -->
      <table class="tlbVitrina" width="100%" height="100%" border="0" cellpadding="3">'.
     '<tbody>'.
     '  <tr>'. // Наименование товара
     '    <td class=gvcellname colspan="2">'.
     '      <a class="gvnamezn" href="?viewgoods='.$row['id'].'"'.
     '       title="'.str_replace('[','_', $row['name']).'">'.$row['name'].'</a>'.
     '    </td>'.
     '  </tr>';
     // Артикул и аналоги, если они есть
      if ($section->params[59]->value!='N') { // Если артикул выводим, то
        $PRICELIST .= 
          '<tr>'.
            '<td class="gvcellart">'.
              '<span class=gvartnm>'.$section->params[68]->value.':&nbsp;</span>'.
              '<span class=gvartzn>'.$row['article'].'</span>'.
            '</td>';
            // Аналоги
            if ($section->params[61]->value!='N') { // Если аналоги выводим, то
            // Если есть аналоги, то нужно показывать активную ссылку на аналоги с текстом "_Есть аналоги_"
               $where_expr = 'id_price='.$row['id'];
               $where_expr2 = 'id_acc='.$row['id'];
               if (se_db_is_item("shop_sameprice",$where_expr) OR se_db_is_item("shop_sameprice",$where_expr2))
                 $PRICELIST .= 
                   '<td class="gvcellanalogs">'.
                     '<a class="gvanalogs" href="?razdel='.$razdel.'&sub=4&viewgoods='.$row['id'].'">'.
                     $section->params[70]->value.'</a>'.
                   '</td>'; 
               else
                 $PRICELIST .= '<td class="gvcellanalogs">&nbsp;</td>';
            }
        $PRICELIST .= 
          '</tr>';
      }         
      $PRICELIST .= 
          '<tr>'. 
            // Ячейка с картинкой
            '<td class="gvcellimg" align="center">'.
              $img.
              '<div class="gvmorephoto">'.$more_photo.'</div>'.
            '</td>'.
            '<td vAlign=top>'.
              '<table width="100%" border="0">'.
                '<tbody>';
                  // Если нет артикула, то аналоги выводим здесь (если Y)!
                  if (($section->params[59]->value=='N') && ($section->params[61]->value!='N')) {
                    $where_expr = 'id_price='.$row['id'];
                    $where_expr2 = 'id_acc='.$row['id'];
                    if (se_db_is_item("shop_sameprice",$where_expr) OR se_db_is_item("shop_sameprice",$where_expr2))
                      $PRICELIST .= 
                      '<tr>'.  
                        '<td class="gvcellanalogs">'.
                          '<a class="gvanalogs" href="?razdel='.$razdel.'&sub=4&viewgoods='.$row['id'].'">'.
                          $section->params[70]->value.'</a>'.
                        '</td>'.
                      '</tr>';
                  }
                  // Количество в наличии
                  if ($section->params[62]->value!="N") 
                    $PRICELIST .=  
                    '<tr>'.
                      '<td class="gvcellcount">'.
                        '<span class="gvcountnm">'.$section->params[71]->value.':&nbsp;</span>'.
                        '<span class="gvcountzn">'.$row['presence_count'].'</span>'.
                      '</td>'.
                    '</tr>';
                  // В наличии (есть/нет)
                  if ($section->params[63]->value!="N") 
                    $PRICELIST .=
                    '<tr>'.
                      '<td class="gvcellcount">'.
                        '<span class="gvcountnm">'.$section->params[72]->value.':&nbsp;</span>'.
                        '<span class="gvcountzn">'.$row['presence'].'</span>'.
                      '</td>'.
                    '</tr>';
              $PRICELIST .= 
                '</tbody>'.
              '</table>'.
            '</td>'.
          '</tr>'.
          '<tr>'.
            '<td>'. // Ячейка с ценами
              '<table width="100%" border="0">'.
                '<tbody>';
                  // Розничная цена
                  if ($section->params[64]->value!="N")
                    $PRICELIST .=
                    '<tr>'.
                      '<td class="gvcellprice">'.
                        '<span class="gvpricenm">'.$section->params[73]->value.':&nbsp;</span>'.
                        '<span class="gvpricezn">'.$row['price'].'</span>'.
                      '</td>'.
                    '</tr>';
                  // Оптовая цена
                  if ($section->params[65]->value!="N")
                    $PRICELIST .=
                    '<tr>'.
                      '<td class="gvcellprice_opt">'.
                        '<span class="gvprice_optnm">'.$section->params[74]->value.':&nbsp;</span>'.
                        '<span class="gvprice_optzn">'.$row['price_opt'].'</span>'.
                      '</td>'.
                    '</tr>';  
                $PRICELIST .=
                '</tbody>'.
              '</table>'.
            '</td>'.
            '<td class="gvcellcart">'.
                $addcart.
            '</td>'.
          '</tr>';
        // Производитель    
        if ($section->params[60]->value!='N')
          $PRICELIST .= 
          '<tr>'.
            '<td class="gvcellmanuf" colspan="2">'.
                '<span class="gvmanufnm">'.$section->params[69]->value.':&nbsp;</span>'.
                '<span class="gvmanufzn">'.$row['manufacturer'].'</span>'.
            '</td>'.
          '</tr>';
      // Краткое описание  
      if ($section->params[66]->value!='N')
        $PRICELIST .= 
          '<tr>'.
            '<td class="gvcellnote" colspan="2">'.
                '<span class="gvnotenm">'.$section->params[75]->value.':</span>'.
                '<div class="gvnotezn">'.$row['note'].'</div>'.
            '</td>'.
          '</tr>'; 
      // Описание  
      if ($section->params[67]->value!='N')
        $PRICELIST .= 
          '<tr>'.
            '<td class="gvcelltext" colspan="2">'.
                '<span class="gvtextnm">'.$section->params[76]->value.':</span>'.
                '<div class="gvtextzn">'.$row['text'].'</div>'.
            '</td>'.
          '</tr>';   
      $PRICELIST .=
        '</tbody>'.
      '</table>
      <!-- / Товар -->
    </td>';   
    if (($ktr%$ncell)==0) 
      $PRICELIST .= '</tr>'; // Закрываем горизонтальный ряд витрины
  }
  // Рисуем пустые ячейки в последнем ряду витрины, сколько не хватает  
  if ($ktr%$ncell>0) 
  {
    for ($i=1; $i<=($ncell-($ktr-($ncell*floor($ktr/$ncell)))); $i++)
      $PRICELIST .= 
      '<td class="celltlbVitrina">
          <table class="tlbVitrina" width="100%" height="100%" border="0" cellpadding="3">
            <tbody>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
       </td>';
    $PRICELIST .= '</tr>';
  }
//EndSubPage1
} else
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==2)){
//BeginSubPage2
// ######################################
// ###### КАТАЛОГ ТОВАРОВ
// ###### Режим таблицы
// Если мы перешли на 2 субстраницу, значит - таблица
  $_SESSION['viewlist'] = 'tab';
// ######
if ($cnrow > 0) { 
  $PRICELIST =
    '<table class="tableTable" id="tablePrice" width="100%" border="0"
                   cellpadding="3"><tbody class=tableBody>';
  $strlimit = $section->params[47]->value;
  if (empty($strlimit))
    $strlimit = 30;
}
$PRICELIST='';  
// Рисуем шапку таблицы
    $PRICELIST .= '<tr class="tableRow" id="tableHeader" valign="top">';
    // Группа товара
    if ($section->params[33]->value!="N")
      $PRICELIST .= '<td class="hgroup">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_g.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=2&'.
                          (($orderby=='ga')?se_sqs("orderby","gd"):se_sqs("orderby","ga")).'#productlst">'.$section->params[42]->value.'</a>'.
                      '</span>&nbsp;'.$imgsort_g.
                    '</td>';
    // Артикул (код товара)
    if ($section->params[34]->value!="N")
      $PRICELIST .= '<td class="hart">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_a.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=2&'.
                          (($orderby=='aa')?se_sqs("orderby","ad"):se_sqs("orderby","aa")).'#productlst">'.$section->params[43]->value.'</a>'.
                      '</span>&nbsp;'.$imgsort_a.
                    '</td>';
    // Фото
    if ($section->params[35]->value!="N")
      $PRICELIST .= '<td class="hpicture">'.
                      '<span class="htitle">'.$section->params[44]->value.'</span>'.
                    '</td>';
    // Наименование
    $PRICELIST .= '<td class="hname">'.
                    '<span class="htitle">'.
                        '<a class="'.$classsort_n.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=2&'.
                        (($orderby=='na')?se_sqs("orderby","nd"):se_sqs("orderby","na")).'#productlst">'.$section->params[45]->value.'</a>'.
                    '</span>&nbsp;'.$imgsort_n.
                  '</td>';
    // Описание
    if ($section->params[36]->value!="N")
      $PRICELIST .= '<td class="hnote">'.
                      '<span class="htitle">'.$section->params[46]->value.'</span>'.
                    '</td>';
    // Производитель
    if ($section->params[37]->value!="N")
      $PRICELIST .= '<td class="hmanuf">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_m.'" title="'.$section->params[23]->value.'" href="/'.$_page.'/'.$razdel.'/sub2/?'.
                          (($orderby=='ma')?se_sqs("orderby","md"):se_sqs("orderby","ma")).'#productlst">'.$section->params[48]->value.'</a>'.
                      '</span>&nbsp;'.$imgsort_m.
                    '</td>';
    // Количество
    if ($section->params[38]->value!="N")
      $PRICELIST .= '<td class="hcount">'.
                      '<span class="htitle">'.$section->params[49]->value.'</span>&nbsp;'.
                    '</td>';
    // В наличии 
    if ($section->params[39]->value!="N")
      $PRICELIST .= '<td class="hpresence">'.
                      '<span class="htitle">'.$section->params[50]->value.'</span>&nbsp;'.
                    '</td>';
    // Аналоги
    if ($section->params[40]->value!="N")
      $PRICELIST .= '<td class="hanalog">'.
                      '<span class="htitle">'.$section->params[51]->value.'</span>'.
                    '</td>';
    // Розничная цена 
    $PRICELIST .= '<td class="hprice">'.
                    '<span class="htitle" title="'.$section->params[23]->value.'">'.
                        '<a class="'.$classsort_p.'" href="/'.$_page.'/'.$razdel.'/sub2/?'.
                        (($orderby=='pa')?se_sqs("orderby","pd"):se_sqs("orderby","pa")).'#productlst">'.$section->params[53]->value.'</a>'.
                    '</span>&nbsp;'.$imgsort_p.
                  '</td>';
    // Оптовая цена
    if ($section->params[41]->value != "N")
      $PRICELIST .= '<td class="hprice_opt">'.
                      '<span class="htitle" title="'.$section->params[23]->value.'">'.
                          '<a class="'.$classsort_o.'" href="?razdel='.$razdel.'&sub=2&'.
                          (($orderby=='oa')?se_sqs("orderby","od"):se_sqs("orderby","oa")).'#productlst">'.$section->params[54]->value.'</a>'.
                      '</span>&nbsp;'.$imgsort_o.
                    '</td>';
    // В корзину
    $PRICELIST .= '<td width="15%" class="hcart">'.
                    '<span class=htitle>'.$section->params[55]->value.'</span>&nbsp;'.
                  '</td>';
    // Закрываем строку              
    $PRICELIST .= '</tr>';
// #######################################
// #### Рисуем содержимое рядов таблицы 
while ($row = se_db_fetch_array($pricequery)) { // извлекаем ряд
  // ### Настройка различных параметров отображения
  // ###
  // Стиль отображения четных и нечетных рядов
  if ($class != "tableRowOdd") // меняем стиль четных и нечетных строк
    $class = "tableRowOdd";  // нечетные
  else 
    $class = "tableRowEven"; // четные
  // Настройка кнопки "В корзину"
  if ($row['presence_count'] < 1) // Если нет в наличии,
    // Настройка отображения количества, имеющегося в наличии
    $row['presence_count'] = '--';
  // Настоойка отображения корзины  
  if (($row['presence_count'] < 1) && ($section->params[18]->value != "N")) // {$section->params[18]->value} - прятать кнопку корзины при нулевом количестве 
    $addcart = "&nbsp;";          // то не выводим кнопку "В корзину".
  else { // Если есть в наличии,
    // то определяем вид отображения кнопки "В корзину"
    if (!empty($incart[$row['id']]) && ($incart[$row['id']] > 0)) 
      $classcart = "buttonAdd2Cart";
    else 
      $classcart = "buttonAddCart";
    $addcart = '<form style="margin:0px;" action="" method=POST>';
    // Выводить поле ввода количества товара
    if ($section->params[90]->value!='N')  
      $addcart .=  '<input class="cartscount" name="addcartcount" VALUE="1" size="3">';
    // Кнопка помещения в корзину
    $addcart .='<input class="buttonSend" id="'.$classcart.'" name="" type="submit" value="'.$section->params[155]->value.'" title="'.$section->params[21]->value.'">
                <input type="hidden" name="addcart" value="'.$row['id'].'"></form>';
  }
  // Настройка отображения оптовой цены
  if (intval($row['price_opt']) == 0) 
    $row['price_opt'] = "";
  else 
    $row['price_opt'] = se_formatNumber(round(se_MoneyConvert($row['price_opt'], $row['curr'], $pricemoney, date("Ymd")),2));
  // Настройка отображения цены в формате выбранной валюты
  $row['price'] = se_formatNumber(round(se_MoneyConvert($row['price'], $row['curr'], $pricemoney, date("Ymd")),2));
  // Создание превьюшки, если ее нет
  if (!empty($row['img'])) {
    $sourceimg=$row['img'];
    $extimg=explode('.',$sourceimg);
    $previmg=@$extimg[0].'_prev.'.@$extimg[1];
    if (!file_exists($wwwdir.$path_imgprice.$previmg)) {
      @require_once('lib/lib_images.php');
      ThumbCreate($wwwdir.$path_imgprice.$previmg, $wwwdir.$path_imgprice.$sourceimg, @$extimg[1], 150);
    }
  }
  // Настройка отображения фотографии
  if (!empty($row['img'])) { // Если есть картинка 
        // если файл с указанной картинкой существует
        if (file_exists($wwwdir.$path_imgprice.substr($row['img'], 0, strrpos($row['img'],".")).'_prev'.substr($row['img'], strrpos($row['img'],"."))))
          $img = '<a href="?viewgoods='.$row['id'].'">'. 
                 '<img class="gimg" src="'.$path_imgprice.substr($row['img'], 0, strrpos($row['img'],".")).'_prev'.
                  substr($row['img'], strrpos($row['img'],".")).'" border="0">'.
                 '</a>';
        else { // если нет файла по указанному пути, то выводим картинку "Нет фото"
          if (file_exists('././modules/sshop_goods/no_foto.gif'))
            $img = '<a href="?viewgoods='.$row['id'].'" title="'.$row['name'].'">'.
                   '  <img class="gimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                   '</a>'; 
          else 
            $img = '';
        }
    } else { // Если нет картинки
        if (file_exists('././modules/sshop_goods/no_foto.gif'))
          $img = '<a href="?viewgoods='.$row['id'].'" title="'.$row['name'].'">'.
                 '  <img class="gimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                 '</a>'; 
        else 
          $img = ''; 
    }
  // ### Отображение рядов таблиц
  // ###
  $PRICELIST .= '<tr class="tableRow" id="'.$class.'">';    
  // Группа товара
  if ($section->params[33]->value!="N")
    $PRICELIST .= '<td class="hgroup">'.$row['group_name'].'&nbsp;</td>';
  // Поле Артикул
  if ($section->params[34]->value!="N")
    $PRICELIST .= '<td class="hart">'.$row['article'].'&nbsp;</td>';
  // Поле Фото
  if ($section->params[35]->value!="N")
    $PRICELIST .= '<td class="gcellimg" align="center">'.$img.'</td>';  
  // Поле Наименование
  $PRICELIST .= '<td class="hname">'.
                    '<a href="?viewgoods='.$row['id'].'">'.$row['name'].'</a>&nbsp;'.
                '</td>';
  // Поле Описание
  if ($section->params[36]->value!="N")
    $PRICELIST .= '<td class="hnote">'.limit_string($row['note'], $strlimit).'&nbsp;</td>';
  // Поле Производитель
  if ($section->params[37]->value!="N")
    $PRICELIST .= '<td class="hmanuf">'.$row['manufacturer'].'&nbsp</td>';
  // Количество
  if ($section->params[38]->value!="N")
      $PRICELIST .= '<td class="hcount">'.$row['presence_count'].'&nbsp</td>';
  // В наличии
  if ($section->params[39]->value!="N")
    $PRICELIST .= '<td align="left" class="hpresence">'.$row['presence'].'&nbsp;</td>';
  // Поле Аналоги
  // Если есть аналоги, то нужно показывать активную ссылку на аналоги с текстом "Есть аналоги"
  if ($section->params[40]->value!="N") { 
    $where_expr = 'id_price='.$row['id'];
    $where_expr2 = 'id_acc='.$row['id'];
    if (se_db_is_item("shop_sameprice",$where_expr) OR se_db_is_item("shop_sameprice",$where_expr2))
      $PRICELIST .= '<td class="hanalog">'.
                    '  <a href="?razdel='.$razdel.'&sub=4&viewgoods='.$row['id'].'">'.$section->params[52]->value.'</a>'.
                    '</td>';
    else
      $PRICELIST .= '<td class="hanalog">&nbsp;</td>';
  }
  // Розничная цена 
  $PRICELIST .= '<td class="hprice">'.$row['price'].'&nbsp;</td>';
  // Оптовая цена
  if ($section->params[41]->value != "N") 
    $PRICELIST .= '<td class="hprice_opt">'.$row['price_opt'].'&nbsp;</td>';
  // В корзину
  $PRICELIST .= '<td class="hcart">'.$addcart.'&nbsp;</td></tr>';
} // end while
//EndSubPage2
} else
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==3)){
//BeginSubPage3
// ###############################
// ### Подробное описание товара
// ###
$strlimit = $section->params[47]->value;
if (empty($strlimit))
  $strlimit = 30;
// ########################################################################
// #### В Ы В О Д И М   П О Д Р О Б Н О Е   О П И С А Н И Е   Т О В А Р А
// ####
if (!empty($viewgoods)) {
// ### Подробный просмотр товара
  $ssql = "SELECT SQL_BIG_RESULT `id`,`enabled`,`name`,`presence_count`,`presence`,`img`,`note`,`text`,".
          "`article`,`manufacturer`,`price`,`price_opt`,`special_price`,`curr`".
          " FROM `shop_price`".
          " WHERE (`id`='$viewgoods') AND (enabled='Y') LIMIT 1"; 
  $aboutgoods = se_db_fetch_array(se_db_query($ssql),MYSQL_ASSOC);
  if (empty($aboutgoods)) {
    $SHOWGOODS = '<div class="block_message"><span class="message">'.$section->params[81]->value.'</span></div>';
  }
  else {  
    $SHOWGOODS = 
    '<table class=tableTable id=tableGoods width=100% border="0" cellpadding="3">'.
      '<tbody class=tableBody>'.
        '<tr class=tableRow id=tableHeader>'.
          '<td class="serv" colspan="2" width="100%"><a class="serv_txt">'.$section->params[146]->value.'</a></td>'.
        '</tr>'.
        '<tr class=tableRow id=tableHeader>'.
          '<td class="titleGoods" colspan="2"><span class="titleGoodsTxt">'.$aboutgoods['name'].'</span></td>'.
        '</tr>';
    // Настройка отображения количества
    if (empty($aboutgoods['presence_count'])) 
      $aboutgoods['presence_count'] = '--';
    // Настройка отображения корзины
    if (($aboutgoods['presence_count'] < 1) && ($section->params[18]->value != "N")) // {$section->params[18]->value} - прятать кнопку корзины при нулевом количестве 
      $addcart = "&nbsp;";          // то не выводим кнопку "В корзину". 
    else {
      if (!empty($incart[$aboutgoods['id']]) && ($incart[$aboutgoods['id']] > 0)) 
        $classcart = "buttonAdd2Cart";
      else 
        $classcart = "buttonAddCart";
      $addcart = '<form style="margin:0px;" action="" method="post">'."\n";
      // Поле ввода Количество товаров в корзину
      if ($section->params[90]->value!='N') 
        $addcart .=  '<input class="cartscount" name="addcartcount" type="text" value="1" size="3">';
      $addcart .=  '<input class="buttonSend" id="'.$classcart.'" name="" type="submit" value="'.$section->params[155]->value.'" title="'.$section->params[21]->value.'">'.
                   '<input type="hidden" name="addcart" value="'.$aboutgoods['id'].'"></form>';
    }
    // Настройка отображения фотографии
    if (se_db_is_item("shop_img","id_price=".$aboutgoods['id'])) { // если к товару есть еще фото
                                                                   // то выводим картинку с якорем
          if (!empty($aboutgoods['img'])) { // Если у товара есть картинка, то пытаемся ее нарисовать
                // Если по указанному пути файл существует
                if (file_exists($wwwdir.$path_imgprice.$aboutgoods['img']))
                    $img = // Картинка с якорем
                         '<a href="#" onClick="window.open(\'../modules/sshop_goods/sshop_fotos.php?shop='.$_page.'&goods='.$aboutgoods['id'].
                         '  &subg=0\', \'Window\', \'scrollbars=auto,toolbar=no, width=700, height=500, resizable=yes\');" title="'.$section->params[22]->value.'">'.
                         '  <img class="gsfoto" src="'.$path_imgprice.$aboutgoods['img'].'" border="0" title="'.$section->params[97]->value.'">'.
                         '</a>';
                else { // если файла по указанному пути не существует
                    if (file_exists('././modules/sshop_goods/no_foto.gif'))
                        $img = '<img class="gsfoto" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">';
                    else 
                      $img = '';
                }
          } else { // Если у товара нет картинки, то выводим заглушку
                if (file_exists('././modules/sshop_goods/no_foto.gif'))
                  $img = '<img class="gsfoto" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">';
                else 
                  $img = '';
          }
          $img .= // Ссылка "Еще фото" ниже картинки        
               '<div class="linkgsfoto">'.
               ' <a href="#" onclick="window.open(\'../modules/sshop_goods/sshop_fotos.php?shop='.$_page.'&goods='.$aboutgoods['id'].
               '   &subg=0\', \'Window\', \'scrollbars=auto,toolbar=no, width=700, height=500, resizable=yes\')" title="'.$section->params[22]->value.'">'.
                   $section->params[97]->value.
               ' </a>'. 
               '</div>';
    } else { // если к товару нет дополнительных фото, то картинку выводим без якоря
          if (!empty($aboutgoods['img'])) { // Если есть картинка, рисуем ее   
                if (file_exists($wwwdir.$path_imgprice.$aboutgoods['img']))  // Если файл по указанному пути существует        
                    $img = '<img class="gsfoto" src="'.$path_imgprice.$aboutgoods['img'].'" border="0">';
                else {// Если файл по указанному пути не существует
                    if (file_exists('././modules/sshop_goods/no_foto.gif'))  
                      $img = '<img class="gsfoto" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">';
                    else 
                      $img = '';
                } 
          } else { // Если нет картинки, то выводим заглушку
                if (file_exists('././modules/sshop_goods/no_foto.gif'))  
                  $img = '<img class="gsfoto" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">';
                else 
                  $img = '';
          }
    }
    $tmpnote=str_replace('\r\n','<br>',$aboutgoods['note']);
    $tmptxt = str_replace('\r\n','<br>',$aboutgoods['text']);
    $tmptxt = str_replace('\n','',$tmptxt);
    // Выводим отображение товара
    $SHOWGOODS .= 
    // Картинка
    '<tr>'.
      '<td class="cellgsfoto" align="center">'.$img.'</td>'.
      '<td class="cellgs">';
      // Артикул
      if ($section->params[82]->value != "N")
        $SHOWGOODS .=  
          '<div class="divgsArt">'.
            '<span class="gstitleArt">'.$section->params[91]->value.':&nbsp;</span>'.
            '<span class="gstextArt">'.$aboutgoods['article'].'</span></div>';
      // Производитель
      if ($section->params[83]->value != "N")
        $SHOWGOODS .=  
          '<div class="divgsArt">'.
            '<span class="gstitleArt">'.$section->params[92]->value.':&nbsp;</span>'.
            '<span class="gstextArt">'.$aboutgoods['manufacturer'].'</span></div>';
      // Количество в наличии
      if ($section->params[84]->value != "N")                 
        $SHOWGOODS .=
          '<div class="divgsCn">'.
            '<span class="gstitleCn">'.$section->params[93]->value.':&nbsp;</span>'.
            '<span class="gstextCn">'.$aboutgoods['presence_count'].'</span></div>';
      // Наличие
      if ($section->params[85]->value != "N")                 
        $SHOWGOODS .=
          '<div class="divgsPresence">'.
            '<span class="gstitlePresence">'.$section->params[94]->value.':&nbsp;</span>'.
            '<span class="gstextPresence">'.$aboutgoods['presence'].'</span></div>';
      // Цена розничная
      if ($section->params[86]->value != "N")                 
        $SHOWGOODS .=
          '<div class="divgsPriceRozn">'.
            '<span class="gstitlePriceRozn">'.$section->params[95]->value.':&nbsp;</span>'.
            '<span class="gstextPriceRozn">'.se_formatMoney(se_MoneyConvert($aboutgoods['price'], $aboutgoods['curr'], $pricemoney, date("Ymd")), $pricemoney).'</span></div>';
      // Цена оптовая
      if ($section->params[87]->value != 'N') {
        // Настройка отображения оптовой цены
        if (intval($aboutgoods['price_opt']) == 0) 
          $aboutgoods['price_opt'] = "";
        else 
          $aboutgoods['price_opt'] = se_formatMoney(se_MoneyConvert($aboutgoods['price_opt'], $aboutgoods['curr'], $pricemoney, date("Ymd")), $pricemoney);
        // Вывод оптовой цены                           
        $SHOWGOODS .=  
          '<div class="divgsPriceOpt">'.
            '<span class="gstitlePriceOpt">'.$section->params[96]->value.':&nbsp;</span>'.
            '<span class="gstextPriceOpt">'.$aboutgoods['price_opt'].'</span><div>'; 
      }
      // Кнопка помещения в корзину
      $SHOWGOODS .=
          '<div class="divgsCart">'.$addcart.'</div>'.
        '</td>'.
      '</tr>';
      // Краткое описание
      if ($section->params[88]->value != "N")                 
        $SHOWGOODS .=
        '<tr>'.
          '<td class="cellgsNote" colspan="2">'.
            '<span class="gstitleNote">'.$section->params[98]->value.':</span>'.
            '<div class="gstextNote">'.$tmpnote.'</div>'.
          '</td>'.
        '</tr>';
      // Описание
      if ($section->params[89]->value != "N")                 
        $SHOWGOODS .=
        '<tr>'.
          '<td class="cellgsText" colspan="2">'.
            '<span class="gstitleText">'.$section->params[99]->value.':</span>'.
            '<div class="gstextText">'.$tmptxt.'</div>'.
          '</td>'.
        '</tr>';
    $SHOWGOODS .= 
      '</tbody>'.  
    '</table>';
// ################################################################
// #### ВЫВОДИМ ВЕРХ ТАБЛИЦЫ, ОБЪЕДИНЯЮЩЕЙ СОПУТСТВУЮЩИЕ И АНАЛОГИ
// ####
    $ACCOMP_SIMILAR = 
      '<table class="tableTable" id="tableAnalogs" width=100% border="0" cellpadding="3" width="100%">'.
      '<tbody class="tableBody">';
  // Сосчитаем количество столбцов 
  $col_count = 0; 
  // Группа товара
  if ($section->params[102]->value!="N") 
    $col_count++;
  // Артикул (код товара)
  if ($section->params[103]->value!="N") 
    $col_count++;
  // Фотография
  if ($section->params[104]->value!="N") 
    $col_count++;
  // Наименование
  $col_count++;
  // Описание
  if ($section->params[105]->value!="N") 
    $col_count++;
  // Производитель
  if ($section->params[106]->value!="N") 
    $col_count++;
  // Количество
  if ($section->params[107]->value!="N") 
    $col_count++;
  // В наличии 
  if ($section->params[108]->value!="N") 
    $col_count++;
  // Розничная цена 
    $col_count++;
  // Оптовая цена
  if ($section->params[109]->value!="N") 
    $col_count++;
  // В корзину
  $col_count++;       
// ################################################################    
// #### В Ы В О Д И М   С О П У Т С Т В У Ю Щ И Е   Т О В А Р Ы
// ####
  $ssql = "SELECT SQL_BIG_RESULT `shop_group`.`name` `group_name`, shop_price.id, shop_price.article, shop_price.name, shop_price.manufacturer, shop_price.note, shop_price.text,".
          " shop_price.presence_count, shop_price.presence, shop_price.price, shop_price.price_opt, shop_price.curr, shop_price.enabled".
          " FROM `shop_price`".
          " INNER JOIN `shop_accomp` ON shop_accomp.id_acc=shop_price.id".
          " INNER JOIN `shop_group` ON `shop_group`.`id`= `shop_price`.`id_group`".
          " WHERE (shop_accomp.id_price='".$aboutgoods['id']."') AND (shop_price.enabled='Y');";
  $res_acc = se_db_query($ssql);
  if (se_db_num_rows($res_acc) > 0) {
    $class = "tableRowOdd";
    // Сервисная строка "Сопутствующие товары"
    $ACCOMP_SIMILAR .=
      '<tr class="tableRow" id="tableHeader">'.
        '<td class="serv" colspan="'.$col_count.'" width="100%">'.
            '<a class="serv_txt">'.$section->params[147]->value.'</a>'.
        '</td>'.
      '</tr>';
    // ##################################################
    // ### Рисуем шапку таблицы сопутствующих товаров
    $ACCOMP_SIMILAR .= '<tr class="tableRow" id="tableHeader" valign="top">';
    // Группа товара
    if ($section->params[102]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="agroup">'.
                  '  <span class="htitle">'.
                  '    <a class="'.$classsort_g.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                       (($orderby=='ga')?se_sqs("orderby","gd"):se_sqs("orderby","ga")).'#productlst">'.$section->params[111]->value.'</a>'.
                  '  </span>&nbsp;'.$imgsort_g.
                  '</td>';
    // Артикул (код товара)
    if ($section->params[103]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="aart">'.
                  '  <span class="htitle">'.
                  '    <a class="'.$classsort_a.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                       (($orderby=='aa')?se_sqs("orderby","ad"):se_sqs("orderby","aa")).'#productlst">'.$section->params[112]->value.'</a>'.
                  '  </span>&nbsp;'.$imgsort_a.
                  '</td>';
    // Фотография
    if ($section->params[104]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="apicture">'.
                  '  <span class="htitle">'.$section->params[113]->value.'</span>'.
                  '</td>';
    // Наименование
    $ACCOMP_SIMILAR .= 
                '<td class="aname">'.
                '  <span class="htitle">'.
                '    <a class="'.$classsort_n.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                     (($orderby=='na')?se_sqs("orderby","nd"):se_sqs("orderby","na")).'#productlst">'.$section->params[114]->value.'</a>'.
                '  </span>&nbsp;'.$imgsort_n.
                '</td>';
    // Описание
    if ($section->params[105]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="anote">'.
                  '  <span class="htitle">'.$section->params[115]->value.'</span>'.
                  '</td>';
    // Производитель
    if ($section->params[106]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="amanuf" title="'.$section->params[23]->value.'">'.
                  ' <span class="htitle">'.
                  '   <a class="'.$classsort_m.'" href="?razdel='.$razdel.'&sub=3&'.
                      (($orderby=='ma')?se_sqs("orderby","md"):se_sqs("orderby","ma")).'#productlst">'.$section->params[116]->value.'</a>'.
                  '  </span>&nbsp;'.$imgsort_m.
                  '</td>';
    // Количество
    if ($section->params[107]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="acount">'.
                  '  <span class="htitle">'.$section->params[117]->value.'</span>'.
                  '</td>';
    // В наличии 
    if ($section->params[108]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="apresence">'.
                  '  <span class="htitle">'.$section->params[118]->value.'</span>'.
                  '</td>';
    // Розничная цена 
    $ACCOMP_SIMILAR .= 
                '<td class="aprice">'.
                '  <span class="htitle">'.
                '    <a class="'.$classsort_p.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                     (($orderby=='pa')?se_sqs("orderby","pd"):se_sqs("orderby","pa")).'#productlst">'.$section->params[119]->value.'</a>'.
                '  </span>&nbsp;'.$imgsort_p.
                '</td>';
    // Оптовая цена
    if ($section->params[109]->value != "N")
      $ACCOMP_SIMILAR .= 
                  '<td class="aprice_opt">'.
                  '  <span class="htitle">'.
                  '    <a class="'.$classsort_o.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                       (($orderby=='oa')?se_sqs("orderby","od"):se_sqs("orderby","oa")).'#productlst">'.$section->params[120]->value.'</a>'.
                  '  </span>&nbsp;'.$imgsort_o.
                  '</td>';
    // В корзину
    $ACCOMP_SIMILAR .= 
                '<td width="15%" class="acart">'.
                '  <span class="htitle">'.$section->params[121]->value.'</span>&nbsp;'.
                '</td>';
    // Закрываем строку           
    $ACCOMP_SIMILAR .= '</tr>';
    // ##################################################
    // ### Рисуем строки таблицы сопутствующих товаров  
    while ($row_acc = se_db_fetch_array($res_acc, MYSQL_ASSOC)) {
      // ###### Различные настройки отображения строк сопутствующих товаров
      // Настройка отображения Четных-нечетных строк 
      if ($class != "tableRowOdd") 
        $class = "tableRowOdd"; 
      else 
        $class = "tableRowEven";
      // Создание превьюшки, если ее нет
      if (!empty($row_acc['img'])) {
        $sourceimg=$row_acc['img'];
        $extimg=explode('.',$sourceimg);
        $previmg=@$extimg[0].'_prev.'.@$extimg[1];
        if (!file_exists($wwwdir.$path_imgprice.$previmg)) {
          @require_once('lib/lib_images.php');
          ThumbCreate($wwwdir.$path_imgprice.$previmg, $wwwdir.$path_imgprice.$sourceimg, @$extimg[1], 150);
        }
      }
      // Настройка отображения фотографии
      if (!empty($row_acc['img'])) { // Если есть фотография к товару, то рисуем ее
          if (file_exists($wwwdir.$path_imgprice.substr($row_acc['img'], 0, strrpos($row_acc['img'],".")).'_prev'.
                          substr($row_acc['img'], strrpos($row_acc['img'],".")))) // Если файл по указанному пути существует
              $img = '<a href="?viewgoods='.$row_acc['id'].'">'.
                     '<img class="aimg" src="'.$path_imgprice.substr($row_acc['img'], 0, strrpos($row_acc['img'],".")).'_prev'.
                     substr($row_acc['img'], strrpos($row_acc['img'],".")).'" border="0">'.
                     '</a>';
          else { // Если файл по указанному пути не существует
              if (file_exists('././modules/sshop_goods/no_foto.gif'))
                $img = '<a href="?viewgoods='.$row_acc['id'].'" title="'.$row_acc['name'].'">'.
                       '<img class="aimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                       '</a>'; 
              else 
                $img = ''; 
          }
      } else { // Если нет фотографии к товару, то выводим заглушку
          if (file_exists('././modules/sshop_goods/no_foto.gif'))
                $img = '<a href="?viewgoods='.$row_acc['id'].'" title="'.$row_acc['name'].'">'.
                       '<img class="aimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                       '</a>'; 
              else 
                $img = ''; 
      }
    // Настройка отображения количества в наличии
      if (empty($row_acc['presence_count'])) 
          $row_acc['presence_count'] = '--';
      // Настройка отображение помещения в корзину
      if (($row_acc['presence_count'] < 1) && ($section->params[18]->value != "N")) // {$section->params[18]->value} - прятать кнопку корзины при нулевом количестве
          $addcart = "&nbsp;";
      else {  
          // Настройка отображения кнопки В корзину
          if (!empty($incart[$row_acc['id']]) && ($incart[$row_acc['id']] > 0)) 
            $classcart = "buttonAdd2Cart";
          else 
            $classcart = "buttonAddCart";
          // Настройка отображения поля ввода Количества товаров в корзину
          $addcart = '<form style="margin:0px;" action="" method="post">';
          if ($section->params[110]->value!='N') 
            $addcart .= '<input class="cartscount" name="addcartcount" value="1" size="3">';
          $addcart .=  '<input class="buttonSend" id="'.$classcart.'" name="" type="submit" value="'.$section->params[155]->value.'" title="'.$section->params[21]->value.'">'.
                       '<input type="hidden" name="addcart" value="'.$row_acc['id'].'"></form>';
      }
      // Настройка отображения оптовой цены
      if (intval($row_acc['price_opt']) == 0) 
        $row_acc['price_opt'] = "";
      else 
        $row_acc['price_opt'] = se_formatNumber(round(se_MoneyConvert($row_acc['price_opt'], $row_acc['curr'], $pricemoney, date("Ymd")),2));
      // Настройка отображения цены   
      $row_acc['price'] = se_formatNumber(round(se_MoneyConvert($row_acc['price'], $row_acc['curr'], $pricemoney, date("Ymd")),2));
      // ##### Выводим строки сопутствующих товаров
      $ACCOMP_SIMILAR .= '<tr class="tableRow" id="'.$class.'">';    
      // Группа товара
      if ($section->params[102]->value!="N")
        $ACCOMP_SIMILAR .= '<td class="agroup">'.$row_acc['group_name'].'&nbsp;</td>';
      // Артикул
      if ($section->params[103]->value!="N")
        $ACCOMP_SIMILAR .= '<td class="aart">'.$row_acc['article'].'&nbsp;</td>';
      // Фотография
      if ($section->params[104]->value!="N")
        $ACCOMP_SIMILAR .= '<td class="acellimg" align="center">'.$img.'</td>';
      // Наименование
      $ACCOMP_SIMILAR .= 
                  '<td class="aname">'.
                  '  <a href="?viewgoods='.$row_acc['id'].'">'.$row_acc['name'].'</a>&nbsp;'.
                  '</td>';
      // Описание
      if ($section->params[105]->value!="N")
        $ACCOMP_SIMILAR .= '<td class="anote">'.limit_string($row_acc['note'], $strlimit).'&nbsp;</td>';
      // Производитель
      if ($section->params[106]->value!="N")
        $ACCOMP_SIMILAR .= '<td class="amanuf">'.$row_acc['manufacturer'].'&nbsp;</td>';
      // Количество
      if ($section->params[107]->value!="N")
        $ACCOMP_SIMILAR .= '<td class="acount">'.$row_acc['presence_count'].'&nbsp;</td>';
      // В наличии
      if ($section->params[108]->value!="N")
        $ACCOMP_SIMILAR .= '<td class="apresence" align="left">'.$row_acc['presence'].'&nbsp;</td>';
      // Розничная цена 
      $ACCOMP_SIMILAR .= '<td class="aprice">'.$row_acc['price'].'&nbsp;</td>';
      // Оптовая цена
      if ($section->params[109]->value != "N") 
        $ACCOMP_SIMILAR .= '<td class="aprice_opt">'.$row_acc['price_opt'].'&nbsp;</td>';
      // В корзину
      $ACCOMP_SIMILAR .= '<td class="acart">'.$addcart.'&nbsp;</td>';
      $ACCOMP_SIMILAR .= '</tr>';
    }
  }
// ###################################################
// ###################################################
// #### В Ы В О Д И М   А Н А Л О Г И
// ####
if ($section->params[124]->value != 'Y') { // неполные аналоги
  $ssql = "SELECT SQL_BIG_RESULT `shop_price`.*, `shop_group`.`name` `group_name`".
          " FROM `shop_price`".
          " INNER JOIN `shop_sameprice` ON `shop_sameprice`.`id_acc`=`shop_price`.`id`".
          " INNER JOIN `shop_group` ON `shop_group`.`id`= `shop_price`.`id_group`".
          " WHERE (`shop_sameprice`.`id_price`='".$aboutgoods['id']."')". 
          " AND (`shop_price`.`enabled`='Y')".$sorderby.";"; 
  $res_same = se_db_query($ssql);
  if (se_db_num_rows($res_same)==0) {
    $ssql = "SELECT SQL_BIG_RESULT `shop_price`.*, `shop_group`.`name` `group_name`".
            " FROM `shop_price`, `shop_group`".
            " INNER JOIN `shop_sameprice` ON `shop_sameprice`.`id_price`=`shop_price`.`id`".
            " INNER JOIN `shop_group` ON `shop_group`.`id`= `shop_price`.`id_group`".
            " WHERE (`shop_sameprice`.`id_acc`='".$aboutgoods['id']."')". 
            " AND (`shop_price`.`enabled`='Y')".$sorderby.";";
    $res_same = se_db_query($ssql);
  }
} else { // полные аналоги   
$arrAnalogs = array($viewgoods); // Массив аналогов (id). Нулевым элементом массива помещаем сам товар
$i = 0;               
// Проверяем по одному все элементы массива аналогов, не связаны ли с ними еще аналоги,
// попутно пополняя этот массив
while ($i < count($arrAnalogs)){
  $theElement=$arrAnalogs[$i++];
  // Проверяем, нет ли ссылок на элемент в столбце id_acc
  $res_same = se_db_query("SELECT `id_acc` FROM `shop_sameprice` WHERE `id_price` = '".$theElement."';");
  if (se_db_num_rows($res_same)>0)
    while ($row_same = se_db_fetch_array($res_same, MYSQL_ASSOC))
      if (!in_array($row_same['id_acc'], $arrAnalogs))
            $arrAnalogs[] = $row_same['id_acc'];
  // Проверяем, нет ли ссылок на элемент в столбце id_price  
  $res_same = se_db_query("SELECT `id_price` FROM `shop_sameprice` WHERE `id_acc` = '".$theElement."';");
  if (se_db_num_rows($res_same)>0)
    while ($row_same = se_db_fetch_array($res_same, MYSQL_ASSOC))
      if (!in_array($row_same['id_price'], $arrAnalogs))
            $arrAnalogs[] = $row_same['id_price'];
} // end while
// Создаем запрос на вывод аналогов
$i = 1; // начинаем с 1-го элемента, не берем нулевой элемент, т.к. это сам товар
$searchby = ''; // подготавливаем строку $searchby
if (count($arrAnalogs) == 1) // если в массиве существует только нулевой элемент
  $searchby = " AND 0;"; // задаем невыполнимое условие запроса
else {
  while ($i < count($arrAnalogs)) {
    $theElement = $arrAnalogs[$i];
    if ($i == 1) // т.е. $theElement = $arrAnalogs[1] - первый аналог 
      $searchby = " AND ((`shop_price`.`id`='".$theElement."')"; // тогда AND 
    else         // т.е. следующие элементы
      $searchby .= " OR (`shop_price`.`id`='".$theElement."')"; // тогда OR
    $i++;
  } 
  $searchby .= ")";
}
// В результате получим: " AND ((`id_price`='1') OR (`id_price`='3') OR (`id_price`='70'))"
$res_same = se_db_query("SELECT `shop_price`.*, `shop_group`.`name` `group_name`".
                        " FROM `shop_price`, `shop_group`".
                        " WHERE (`shop_group`.`id`= `shop_price`.`id_group`)". 
                        " AND (`shop_price`.`enabled`='Y')". 
                         $searchby.$sorderby.";");
}
// ########### Вывод полученных аналогов в таблицу
if (se_db_num_rows($res_same)>0) {
    $class = "tableRowOdd";
    // Сервисная строка "Аналоги"
    $ACCOMP_SIMILAR .= 
      '    <tr class="tableRow" id="tableHeader">'.
      '      <td class="serv" colspan="'.$col_count.'" width="100%">'.
      '        <a class="serv_txt">'.$section->params[148]->value.'</a>'.
      '      </td>'.
      '    </tr>';  
    // ###################################
    // ### Рисуем шапку таблицы аналогов
    $ACCOMP_SIMILAR .= '<tr class="tableRow" id="tableHeader" valign="top">';
    // Группа товара
    if ($section->params[102]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="agroup">'.
                  '  <span class="htitle">'.
                  '    <a class="'.$classsort_g.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                       (($orderby=='ga')?se_sqs("orderby","gd"):se_sqs("orderby","ga")).'#productlst">'.$section->params[111]->value.'</a>'.
                  '  </span>&nbsp;'.$imgsort_g.
                  '</td>';
    // Артикул (код товара)
    if ($section->params[103]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="aart">'.
                  '  <span class="htitle">'.
                  '    <a class="'.$classsort_a.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                       (($orderby=='aa')?se_sqs("orderby","ad"):se_sqs("orderby","aa")).'#productlst">'.$section->params[112]->value.'</a>'.
                  '  </span>&nbsp;'.$imgsort_a.
                  '</td>';
    // Фотография
    if ($section->params[104]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="apicture">'.
                  '  <span class="htitle">'.$section->params[113]->value.'</span>'.
                  '</td>';
    // Наименование
    $ACCOMP_SIMILAR .= 
                '<td class="aname">'.
                '  <span class="htitle">'.
                '    <a class="'.$classsort_n.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                     (($orderby=='na')?se_sqs("orderby","nd"):se_sqs("orderby","na")).'#productlst">'.$section->params[114]->value.'</a>'.
                '  </span>&nbsp;'.$imgsort_n.
                '</td>';
    // Описание
    if ($section->params[105]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="anote">'.
                  '  <span class="htitle">'.$section->params[115]->value.'</span>'.
                  '</td>';
    // Производитель
    if ($section->params[106]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="amanuf" title="'.$section->params[23]->value.'">'.
                  ' <span class="htitle">'.
                  '   <a class="'.$classsort_m.'" href="?razdel='.$razdel.'&sub=3&'.
                      (($orderby=='ma')?se_sqs("orderby","md"):se_sqs("orderby","ma")).'#productlst">'.$section->params[116]->value.'</a>'.
                  '  </span>&nbsp;'.$imgsort_m.
                  '</td>';
    // Количество
    if ($section->params[107]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="acount">'.
                  '  <span class="htitle">'.$section->params[117]->value.'</span>'.
                  '</td>';
    // В наличии 
    if ($section->params[108]->value!="N")
      $ACCOMP_SIMILAR .= 
                  '<td class="apresence">'.
                  '  <span class="htitle">'.$section->params[118]->value.'</span>'.
                  '</td>';
    // Розничная цена 
    $ACCOMP_SIMILAR .= 
                '<td class="aprice">'.
                '  <span class="htitle">'.
                '    <a class="'.$classsort_p.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                     (($orderby=='pa')?se_sqs("orderby","pd"):se_sqs("orderby","pa")).'#productlst">'.$section->params[119]->value.'</a>'.
                '  </span>&nbsp;'.$imgsort_p.
                '</td>';
    // Оптовая цена
    if ($section->params[109]->value != "N")
      $ACCOMP_SIMILAR .= 
                  '<td class="aprice_opt">'.
                  '  <span class="htitle">'.
                  '    <a class="'.$classsort_o.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=3&'.
                       (($orderby=='oa')?se_sqs("orderby","od"):se_sqs("orderby","oa")).'#productlst">'.$section->params[120]->value.'</a>'.
                  '  </span>&nbsp;'.$imgsort_o.
                  '</td>';
    // В корзину
    $ACCOMP_SIMILAR .= 
                '<td width="15%" class="acart">'.
                '  <span class=htitle>'.$section->params[121]->value.'</span>&nbsp;'.
                '</td>';
    // Закрываем строку           
    $ACCOMP_SIMILAR .= '</tr>';
  // #############################################
  // ### Рисуем содержимое рядов таблицы аналогов
  while ($row_same = se_db_fetch_array($res_same, MYSQL_ASSOC)) {
    // ### Настройка различных параметров отображения полей    
    // Четные-нечетные ряды таблицы
    if ($class != "tableRowOdd") 
      $class = "tableRowOdd"; 
    else 
      $class = "tableRowEven";
    // Создание превьюшки, если ее нет
    if (!empty($row_same['img'])) {
      $sourceimg=$row_same['img'];
      $extimg=explode('.',$sourceimg);
      $previmg=@$extimg[0].'_prev.'.@$extimg[1];
      if (!file_exists($wwwdir.$path_imgprice.$previmg)) {
        @require_once('lib/lib_images.php');
        ThumbCreate($wwwdir.$path_imgprice.$previmg, $wwwdir.$path_imgprice.$sourceimg, @$extimg[1], 150);
      }
    }
    // Настройка отображения фотографии
    if (!empty($row_same['img'])) { // Если есть фотография к товару, то рисуем ее
        if (file_exists($wwwdir.$path_imgprice.substr($row_same['img'], 0, strrpos($row_same['img'],".")).'_prev'.
                        substr($row_same['img'], strrpos($row_same['img'],".")))) // Если файл по указанному пути существует
            $img = '<a href="?viewgoods='.$row_same['id'].'">'.
                   '<img class="aimg" src="'.$path_imgprice.substr($row_same['img'], 0, strrpos($row_same['img'],".")).'_prev'.
                   substr($row_same['img'], strrpos($row_same['img'],".")).'" border="0">'.
                   '</a>';
        else { // Если файл по указанному пути не существует
            if (file_exists('././modules/sshop_goods/no_foto.gif'))
              $img = '<a href="?viewgoods='.$row_same['id'].'" title="'.$row_same['name'].'">'.
                     '<img class="aimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                     '</a>'; 
            else 
              $img = ''; 
        }
    } else { // Если нет фотографии к товару, то выводим заглушку
            if (file_exists('././modules/sshop_goods/no_foto.gif'))
              $img = '<a href="?viewgoods='.$row_same['id'].'" title="'.$row_same['name'].'">'.
                     '<img class="aimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                     '</a>'; 
            else 
                $img = ''; 
    }
    // Настройка отображение Количества товаров 
    if ($row_same['presence_count'] < 1) 
        $row_same['presence_count'] = '--';
    // Настройка помещения в корзину
    if (($row_same['presence_count'] < 1) && ($section->params[18]->value != "N")) // {$section->params[18]->value} - прятать кнопку корзины при нулевом количестве
        // Если нет товара, то поле "В корзину" не выводим
        $addcart = "&nbsp;";
    else {  
        // Если есть в корзине, то кнопка неактивна
        if (!empty($incart[$row_same['id']]) && ($incart[$row_same['id']] > 0)) 
          $classcart = "buttonAdd2Cart";
        else 
          $classcart = "buttonAddCart";
        // Настройка поля добавления в корзину
        $addcart = '<form style="margin:0px;" action="" method="post">';
        if ($section->params[110]->value!='N') 
          $addcart .= '  <input class="cartscount" name="addcartcount" value="1" size="3">';
        $addcart .= '  <input class="buttonSend" id="'.$classcart.'" name="" type="submit" value="'.$section->params[155]->value.'" title="'.$section->params[21]->value.'">'.
                    '  <input type="hidden" name="addcart" value="'.$row_same['id'].'">'.
                    '</form>';
      }     
    // Настройка отображения оптовой цены
    if (intval($row_same['price_opt']) == 0) 
      $row_same['price_opt'] = "";
    else 
      $row_same['price_opt'] = se_formatNumber(round(se_MoneyConvert($row_same['price_opt'], $row_same['curr'], $pricemoney, date("Ymd")),2));
    // Настройка отображения цены
    $row_same['price'] = se_formatNumber(round(se_MoneyConvert($row_same['price'], $row_same['curr'], $pricemoney, date("Ymd")),2));
    // #########################################
    // ### Отображение рядов таблицы аналогов
    $ACCOMP_SIMILAR .= '<tr class="tableRow" id="'.$class.'">';    
    // Группа товара
    if ($section->params[102]->value!="N")
      $ACCOMP_SIMILAR .= '<td class="agroup">'.$row_same['group_name'].'&nbsp;</td>';
    // Артикул
    if ($section->params[103]->value!="N")
      $ACCOMP_SIMILAR .= '<td class="aart">'.$row_same['article'].'&nbsp;</td>';
    // Фотография
    if ($section->params[104]->value!="N")
      $ACCOMP_SIMILAR .= '<td class="acellimg" align="center">'.$img.'</td>';
    // Наименование
    $ACCOMP_SIMILAR .= '<td class="aname">'.
                '  <a href="?viewgoods='.$row_same['id'].'">'.$row_same['name'].'</a>&nbsp;'.
                '</td>';
    // Описание
    if ($section->params[105]->value!="N")
      $ACCOMP_SIMILAR .= '<td class="anote">'.limit_string($row_same['note'], $strlimit).'&nbsp;</td>';
    // Производитель
    if ($section->params[106]->value!="N")
      $ACCOMP_SIMILAR .= '<td class="amanuf">'.$row_same['manufacturer'].'&nbsp;</td>';
    // Количество
    if ($section->params[107]->value!="N")
      $ACCOMP_SIMILAR .= '<td class="acount">'.$row_same['presence_count'].'&nbsp;</td>';
    // В наличии
    if ($section->params[108]->value!="N")
      $ACCOMP_SIMILAR .= '<td class="apresence" align="left">'.$row_same['presence'].'&nbsp;</td>';
    // Розничная цена 
    $ACCOMP_SIMILAR .= '<td class="aprice">'.$row_same['price'].'&nbsp;</td>';
    // Оптовая цена
    if ($section->params[109]->value != "N") 
      $ACCOMP_SIMILAR .= '<td class="aprice_opt">'.$row_same['price_opt'].'&nbsp;</td>';
    // В корзину
    $ACCOMP_SIMILAR .= '<td class="acart">'.$addcart.'&nbsp;</td>';
    $ACCOMP_SIMILAR .= '</tr>';
  }
}
// ################################################################
// #### ВЫВОДИМ НИЗ ТАБЛИЦЫ, ОБЪЕДИНЯЮЩЕЙ СОПУТСТВУЮЩИЕ И АНАЛОГИ
// ####
    $ACCOMP_SIMILAR .= '</tbody></table>';
// ###############################################
// #### К О М М Е Н Т А Р И И   К   Т О В А Р У
// ####
if ($section->params[127]->value != 'N') {
  // ### Выводим комментарии к товару
  if (!empty($_SESSION['error_message'])) $error_message = $_SESSION['error_message']; else $error_message = "";
  $_SESSION['error_message'] = null;
  if (isset($_POST['GoToComm'])) {
    $_SESSION['comm_message']['comm_name'] = $_POST['comm_name'];
    $_SESSION['comm_message']['comm_email'] = $_POST['comm_email'];
    $_SESSION['comm_message']['comm_note'] = $_POST['comm_note'];
    if (empty($_POST['comm_name'])) $_SESSION['error_message'] .= "<li>{$section->params[138]->value}</li>";
    if(!empty($_POST['comm_email'])&&!Comment_CheckMail($_POST['comm_email'])) $_SESSION['error_message'] .= "<li>{$section->params[139]->value}</li>";
    if(empty($_POST['comm_note'])) $_SESSION['error_message'] .= "<li>{$section->params[140]->value}</li>";
    $param77=$section->params[141]->value; // Включить защиту от спама?
    if ($param77!='N') {
        $_POST['pin'] = trim($_POST['pin']);
        require_once getcwd()."/lib/card.php";
        if (!checkcard($_POST['pin']))
          $_SESSION['error_message'] .= "<li>{$section->params[143]->value}</li>";  // "Неверное число с картинки"
    }
    if (empty($_SESSION['error_message'])) {
        se_db_query("INSERT INTO `shop_comm` ( `id_price` , `name` , `email` , `commentary` , `date`)
                     VALUES ('".$aboutgoods['id']."', '".$_POST['comm_name']."', '".$_POST['comm_email']."', '".$_POST['comm_note']."', '".date("Y-m-d")."');");
        $_SESSION['comm_message'] = array();
        header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page."?".se_sqs("","")."&".time()."#comment");
        exit();
    }else{
        header("Location: http://".$_SERVER['HTTP_HOST']."/".$_page."?".se_sqs("","")."&".time()."#addcomment");
        exit();
    }
  }
  if (!empty($_SESSION['comm_message']['comm_name'])) 
    $comm_name = $_SESSION['comm_message']['comm_name']; 
  else 
    $comm_name = $section->params[132]->value;
  if (!empty($_SESSION['comm_message']['comm_email'])) 
    $comm_email = $_SESSION['comm_message']['comm_email']; 
  else 
    $comm_email = "";
  if (!empty($_SESSION['comm_message']['comm_note'])) 
    $comm_note = $_SESSION['comm_message']['comm_note']; 
  else 
    $comm_note = "";
  $COMMENTS = "";
  // Получаем число выводимых комментариев на одной странице
  if (!empty($_GET['pagencomm'])) {  
      $pagencomm = htmlspecialchars($_GET['pagencomm'], ENT_QUOTES);
      $_SESSION['limitpagecomm'] = $pagencomm;
      $_GET['sheet']=1;
  } elseif (!empty($_POST['pagencomm'])) { 
      $pagencomm = htmlspecialchars($_POST['pagencomm'], ENT_QUOTES);
      $_SESSION['limitpagecomm'] = $pagencomm;
      $_GET['sheet']=1;
  } elseif (!empty($_SESSION['limitpagecomm'])) 
      $pagen = $_SESSION['limitpagecomm'];       
  else 
      $pagencomm = $section->params[129]->value; 
  // Получаем номер листа
  if (!empty($_GET['sheet']))  
    $sheet = htmlspecialchars($_GET['sheet'], ENT_QUOTES); 
  else
    $sheet = "1";
  if ((!empty($sheet))&&($sheet > 1)) 
    $limitpagecomm = ($pagencomm*$sheet-$pagencomm).",".$pagencomm;
  else 
    $limitpagecomm = $pagencomm;      
  $class = "tableRowOdd";
  $res_comm = 
      se_db_query("select SQL_CALC_FOUND_ROWS `id_price`,`commentary`,`name`,`date`,`email` from `shop_comm`".
                  " WHERE id_price='".$aboutgoods['id']."' ORDER BY id DESC, date DESC LIMIT ".
                  $limitpagecomm.";");
  $cnr = se_db_fetch_array(se_db_query("SELECT FOUND_ROWS()")); $cnrow = $cnr[0];
  $MANYPAGECOMM = se_divpages($cnrow, $pagencomm,$section->params[24]->value);
  $COMMENTS .= 
    '<a name="comment"></a>'.
    '<table class=tableTable id=tableComment width="100%" border="0" cellpadding="3">'.
    '  <tr class=tableRow id=tableHeader>'.
    '    <td width="100%"><a class="serv">'.$section->params[128]->value.'</a></td>'.
    '  </tr>'.
    '</table>'.
    '<div id="passert" name="passert" style="visibility: visible; display: block;" width="100%">'.
    '  <table class=tableTable id=tableComment cellpadding="3" width="100%">'.
    '  <tbody class=tableBody>';
  if (se_db_num_rows($res_comm)>0) {
      while ($row_comm = se_db_fetch_array($res_comm, MYSQL_ASSOC)) {
        $commtext=explode('<%comment%>',$row_comm['commentary']);
        if ($class != "tableRowOdd") 
          $class = "tableRowOdd"; 
        else 
          $class = "tableRowEven";
        $COMMENTS .= 
            '<tr class=tableRow id="'.$class.'">'.
            '  <td>'.
            '    <div class="comm_date">'.date("d.m.Y", strtotime($row_comm['date'])).'</div>'.
            '    <div class="comm_titlename">'.
            '      <a href="mailto:'.$row_comm['email'].'">'.htmlspecialchars($row_comm['name'],ENT_QUOTES).'</a>'.
            '    </div>'.
            '    <div class="comm_txt">'.str_replace("\r\n", '<br>', se_db_output(@$commtext[0]));
        if (!empty($commtext[1])) 
          $COMMENTS .= 
            '<p id="comm_answer">'.
            '  <b class="commenttitle">'.$section->params[136]->value.'</b><br>'.
            '  <em class=commenttext>'.str_replace("\r\n", '<br>', se_db_output(@$commtext[1])).'</em>'.
            '</p>';
        $COMMENTS .= '</div></td></tr>';
      }
  } else {
    $COMMENTS .= 
          '<tr class=tableRow id="tableRowOdd">'.
          '  <td>'.
          '    <span class="message">'.$section->params[135]->value.'</span>'.
          '  </td>'.
          '</tr>';
  }
  if ($class != "tableRowOdd") 
    $class = "tableRowOdd"; 
  else 
    $class = "tableRowEven";
  $COMMENTS .=
    '<tr class=trpusto>'.
    '  <td height="10px"></td>'.
    '</tr>'.
    '<tr class=tableRow id="'.$class.'">'.
    '  <td>';
    if (!empty($error_message)) 
      $COMMENTS .= 
        '<div class=block_message>'.
        '  <span class=error_message>'.$section->params[137]->value.' '.$error_message.'</span>'.
        '</div><br>';
  $COMMENTS .= 
      '<form style="margin:0px" name="" action="" method="post">'.
      '  <a name="addcomment"></a>'.
      '    <table class="tableTable" id="tableAddComment" width="100%" border="0" cellPadding="3">'.
      '      <tr class="tableRow" id="tableHeader">'.
      '        <td colspan=3>'.$section->params[130]->value.'</td>'.
      '      </tr>'.
      '      <tr vAlign="top" align="left">'.
      '        <td width="20%" class="comm_title" align="right">'.$section->params[131]->value.':&nbsp;<font color=red>*</font></td>'.
      '        <td width="10px" class="tdpusto"></td>'.
      '        <td class="comm_field" align="left">'.
      '          <input class="inputtext" id="field_name" name="comm_name" value="'.$comm_name.'" title="'.$section->params[131]->value.'" width="30">'.
      '        </td>'.
      '      </tr>'.
      '      <tr vAlign="top" align="left">'.
      '        <td width="20%" class="comm_title" align="right">'.$section->params[133]->value.':</td>'.
      '        <td width="10px" class="tdpusto"></td>'.
      '        <td class="comm_field" align="left">'.
      '          <input class="inputtext" id="field_email" name="comm_email" value="'.$comm_email.'" title="'.$section->params[133]->value.'" width="30">'.
      '        </td>'.
      '      </tr>'.
      '      <tr class=trpusto>'.
      '        <td colSpan="3" height="10px"></td>'.
      '      </tr>'.
      '      <tr vAlign="top" align="left">'.
      '        <td colspan="3" class="comm_title">'.$section->params[134]->value.':&nbsp;<font color=red>*</font></td>'.
      '      </tr>'.
      '      <tr vAlign="top" align="left">'.
      '        <td colspan="3" class="comm_area">'.
      '          <textarea style="width:100%;" cols="100" rows="7" class="areatext" id="field_note" name="comm_note" title="'.$section->params[134]->value.'">'.
                 $comm_note.'</textarea>'.
      '        </td>'.
      '      </tr>'.
      '      <tr class=trpusto>'.
      '        <td colSpan="3" height="10px"></td>'.
      '      </tr>';
  if($section->params[141]->value!='N') {
    $COMMENTS .= 
      '<tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">'.
      '  <td class="objTitl" colspan="2" width="25%"><font color=red>*</font>'.$section->params[142]->value.':</td>'.
      '  <td class="objArea" id="ank_chimg">'.
      '    <img class="ank_img" style="width:150px; height:30px" src="/lib/cardimage.php?session='.$sid.'&'.time().'"><br>'.
      '    <input maxlength="5" size="5" name="pin" type="text" class="inputText" title="&quot;{$section->params[3]->value}&quot;">'.
      '  </td>'.
      '</tr>';
  }
  $COMMENTS .= 
      '        <tr vAlign="top" align="left">'.
      '          <td colSpan="3">'.
      '            <input class="buttonSend" NAME="GoToComm" type="submit" value="'.$section->params[152]->value.'">'.
      '          </td>'.
      '        </tr>'.
      '      </table>'.
      '    </form>'.
      '  </td>'.
      '</tr>';
  $COMMENTS .= 
      '  </tbody>'.
      '  </table>'.
      '</div><br>';
} // Конец комментариев
}
}
//EndSubPage3
} else
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==4)){
//BeginSubPage4
// ####################################################
// ### ВЫВОД ТОВАРА В ТАБЛИЦЕ И АНАЛОГОВ В ТАБЛИЦЕ
// ###
$strlimit = $section->params[47]->value;
if (empty($strlimit))
  $strlimit = 30;
// ################################################################
// #### ВЫВОДИМ ВЕРХ ТАБЛИЦЫ, ОБЪЕДИНЯЮЩЕЙ ТОВАР И ЕГО АНАЛОГИ
// ####
    $SHOWGOODS = 
      '<table class="tableTable" id="tableAnalogs" width=100% border="0" cellpadding="3" width="100%">'.
      '<tbody class="tableBody">';
    // Сосчитаем количество столбцов 
    $col_count = 0; 
    // Группа товара
    if ($section->params[102]->value!="N") 
      $col_count++;
    // Артикул (код товара)
    if ($section->params[103]->value!="N") 
      $col_count++;
    // Фотография
    if ($section->params[104]->value!="N") 
      $col_count++;
    // Наименование
    $col_count++;
    // Описание
    if ($section->params[105]->value!="N") 
      $col_count++;
    // Производитель
    if ($section->params[106]->value!="N") 
      $col_count++;
    // Количество
    if ($section->params[107]->value!="N") 
      $col_count++;
    // В наличии 
    if ($section->params[108]->value!="N") 
      $col_count++;
    // Розничная цена 
      $col_count++;
    // Оптовая цена
    if ($section->params[109]->value!="N") 
      $col_count++;
    // В корзину
    $col_count++;
// ####################################################
// #### Запрошенный товар в табличном отображении
// ####
if (!empty($viewgoods)) {
 /* $ssql = "SELECT SQL_BIG_RESULT `id`,`id_analog`,`enabled`,`name`,`presence_count`,`presence`,`img`,`note`,`text`,".
            "`article`,`marka`,`app_models`,`manufacturer`,`price`,`price_opt`,`curr`".
          " FROM `shop_price`".
          " WHERE (`id`='$viewgoods') AND (enabled='Y') LIMIT 1"; 
   */       
  $ssql="SELECT SQL_BIG_RESULT `shop_price`.*, `shop_group`.`name` `group_name`".
       " FROM `shop_price`, `shop_group`".
       " WHERE (`shop_price`.`enabled`='Y')".
       " AND (`shop_group`.`id`= `shop_price`.`id_group`)".
       " AND (`shop_price`.`id`='".$viewgoods."');";
  $aboutgoods = se_db_fetch_array(se_db_query($ssql),MYSQL_ASSOC);
  if (empty($aboutgoods)) {
    $SHOWGOODS = '<div class="block_message"><font class="message">'.$section->params[81]->value.'</font></div>';
  }
  else {             
    $class = "tableRowOdd";
    // #### Строка "Запрошенный товар"
    $SHOWGOODS .= 
        '<tr class=tableRow id=tableHeader>'.
          '<td class="serv" colspan="'.$col_count.'" width="100%"><a class="serv_txt">'.$section->params[146]->value.'</a></td>'.
        '</tr>';
    // ###################################
    // #### Рисуем шапку таблицы товара
    $SHOWGOODS .= '<tr class="tableRow" id="tableHeader" valign="top">';
    // Группа товара
    if ($section->params[102]->value!="N") 
      $SHOWGOODS .= '<td class="agroup">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_g.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                          (($orderby=='ga')?se_sqs("orderby","gd"):se_sqs("orderby","ga")).'#productlst">'.$section->params[111]->value.'</a>'.
                      '</span>&nbsp;'.$imgsort_g.
                    '</td>'; 
    // Артикул (код товара)
    if ($section->params[103]->value!="N") 
      $SHOWGOODS .= '<td class="aart">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_a.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                          (($orderby=='aa')?se_sqs("orderby","ad"):se_sqs("orderby","aa")).'#productlst">'.$section->params[112]->value.'</a>'.
                      '</span>&nbsp;'.$imgsort_a.
                    '</td>';
    // Фотография
    if ($section->params[104]->value!="N") 
      $SHOWGOODS .= '<td class="apicture">'.
                      '<span class="htitle">'.$section->params[113]->value.'</span>'.
                    '</td>';
    // Наименование
    $SHOWGOODS .= '<td class="aname">'.
                    '<span class="htitle">'.
                        '<a class="'.$classsort_n.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                        (($orderby=='na')?se_sqs("orderby","nd"):se_sqs("orderby","na")).'#productlst">'.$section->params[114]->value.'</a>'.
                    '</span>&nbsp;'.$imgsort_n.
                  '</td>';
    // Описание
    if ($section->params[105]->value!="N") 
      $SHOWGOODS .= '<td class="anote">'.
                      '<span class="htitle">'.$section->params[115]->value.'</span>'.
                    '</td>';
    // Производитель
    if ($section->params[106]->value!="N") 
      $SHOWGOODS .= '<td class="amanuf">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_m.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                          (($orderby=='ma')?se_sqs("orderby","md"):se_sqs("orderby","ma")).'#productlst">'.$section->params[116]->value.'</a>'.
                      '</span>&nbsp;'.$imgsort_m.
                    '</td>';
    // Количество
    if ($section->params[107]->value!="N") 
      $SHOWGOODS .= '<td class="acount">'.
                      '<span class="htitle">'.$section->params[117]->value.'</span>'.
                    '</td>';
    // В наличии 
    if ($section->params[108]->value!="N") 
      $SHOWGOODS .= '<td class="apresence">'.
                      '<span class="htitle">'.$section->params[118]->value.'</span>'.
                    '</td>';
    // Розничная цена 
    $SHOWGOODS .= '<td class="aprice">'.
                    '<span class="htitle">'.
                        '<a class="'.$classsort_p.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                           (($orderby=='pa')?se_sqs("orderby","pd"):se_sqs("orderby","pa")).'#productlst">'.$section->params[119]->value.'</a>'.
                    '</span>&nbsp;'.$imgsort_p.
                  '</td>';
    // Оптовая цена
    if ($section->params[109]->value != "N") 
      $SHOWGOODS .= '<td class="aprice_opt">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_o.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                          (($orderby=='oa')?se_sqs("orderby","od"):se_sqs("orderby","oa")).'#productlst">'.$section->params[120]->value.'</a>'.
                      '</span>&nbsp;'.$imgsort_o.
                    '</td>';
    // В корзину
    $SHOWGOODS .= '<td width="15%" class="acart">'.
                    '<span class=htitle>'.$section->params[121]->value.'</span>&nbsp;'.
                  '</td>';
    // Закрываем строку
    $SHOWGOODS .= '</tr>';
    // ##############################################################
    // ### Настройка различных параметров отображения таблицы товара
    // Стиль отображения четных и нечетных рядов
    if ($class != "tableRowOdd") // меняем стиль четных и нечетных строк
      $class = "tableRowOdd";  // нечетные
    else 
      $class = "tableRowEven"; // четные
    // Настройка отображения оптовой цены
    if (intval($aboutgoods['price_opt']) == 0) 
      $aboutgoods['price_opt'] = "";
    else 
      $aboutgoods['price_opt'] = se_formatNumber(round(se_MoneyConvert($aboutgoods['price_opt'], $aboutgoods['curr'], $pricemoney, date("Ymd")),2));
    // Настройка отображения цены в формате выбранной валюты
    $aboutgoods['price'] = se_formatNumber(round(se_MoneyConvert($aboutgoods['price'], $aboutgoods['curr'], $pricemoney, date("Ymd")),2));
    // Создание превьюшки, если ее нет
    if (!empty($aboutgoods['img'])) {
      $sourceimg=$aboutgoods['img'];
      $extimg=explode('.',$sourceimg);
      $previmg=@$extimg[0].'_prev.'.@$extimg[1];
      if (!file_exists($wwwdir.$path_imgprice.$previmg)) {
        @require_once('lib/lib_images.php');
        ThumbCreate($wwwdir.$path_imgprice.$previmg, $wwwdir.$path_imgprice.$sourceimg, @$extimg[1], 150);
      }
    }
    // Настройка отображения фотографии
    if (!empty($aboutgoods['img'])) { // Если есть фотография к товару, то рисуем ее
        if (file_exists($wwwdir.$path_imgprice.substr($aboutgoods['img'], 0, strrpos($aboutgoods['img'],".")).'_prev'.
                        substr($aboutgoods['img'], strrpos($aboutgoods['img'],".")))) // Если файл по указанному пути существует
            $img = '<a href="?viewgoods='.$aboutgoods['id'].'">'.
                   '<img class="aimg" src="'.$path_imgprice.substr($aboutgoods['img'], 0, strrpos($aboutgoods['img'],".")).'_prev'.
                   substr($aboutgoods['img'], strrpos($aboutgoods['img'],".")).'" border="0">'.
                   '</a>';
        else { // Если файл по указанному пути не существует
            if (file_exists('././modules/sshop_goods/no_foto.gif'))
              $img = '<a href="?viewgoods='.$aboutgoods['id'].'" title="'.$aboutgoods['name'].'">'.
                     '<img class="aimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                     '</a>'; 
            else 
              $img = ''; 
        }
    } else { // Если нет фотографии к товару, то выводим заглушку
            if (file_exists('././modules/sshop_goods/no_foto.gif'))
              $img = '<a href="?viewgoods='.$aboutgoods['id'].'" title="'.$aboutgoods['name'].'">'.
                     '<img class="aimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                     '</a>'; 
            else 
                $img = ''; 
    }
    // Настройка отображения количества (если 0, то --)
    if ($aboutgoods['presence_count'] < 1)
      $aboutgoods['presence_count'] = '--';  
    // Настройка помещения в корзину
    if (($aboutgoods['presence_count'] < 1) && ($section->params[18]->value != "N")) //  - прятать кнопку корзины при нулевом количестве
      $addcart = "&nbsp;";          // то не выводим кнопку "В корзину".
    else { // Если есть в наличии,
      // то определяем вид отображения кнопки "В корзину"
      if (!empty($incart[$aboutgoods['id']]) && ($incart[$aboutgoods['id']] > 0)) 
        $classcart = "buttonAdd2Cart";
      else 
        $classcart = "buttonAddCart";
      $addcart = '<form style="margin:0px;" action="" method=POST>';
      // Выводить поле ввода количества товара
      if ($section->params[110]->value!='N')  
        $addcart .=  '<input class="cartscount" name="addcartcount" value="1" size="3">';
      // Кнопка помещения в корзину
      $addcart .='<input class="buttonSend" id="'.$classcart.'" name="" type="submit" value="'.$section->params[155]->value.'" title="'.$section->params[21]->value.'">
                  <input type="hidden" name="addcart" value="'.$aboutgoods['id'].'"></form>';
    }
    // #######################################
    // ### Отображение рядов таблицы товара
    // ###
    $SHOWGOODS .= '<tr class="tableRow" id="'.$class.'">';    
    // Группа товара
    if ($section->params[102]->value!="N")
      $SHOWGOODS .= '<td class="agroup">'.$aboutgoods['group_name'].'&nbsp;</td>';
    // Артикул
    if ($section->params[103]->value!="N")
      $SHOWGOODS .= '<td class="aart">'.$aboutgoods['article'].'&nbsp;</td>';
    // Фотография
    if ($section->params[104]->value!="N")
      $SHOWGOODS .= '<td class="acellimg" align="center">'.$img.'</td>';
    // Наименование
    $SHOWGOODS .= '<td class="aname">'.
                      '<a href="?viewgoods='.$aboutgoods['id'].'">'.$aboutgoods['name'].'</a>'.
                  '&nbsp;</td>';
    // Описание
    if ($section->params[105]->value!="N")
      $SHOWGOODS .= '<td class="anote">'.limit_string($aboutgoods['note'], $strlimit).'&nbsp;</td>';
    // Производитель
    if ($section->params[106]->value!="N")
      $SHOWGOODS .= '<td class="amanuf">'.$aboutgoods['manufacturer'].'&nbsp;</td>';
    // Количество
    if ($section->params[107]->value!="N")
      $SHOWGOODS .= '<td class="acount">'.$aboutgoods['presence_count'].'&nbsp;</td>';
    // В наличии
    if ($section->params[108]->value!="N")
      $SHOWGOODS .= '<td class="apresence" align="left">'.$aboutgoods['presence'].'&nbsp;</td>';
    // Розничная цена 
    $SHOWGOODS .= '<td class="aprice">'.$aboutgoods['price'].'&nbsp;</td>';
    // Оптовая цена
    if ($section->params[109]->value != "N") 
      $SHOWGOODS .= '<td class="aprice_opt">'.$aboutgoods['price_opt'].'&nbsp;</td>';
    // В корзину
    $SHOWGOODS .= '<td class="acart">'.$addcart.'&nbsp;</td>';
    // Закрываем строку
    $SHOWGOODS .= '</tr>';
  }
// ##################################################   
// ############### Выводим аналоги ##################
// ###############
// ############################################################
// #### Создаем запрос на вывод неполных или полных аналогов
if ($section->params[124]->value != 'Y') { // неполные аналоги
  $ssql = "SELECT SQL_BIG_RESULT `shop_price`.*, `shop_group`.`name` `group_name`".
          " FROM `shop_price`".
          " INNER JOIN `shop_sameprice` ON `shop_sameprice`.`id_acc`=`shop_price`.`id`".
          " INNER JOIN `shop_group` ON `shop_group`.`id`= `shop_price`.`id_group`".
          " WHERE (`shop_sameprice`.`id_price`='".$aboutgoods['id']."')". 
          " AND (`shop_price`.`enabled`='Y')".$sorderby.";"; 
  $res_same = se_db_query($ssql);
  if (se_db_num_rows($res_same)==0) {
    $ssql = "SELECT SQL_BIG_RESULT `shop_price`.*, `shop_group`.`name` `group_name`".
            " FROM `shop_price`, `shop_group`".
            " INNER JOIN `shop_sameprice` ON `shop_sameprice`.`id_price`=`shop_price`.`id`".
            " INNER JOIN `shop_group` ON `shop_group`.`id`= `shop_price`.`id_group`".
            " WHERE (`shop_sameprice`.`id_acc`='".$aboutgoods['id']."')". 
            " AND (`shop_price`.`enabled`='Y')".$sorderby.";";
    $res_same = se_db_query($ssql);
  }
} else { // полные аналоги
$arrAnalogs = array($viewgoods); // Массив аналогов (id). Нулевым элементом массива помещаем сам товар
$i = 0;               
// Проверяем по одному все элементы массива аналогов, не связаны ли с ними еще аналоги,
// попутно пополняя этот массив
while ($i < count($arrAnalogs)){  
  $theElement=$arrAnalogs[$i++];
  // Проверяем, нет ли ссылок на элемент в столбце id_acc
  $res_same = se_db_query("SELECT `id_acc` FROM `shop_sameprice` WHERE `id_price` = '".$theElement."';");
  if (se_db_num_rows($res_same)>0)
    while ($row_same = se_db_fetch_array($res_same, MYSQL_ASSOC))
      if (!in_array($row_same['id_acc'], $arrAnalogs))
            $arrAnalogs[] = $row_same['id_acc'];
  // Проверяем, нет ли ссылок на элемент в столбце id_price  
  $res_same = se_db_query("SELECT `id_price` FROM `shop_sameprice` WHERE `id_acc` = '".$theElement."';");
  if (se_db_num_rows($res_same)>0)
    while ($row_same = se_db_fetch_array($res_same, MYSQL_ASSOC))
      if (!in_array($row_same['id_price'], $arrAnalogs))
            $arrAnalogs[] = $row_same['id_price'];
} // end while
// Создаем запрос на вывод аналогов
$i = 1; // начинаем с 1-го элемента, не берем нулевой элемент, т.к. это сам товар
$searchby = ''; // подготавливаем строку $searchby
if (count($arrAnalogs) == 1) // если в массиве существует только нулевой элемент
  $searchby = " AND 0;"; // задаем невыполнимое условие запроса
else { 
  while ($i < count($arrAnalogs)) {
    $theElement = $arrAnalogs[$i];
    if ($i == 1) // т.е. $theElement = $arrAnalogs[1] - первый аналог 
      $searchby = " AND ((`shop_price`.`id`='".$theElement."')"; // тогда AND 
    else         // т.е. следующие элементы
      $searchby .= " OR (`shop_price`.`id`='".$theElement."')"; // тогда OR
    $i++;
  } 
  $searchby .= ")";
}
// В результате получим: " AND ((`id_price`='1') OR (`id_price`='3') OR (`id_price`='70'))"
$ssql = "SELECT `shop_price`.*, `shop_group`.`name` `group_name`".
                        " FROM `shop_price`, `shop_group`".
                        " WHERE (`shop_group`.`id`= `shop_price`.`id_group`)". 
                        " AND (`shop_price`.`enabled`='Y')". 
                         $searchby.$sorderby.";";
$res_same = se_db_query($ssql);
} // end if ($section->params[124]->value != 'Y')
if (se_db_num_rows($res_same) > 0) { // тогда выводим аналоги
  $class = "tableRowOdd";
  $SHOWGOODS .= 
        '<tr class=tableRow id=tableHeader>'.
          '<td class="serv" colspan="'.$col_count.'" width="100%"><a class="serv_txt">'.$section->params[148]->value.'</a></td>'.
        '</tr>';
    // ###################################
    // ### Рисуем шапку таблицы аналогов
    $SHOWGOODS .= '<tr class="tableRow" id="tableHeader" valign="top">';
    // Группа товара
    if ($section->params[102]->value!="N")
      $SHOWGOODS .= '<td class="agroup">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_g.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                          (($orderby=='ga')?se_sqs("orderby","gd"):se_sqs("orderby","ga")).'#productlst">'.$section->params[111]->value.'</a>'.
                      '</span>'.$imgsort_g.
                    '</td>';
    // Артикул (код товара)
    if ($section->params[103]->value!="N")
      $SHOWGOODS .= '<td class="aart">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_a.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                          (($orderby=='aa')?se_sqs("orderby","ad"):se_sqs("orderby","aa")).'#productlst">'.$section->params[112]->value.'</a>'.
                      '</span>'.$imgsort_a.
                    '</td>';
    // Фотография
    if ($section->params[104]->value!="N")
      $SHOWGOODS .= '<td class="apicture">'.
                      '<span class="htitle">'.$section->params[113]->value.'</span>'.
                    '</td>';
    // Наименование
    $SHOWGOODS .= '<td class="aname">'.
                    '<span class="htitle">'.
                        '<a class="'.$classsort_n.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                        (($orderby=='na')?se_sqs("orderby","nd"):se_sqs("orderby","na")).'#productlst">'.$section->params[114]->value.'</a>'.
                    '</span>'.$imgsort_n.
                  '</td>';
    // Описание
    if ($section->params[105]->value!="N")
      $SHOWGOODS .= '<td class="anote">'.
                      '<span class="htitle">'.$section->params[115]->value.'</span>'.
                    '</td>';
    // Производитель
    if ($section->params[106]->value!="N")
      $SHOWGOODS .= '<td class="amanuf">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_m.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                          (($orderby=='ma')?se_sqs("orderby","md"):se_sqs("orderby","ma")).'#productlst">'.$section->params[116]->value.'</a>'.
                      '</span>'.$imgsort_m.
                    '</td>';
    // Количество
    if ($section->params[107]->value!="N")
      $SHOWGOODS .= '<td class="acount">'.
                      '<span class="htitle">'.$section->params[117]->value.'</span>'.
                    '</td>';
    // В наличии 
    if ($section->params[108]->value!="N")
      $SHOWGOODS .= '<td class="apresence">'.
                      '<span class="htitle">'.$section->params[118]->value.'</span>'.
                    '</td>';
    // Розничная цена 
    $SHOWGOODS .= '<td class="aprice">'.
                    '<span class="htitle">'.
                        '<a class="'.$classsort_p.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                        (($orderby=='pa')?se_sqs("orderby","pd"):se_sqs("orderby","pa")).'#productlst">'.$section->params[119]->value.'</a>'.
                    '</span>'.$imgsort_p.
                  '</td>';
    // Оптовая цена
    if ($section->params[109]->value != "N")
      $SHOWGOODS .= '<td class="aprice_opt">'.
                      '<span class="htitle">'.
                          '<a class="'.$classsort_o.'" title="'.$section->params[23]->value.'" href="?razdel='.$razdel.'&sub=4&'.
                          (($orderby=='oa')?se_sqs("orderby","od"):se_sqs("orderby","oa")).'#productlst">'.$section->params[120]->value.'</a>'.
                      '</span>'.$imgsort_o.
                    '</td>';
    // В корзину
    $SHOWGOODS .= '<td class="acart" width="15%">'.
                    '<span class=htitle>'.$section->params[121]->value.'</span>'.
                  '</td>';
    // Закрываем строку
    $SHOWGOODS .= '</tr>';
  // #############################################
  // ### Рисуем содержимое рядов таблицы аналогов
  while ($row_same = se_db_fetch_array($res_same, MYSQL_ASSOC)) {
    // ### Настройка различных параметров отображения полей
    // Четные-нечетные ряды таблицы
    if ($class != "tableRowOdd") 
      $class = "tableRowOdd"; 
    else 
      $class = "tableRowEven";
    // Создание превьюшки, если ее нет
    if (!empty($row_same['img'])) {
      $sourceimg=$row_same['img'];
      $extimg=explode('.',$sourceimg);
      $previmg=@$extimg[0].'_prev.'.@$extimg[1];
      if (!file_exists($wwwdir.$path_imgprice.$previmg)) {
        @require_once('lib/lib_images.php');
        ThumbCreate($wwwdir.$path_imgprice.$previmg, $wwwdir.$path_imgprice.$sourceimg, @$extimg[1], 150);
      }
    }
    // Настройка отображения фотографии
    if (!empty($row_same['img'])) { // Если есть фотография к товару, то рисуем ее
        if (file_exists($wwwdir.$path_imgprice.substr($row_same['img'], 0, strrpos($row_same['img'],".")).'_prev'.
                        substr($row_same['img'], strrpos($row_same['img'],".")))) // Если файл по указанному пути существует
            $img = '<a href="?viewgoods='.$row_same['id'].'">'.
                   '<img class="aimg" src="'.$path_imgprice.substr($row_same['img'], 0, strrpos($row_same['img'],".")).'_prev'.
                   substr($row_same['img'], strrpos($row_same['img'],".")).'" border="0">'.
                   '</a>';
        else { // Если файл по указанному пути не существует
            if (file_exists('././modules/sshop_goods/no_foto.gif'))
              $img = '<a href="?viewgoods='.$row_same['id'].'" title="'.$row_same['name'].'">'.
                     '<img class="aimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                     '</a>'; 
            else 
              $img = ''; 
        }
    } else { // Если нет фотографии к товару, то выводим заглушку
            if (file_exists('././modules/sshop_goods/no_foto.gif'))
              $img = '<a href="?viewgoods='.$row_same['id'].'" title="'.$row_same['name'].'">'.
                     '<img class="aimg" src="/./modules/sshop_goods/no_foto.gif" border="0" title="'.$section->params[80]->value.'">'.
                     '</a>'; 
            else 
                $img = ''; 
    }
    // Настройка отображения количества (если 0, то --)
    if ($row_same['presence_count'] < 1)
      $row_same['presence_count'] = '--';  
    // Настройка помещения в корзину
    if (($row_same['presence_count'] < 1) && ($section->params[18]->value != "N")) // {$section->params[18]->value} - прятать кнопку корзины при нулевом количестве
      $addcart = "&nbsp;";
    else {  
      // Если есть в корзине, то кнопка неактивна
      if (!empty($incart[$res_same['id']]) && ($incart[$row_same['id']] > 0)) 
        $classcart = "buttonAdd2Cart";
      else 
        $classcart = "buttonAddCart";
      // Настройка поля добавления в корзину
      $addcart = '<form style="margin:0px;" action="" method="post">';
      if ($section->params[90]->value!='N') 
        $addcart .=  '<input class="cartscount" name="addcartcount" value="1" size="3">';
      $addcart .= '<input class="buttonSend" id="'.$classcart.'" name="" type="submit" value="'.$section->params[155]->value.'" title="'.$section->params[21]->value.'">'.
                  '<input type="hidden" name="addcart" value="'.$row_same['id'].'">'.
                 '</form>';
    }
    // Отображение оптовой цены
    if (intval($row_same['price_opt']) == 0) 
      $row_same['price_opt'] = "";
    else 
      $row_same['price_opt'] = se_formatNumber(round(se_MoneyConvert($row_same['price_opt'], $row_same['curr'], $pricemoney, date("Ymd")),2));
    // Отображение цены
    $row_same['price'] = se_formatNumber(round(se_MoneyConvert($row_same['price'], $row_same['curr'], $pricemoney, date("Ymd")),2));
    // #########################################
    // ### Отображение рядов таблицы аналогов
    $SHOWGOODS .= '<tr class="tableRow" id="'.$class.'">';    
    // Группа товара
    if ($section->params[102]->value!="N")
      $SHOWGOODS .= '<td class="agroup">'.$row_same['group_name'].'&nbsp;</td>';
    // Артикул
    if ($section->params[103]->value!="N")
      $SHOWGOODS .= '<td class="aart">'.$row_same['article'].'&nbsp;</td>';
    // Фотография
    if ($section->params[104]->value!="N")
      $SHOWGOODS .= '<td class="acellimg" align="center">'.$img.'</td>';
    // Наименование
    $SHOWGOODS .= '<td class="aname">'.
                      '<a href="?viewgoods='.$row_same['id'].'">'.$row_same['name'].'</a>&nbsp;'.
                  '</td>';
    // Описание
    if ($section->params[105]->value!="N")
      $SHOWGOODS .= '<td class="anote">'.limit_string($row_same['note'], $strlimit).'&nbsp;</td>';
    // Производитель
    if ($section->params[106]->value!="N")
      $SHOWGOODS .= '<td class="amanuf">'.$row_same['manufacturer'].'&nbsp</td>';
    // Количество
    if ($section->params[107]->value!="N")
      $SHOWGOODS .= '<td class="acount">'.$row_same['presence_count'].'&nbsp;</td>';
    // В наличии
    if ($section->params[108]->value!="N")
      $SHOWGOODS .= '<td class="apresence" align="left">'.$row_same['presence'].'&nbsp;</td>';
    // Розничная цена 
    $SHOWGOODS .= '<td class="aprice">'.$row_same['price'].'&nbsp;</td>';
    // Оптовая цена
    if ($section->params[109]->value != "N") 
      $SHOWGOODS .= '<td class="aprice_opt">'.$row_same['price_opt'].'&nbsp;</td>';
       // В корзину
    $SHOWGOODS .= '<td class="acart">'.$addcart.'&nbsp;</td>';
    // Закрываем строку
    $SHOWGOODS .= '</tr>';
  }
 }
}
// ################################################################
// #### ВЫВОДИМ НИЗ ТАБЛИЦЫ, ОБЪЕДИНЯЮЩЕЙ ТОВАР И ЕГО АНАЛОГИ
// ####
    $SHOWGOODS .= '</tbody></table>';
//EndSubPage4
} else
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==5)){
//BeginSubPage5
// Подкаталог
//EndSubPage5
}
}
//EndSubPages
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"e_shop\" [part.style]>
<noempty:part.title><H3 class=contentTitle[part.style_title]><span class=\"contentTitleTxt\">[part.title]</span></H3></noempty>
<noempty:part.image><a href=\"[part.image]\" target=_blank><img class=contentImage alt=\"[part.title]\" src=\"[part.image]\" border=0 [part.style_image]></a> </noempty>
<noempty:part.text><div class=contentText [part.style_text]>[part.text]</div></noempty>
<table class=\"tableTable\" id=\"tableGroup\" width=\"100%\" border=0>
  <tbody>
  <tr>
    <td>$ADMIN_MESSAGE<BR>$ADMIN_BLOCK</TD>
  </tr>
  <tr>
    <td>$SHOWPATH</td>
  </tr>
  <tr>
    <td>$SE_GROUPLIST</td>
  </tr>
  </tbody>
</table>
</div>
true
<!-- =============== END CONTENT ============= -->";
$__module_subpage[1]['form'] = "<div class=\"content\" id=\"e_shop\" > <!-- Sub1 Режим витрины -->
<table class=\"tableTable\" border=\"0\" width=\"100%\">
<tbody>
  <tr>
    <td>$SHOWPATH
    
    </td>
  </tr>
  <tr>
    <td>$SE_GROUPLIST    </td>
  </tr>
  <tr>
    <td class=\"cellAllCountInGroup\">
      <span class=\"txtAllCountInGroup\">({$section->params[30]->value}: $SE_SUB_COUNTITEM)</span>
    </td>
  </tr>
  <tr>
    <td class=\"tableRow\" id=\"cellParams\">
      <table class=\"tableTools\" style=\"width: 100%\">
      <tbody>
      <tr>
        <td class=\"cellPrice\">
          <span class=\"txtSelectPrice\">{$section->params[29]->value}&nbsp;</span>$PARAM_VALUTA
          
          </td>
        <td class=\"cellCountGoods\" align=\"left\">
          <span class=\"txtSelectCountGoods\">{$section->params[27]->value}&nbsp;</span>$PARAM_COUNTGOODS          </td>
        <td class=\"cellShow\" style=\"padding: 0px; margin: 0px\">&nbsp;
          <input class=\"buttonSend\" id=\"buttonTypeShop\" vAlign=\"top\" name=\"\" onclick=\"document.location.href='[@subpage2]&shopcatgr=$shopcatgr'\" type=\"button\" value=\"{$section->params[16]->value}\">
        </td>
      </tr>
      </tbody>
      </table>
    </td>
  </tr>
  <tr class=\"tableRow\">
    <td class=\"muchpages\" align=\"center\">$MANYPAGE
    
    </td>
  </tr>
</tbody>
</table>
<table class=\"tableTable\" id=\"tablePrice\">
<tbody>
    $PRICELIST</tbody>
</table>
<table class=\"tableTable\" border=\"0\" width=\"100%\">
<tbody>
    <tr class=\"tableRow\">
        <td align=\"center\">$MANYPAGE
        
        </td>
    </tr>
</tbody>
</table>
</div>
";
$__module_subpage[2]['form'] = "<div class=\"content\" id=\"e_shop\" > <!-- Sub2 Режим таблицы -->
<table class=\"tableTable\" border=\"0\" width=\"100%\">
<tbody>
  <tr>
    <td>$SHOWPATH
    
    
    </td>
  </tr>
  <tr>
    <td>$SE_GROUPLIST    </td>
  </tr>
  <tr>
    <td class=\"cellAllCountInGroup\">
      <span class=\"txtAllCountInGroup\">({$section->params[30]->value}: $SE_SUB_COUNTITEM)</span>
    </td>
  </tr>
  <tr>
    <td class=\"tableRow\" id=\"cellParams\">
      <table class=\"tableTools\" style=\"width: 100%\">
      <tbody>
      <tr>
        <td class=\"cellPrice\">
          <span class=\"txtSelectPrice\">{$section->params[29]->value}&nbsp;</span>$PARAM_VALUTA
          
          </td>
        <td class=\"cellCountGoods\" align=\"left\">
          <span class=\"txtSelectCountGoods\">{$section->params[27]->value}&nbsp;</span>$PARAM_COUNTGOODS          </td>
        <td class=\"cellShow\" style=\"padding: 0px; margin: 0px\">&nbsp;
          <input class=\"buttonSend\" id=\"buttonTypeShop\" vAlign=\"top\" name=\"\" onclick=\"document.location.href='[@subpage1]&shopcatgr=$shopcatgr'\" type=\"button\" value=\"{$section->params[17]->value}\">
        </td>
      </tr>
      </tbody>
      </table>
    </td>
  </tr>
  <tr class=\"tableRow\">
    <td class=\"muchpages\" align=\"center\">$MANYPAGE
    
    </td>
  </tr>
</tbody>
</table>
<table class=\"tableTable\" id=\"tablePrice\" border=0>
  <tbody>
$PRICELIST</tbody>
</table>
<table class=\"tableTable\" border=\"0\" width=\"100%\">
<tbody>
  <tr class=\"tableRow\">
    <td align=\"center\">$MANYPAGE
    
    </td>
  </tr>
</tbody>
</table>
</div>
";
$__module_subpage[3]['form'] = "<div class=\"content\" id=\"e_shop\" >  <!-- Sub3 О товаре подробно и сопутствующие товары с аналогами-->

  <table class=\"tableTable\" cellPadding=5 width=\"100%\" border=\"0\">
    <tbody>
      <tr>
        <td>$SHOWPATH</td>
      </tr>
      <tr>
        <td id=nameheader>$SE_SUB_GRNAME</td>
      </tr>
      <tr>
        <td class=\"tableRow\" id=\"cellParams\">
          <table class=\"tableParams\" style=\"width: 100%\">
          <tbody>
            <tr>
              <td class=\"cellPrice\"><span class=\"txtSelectPrice\">{$section->params[29]->value}&nbsp;</span>$PARAM_VALUTA</td>
              <td class=\"cellCountGoods\" align=\"left\"><span class=\"txtSelectCountGoods\">{$section->params[27]->value}&nbsp;</span>$PARAM_COUNTGOODS</td>
            </tr>
          </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <table class=\"tableTable\" width=\"100%\" border=\"0\">
    <tbody>
      <tr class=\"tableRow\">
        <td>$SHOWGOODS</td>
      </tr>
      <tr class=\"tableRow\">
        <td class=\"muchpages\" align=\"center\">$MANYPAGE</td>
      </tr>
      <tr class=\"tableRow\">
        <td>$ACCOMP_SIMILAR</td>
      </tr>
      <tr class=\"tableRow\">
        <td class=\"muchpages\" align=\"center\">$MANYPAGE</td>
      </tr>
      <tr class=\"tableRow\">
        <td align=\"center\">$MANYPAGECOMM</td>
      </tr>
      <tr class=\"tableRow\">
        <td>$COMMENTS</td>
      </tr>
    </tbody>
  </table>
  
  </div>
";
$__module_subpage[4]['form'] = "<div class=\"content\" id=\"e_shop\" > <!-- Sub4 Товар и аналоги в таблице-->

  <table class=\"tableTable\" cellPadding=5 width=\"100%\" border=\"0\">
    <tbody>
      <tr>
        <td>$SHOWPATH</td>
      </tr>
      <tr>
        <td id=nameheader>$SE_SUB_GRNAME</td>
      </tr>
      <tr>
        <td class=\"tableRow\" id=\"cellParams\">
          <table class=\"tableParams\" style=\"width: 100%\">
          <tbody>
            <tr>
              <td class=\"cellPrice\"><span class=\"txtSelectPrice\">{$section->params[29]->value}&nbsp;</span>$PARAM_VALUTA</td>
              <td class=\"cellCountGoods\" align=\"left\"><span class=\"txtSelectCountGoods\">{$section->params[27]->value}&nbsp;</span>$PARAM_COUNTGOODS</td>
            </tr>
          </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <table class=\"tableTable\" width=\"100%\" border=\"0\">
    <tbody>
      <tr class=\"tableRow\">
        <td class=\"muchpages\" align=\"center\">$MANYPAGE</td>
      </tr>
      <tr class=\"tableRow\">
        <td>$SHOWGOODS</td>
      </tr>
      <tr class=\"tableRow\">
        <td class=\"muchpages\" align=\"center\">$MANYPAGE</td>
      </tr>
    </tbody>
  </table>
  
  
</div>
";
$__module_subpage[5]['form'] = "<div class=\"content\" id=\"e_shop\" > <!-- Sub5 (только макет) Подкаталог --></div>
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};