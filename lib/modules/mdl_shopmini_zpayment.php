<?php
//BeginLib
// ������� �������� ����� ������ �� ���� ������ �������� �� ������ �����, 
// � ���� �� ������� ����� ��������� ������� ������ � ������������� ID
// ����� ������������ ���������� ���� ������ �� �������� config_zp.php
if (!function_exists('GetAmountOrder')) {
function GetAmountOrder($IdOrder){
	global $AmountOrder;
	// ������ � �� ����� ������� 
	return $AmountOrder;
}}
// ������� ��������� ���������� ������
// ���������� ���������� ������� ������� �� ������ ������ ������ �����.
if (!function_exists('ConfirmOrder')) {
function ConfirmOrder($IdOrder) {
// ����� ���������� ��������� ��� �������� �� ���������� ������� ������, 
// ����������� �������, �������� ������ � ��. �������� ����� ��������� ������ ������
	// ���������� ������ �������
    se_shop_mail_payee_client($email_to, $array_change, $from, $email_from);
    se_shop_mail_payee_admin($email_to, $array_change, $from, $email_from);
    return true;
}}
// ������� ������ ������
// ���������� ���������� ������� ������� �� ������ ������ ������ �����.
if (!function_exists('CancelOrder')) {
function CancelOrder($IdOrder) {
// ����� ���������� ��������� ��� �������� �� ������ ������
    //!!! ������� �������
    unset($_SESSION['mshopcart']);
	return true;
}}
// ����������� ��������� �� e-mail
if (!function_exists('se_shop_mail')) {
function se_shop_mail($mailtype, $email_to, $array_change, $from, $email_from, $lang="rus", $typpost="html") {
    /* ���������� */
    $encode='utf-8';
    $from = str_replace('�','No',$from);
    $arr_email_to = preg_split("/[\s,;]+/",$email_to);
    $email_to = '';
    foreach ($arr_email_to as $k => $v)
        $email_to .= (!empty($email_to)?', ':'').substr($v,0,strpos($v,"@")).' <'.$v.'>';
    $email_from = preg_split("/[\s,;]+/",$email_from);
    $from = str_replace('&amp;','',$from);
    $from = str_replace('&quot;','',$from);
    $from = str_replace('"','',$from);
    $from = str_replace("'",'',$from);
    $from = str_replace('&#039;','',$from);
    $from = str_replace('&lt;','',$from);
    $from = str_replace('&gt;','',$from);
    $mail_text = se_db_fetch_array(se_db_query("
        SELECT * FROM `shop_mail`
        WHERE (`lang`='".$lang."')AND(`mailtype`='".$mailtype."') LIMIT 1;"));
    if ($typpost!='html') $mail_text['letter'] = str_replace("<br>","\r\n",$mail_text['letter']);
    else $mail_text['letter'] = str_replace("\r\n","<br>",$mail_text['letter']);
    foreach ($array_change as $k => $v) {
        $mail_text['letter'] = str_replace("[".$k."]", $v, $mail_text['letter']);
        $mail_text['subject'] = str_replace("[".$k."]", $v, $mail_text['subject']);
    }
    $mail_text['subject']= str_replace('�','No',$mail_text['subject']);
    $headers = "";
    $headers .= 'Content-type: text/'.$typpost.'; charset='.$encode."\n";
    $headers .= 'From: '.$from.' <'.(!empty($email_from[0])?$email_from[0]:$email_from).">\n";
    $headers .= 'Subject: '.$mail_text['subject']."\n";
    $headers .= 'Return-path: '.$email_from[0]."\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative;\n";
    $headers .= 'Content-type: text/'.$typpost.'; charset='.$encode."\n";
    $mail_text['letter'] = str_replace('�','No',$mail_text['letter']);
    mail($email_to, $mail_text['subject'], $mail_text['letter'], $headers, '-f'.$email_from[0]);
}}
if (!function_exists('se_merchant_mail_payee_client')) {
function se_merchant_mail_payee_client($email_to, $array_change, $from, $email_from) {
    // �������� ������� ������ �� �������� ������
}}
if (!function_exists('se_merchant_mail_payee_admin')) {
function se_merchant_mail_payee_admin($email_to, $array_change, $from, $email_from) {
    // �������� ������ ������ �� �������� ������
}}
//EndLib
function module_shopmini_zpayment($razdel, $section = null)
{
   $__data = seData::getInstance();
   $_page = $__data->req->page;
   $_razdel = $__data->req->razdel;
   $_sub = $__data->req->sub;
   unset($SE);
   if ($section == null) return;
if (empty($section->params[0]->value)) $section->params[0]->value = "catalogue";
if (empty($section->params[1]->value)) $section->params[1]->value = "Номер счета на оплату ";
if (empty($section->params[2]->value)) $section->params[2]->value = "";
if (empty($section->params[3]->value)) $section->params[3]->value = "Сумма к оплате";
if (empty($section->params[4]->value)) $section->params[4]->value = "Руб";
if (empty($section->params[5]->value)) $section->params[5]->value = "Ваш e-mail";
if (empty($section->params[6]->value)) $section->params[6]->value = "Описание покупки";
if (empty($section->params[7]->value)) $section->params[7]->value = "Оплатить";
if (empty($section->params[8]->value)) $section->params[8]->value = "Форма оплаты заказа через Z-Payment";
if (empty($section->params[9]->value)) $section->params[9]->value = "Вернуться";
if (isRequest('merchant'))
{
     if (getRequest('merchant') == 'success') $__data->goSubName($section, '1');
     if (getRequest('merchant') == 'fail') $__data->goSubName($section, '2');
     if (getRequest('merchant') == 'result') $__data->goSubName($section, '3');
}
if (!empty($_SESSION['mshopcart'])) 
{
    $valuts = $section->params[4]->value;
    $incart = $_SESSION['mshopcart'];
    $shcart = array();
    $summa_order = 0;
    $count_order = 0;
    $list_order = "";
    $fl = true;
    foreach($incart as $id_cart=>$value)
    {
        if (!empty($id_cart) && $value['count'])
        {
            $summ = round( intval($value['count']) * round($value['price'], 2), 2);
            $summa_order += $summ;
            $list_order .= "Наименование: #{$id_cart} {$value['name']}\r\n";
            $list_order .= "Количество: {$value['count']}\r\n";
            $list_order .= "Цена: {$value['price']}\r\n";
            $list_order .= "Стоимость: {$summ}\r\n";
            $list_order .= "\r\n";
        }    
    }
}
if (empty($list_order)) return;
//$z_payment_url ="https://z-payment.ru/merchant.php";
$z_payment_url = '';
$IdShopZP = '';
////////////////////////////////////////////////////////////////
//		Z-PAYMENT, система приема и обработки платежей 		  //
//		All rights reserved © 2002-2007, TRANSACTOR LLC		  //
////////////////////////////////////////////////////////////////
//Файл основных переменных 
//ID магазина Z-PAYMENT
$IdShopZP = $section->params[2]->value;
//Merhant Key ключ магазина
$SecretKeyZP = '';
//Метод передачи данных на Result URL
$ResultMethod = 'POST'; //  GET
//Метод передачи данных на Success URL
$SuccessMethod = 'POST'; //  GET
//Матод передачи данных на Fail URL
$FailMethod = 'POST';  //  GET
//Адрес сайта магазина
$ShopURL = '/'.$_page .'/';
//Сумма оплаты за заказ
$AmountOrder = str_replace(',', '.', $summa_order);
//Номер заказа в магазине 
$NumberOrder = 872; // Нужно сделать
$incart = array();
if (!empty($_SESSION['USER_EMAIL']))
  $CLIENT_MAIL = $_SESSION['USER_EMAIL'];
elseif (!empty($_COOKIE['USER_EMAIL']))  
    $CLIENT_MAIL = $_COOKIE['USER_EMAIL'];
if (isRequest('USER_EMAIL'))
    $_SESSION['USER_EMAIL'] = getRequest('USER_EMAIL');
//BeginSubPages
if (($razdel != $__data->req->razdel) || empty($__data->req->sub)){
//BeginRazdel
//EndRazdel
}
else{
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==1)){
//BeginSubPage1
////////////////////////////////////////////////////////////////
//		Z-PAYMENT, система приема и обработки платежей 		  //
//		All rights reserved © 2002-2007, TRANSACTOR LLC		  //
////////////////////////////////////////////////////////////////
//Страница выполненного платежа
//Устанавливаем метод приема данных
if($SuccessMethod=='POST') $HTTP = $HTTP_POST_VARS; 
else $HTTP = $HTTP_GET_VARS;
//Преобразуем массив в переменные
foreach ($HTTP as $Key=>$Value) { $$Key = $Value; }
//Сдесь вы можете внести соответсвующие изменение в базу данных  
//Обратите внимание, что переход на Success URL не является фактом оплаты счета
//он лишь подтверждает успешное оформление счета на оплату, факт оплаты высылается
//на Result URL - result_zp.php
//EndSubPage1
} else
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==2)){
//BeginSubPage2
////////////////////////////////////////////////////////////////
//		Z-PAYMENT, система приема и обработки платежей 		  //
//		All rights reserved © 2002-2007, TRANSACTOR LLC		  //
////////////////////////////////////////////////////////////////
//Страница НЕ выполненного платежа
//Переход на эту страницу означает отказ пользователя выполнить перевод,
//отмена выписанного счета, ошибка при 
//Устанавливаем метод приема данных
if($FailMethod=='POST') $HTTP = $HTTP_POST_VARS; 
else $HTTP = $HTTP_GET_VARS;
//Преобразуем массив в переменные
if (!empty($HTTP))
    foreach ($HTTP as $Key=>$Value) { $$Key = $Value; }
//Сдесь вы можете внести соответсвующие изменение в базу данных 
//Отменить заказ, удалить корзину и пр. 
//unset($_SESSION['mshopcart']);
//EndSubPage2
} else
if(($razdel == $__data->req->razdel) && !empty($__data->req->sub) && ($__data->req->sub==3)){
//BeginSubPage3
////////////////////////////////////////////////////////////////
//		Z-PAYMENT, система приема и обработки платежей 		  //
//		All rights reserved © 2002-2007, TRANSACTOR LLC		  //
////////////////////////////////////////////////////////////////
//Скрипт обрабатывающий запросы Z-PAYMENT
//Устанавливаем метод приема данных
if($ResultMethod=='POST') $HTTP = $HTTP_POST_VARS; 
else $HTTP = $HTTP_GET_VARS;
//Преобразуем массив в переменные
foreach ($HTTP as $Key=>$Value) { $$Key = $Value; }
//Проверяем номер магазина 
if($LMI_PAYEE_PURSE!=$IdShopZP) {
	die("ERR: Id магазина не соответсвует настройкам сайта!");
}
//Проверяем номер заказа 
if($LMI_PAYMENT_NO!=$NumberOrder) {
	die("ERR: Номер счета не соответсвует заказу!");
}
//Настоятельно рекомендуем сверять сумму оплаты с суммой вашего заказа из БД
$RealAmountOrder = GetAmountOrder($LMI_PAYMENT_NO);
if($RealAmountOrder!=$LMI_PAYMENT_AMOUNT) {
	die("ERR: Сумма оплаты не соответсвует сумме заказа!");
}
//Предварительный запрос на проведение платежа?
if($LMI_PREREQUEST==1) {
// Если в настройках магазина https://z-payment.ru/shops.php
// Задана опция "Отправлять предварительный запрос перед оплатой на Result URL"
// Перед оплатой этот скрипт будет получать запрос на разрешение оплаты, если платеж прошел
// проверку требуется вернуть YES, любое другое сообщение будет принято системой как запрет 
// оплачивать счет
// В этом месте вы можете проверить наличие товара, курсы валют и другую информацию о заказе
// зарезервировать товар на складе, перед тем как разрешите покупателю совершить оплату. 
// Здесь же можно изменить статус заказа на "Ожидает оплаты" 
// Не забывайте проверить статус заказа на предмет ОТМЕНЫ или ОПЛАТЫ
// $CLIENT_MAIL  -  емаил покупателя 
// $LMI_PAYER_WM - кошелек покупателя или его емаил 
// $LMI_MODE = 0 - рабочий режим
// $DESC_PAY - Описание товара
// $USER_VALUE1, $USER_VALUE2, ... Остальные переменные переданные продавцом 
// в форме запроса платежа
	//Разрешаем оплату
	echo 'YES';
} else { //Уведомление об оплате
// Если Result URL обеспечивает безопасное соединение SSL и выставлена 
// настройка "Отправлять ключ магазина, если Result URL обеспечивает безопасность"
// сверяем ключи, этого достаточно при условии, что вы задали 
	if(isset($LMI_SECRET_KEY)) {
		// Если ключ совпадает, занчит все ОК, проводим заказ 
		if($LMI_SECRET_KEY==$SecretKeyZP) {
			//Подтверждение оплаты заказа 
			$Result = ConfirmOrder($LMI_PAYMENT_NO);
			//Все прошло успешно
			if($Result) echo 'YES';
		} else {
			//Отмена заказа
			CancelOrder($LMI_PAYMENT_NO);
		}
	} else {
		// Ключ не был передан, требуется проверить контрольный хеш запроса 
		//Расчет контрольного хеша из полученных переменных и Ключа мерчанта
		$CalcHash = md5($LMI_PAYEE_PURSE.$LMI_PAYMENT_AMOUNT.$LMI_PAYMENT_NO.$LMI_MODE.$LMI_SYS_INVS_NO.$LMI_SYS_TRANS_NO.$LMI_SYS_TRANS_DATE.$SecretKeyZP.$LMI_PAYER_PURSE.$LMI_PAYER_WM);
		//Сравниваем значение расчетного хеша с полученным 
		if($LMI_HASH == strtoupper($CalcHash)) {
			//Подтверждение оплаты заказа 
			$Result = ConfirmOrder($LMI_PAYMENT_NO);
			//Все прошло успешно
			if($Result) echo 'YES';
		} else {
			//Отмена заказа
			CancelOrder($LMI_PAYMENT_NO);
		}
	}
}
//EndSubPage3
}
}
//EndSubPages
$__module_content['form'] = "
<!-- =============== START CONTENT =============== -->
<div class=\"content\" id=\"cont_shzpaym\" [part.style]>
<noempty:part.title><h3 class=\"contentTitle\"[part.style_title]><span class=\"contentTitleTxt\">[part.title]</span> </h3> </noempty>
<noempty:part.image><a href=\"[part.image]\" target=\"_blank\"><img alt=\"[part.image_alt]\" border=\"0\" class=\"contentImage[part.style_image]\" src=\"[part.image]\"></a> </noempty>
<noempty:part.text><div class=\"contentText[part.style_text]\">[part.text]</div> </noempty>
<form style=\"margin:0px;\" id=\"pay\" name=\"pay\" method=\"post\" action=\"{$z_payment_url}\">
    <table cellpadding=\"0\" cellspacing=\"0\" class=\"tableTable tablePayment\">
    <tbody class=\"tableBody\">
    <tr class=\"tableRow\" id=\"tableHeader\" vAlign=\"top\">
        <td colspan=\"2\" class=\"HeadTable\">{$section->params[8]->value}
        <input type=\"hidden\" name=\"LMI_PAYMENT_DESC\" value=\"{$list_order}\"></td>
    </tr>
    <tr>
        <td class=\"TitleNameTable\">{$section->params[1]->value} </td>
        <td class=\"ValueTable\">
        <input name=\"LMI_PAYMENT_NO\" type=\"hidden\" value=\"{$NumberOrder}\">{$NumberOrder}</td>
    </tr>
    <tr>
        <td class=\"TitleNameTable\">{$section->params[3]->value} </td>
        <td class=\"ValueTable\"><input name=\"LMI_PAYMENT_AMOUNT\" type=\"hidden\" value=\"{$AmountOrder}\">{$AmountOrder} {$section->params[4]->value}
        </td>
    </tr>
    <tr>
        <td class=\"TitleNameTable\">{$section->params[5]->value} </td>
        <td class=\"ValueTable\"><input class=\"inp\" name=\"CLIENT_MAIL\" type=\"text\" value=\"{$CLIENT_MAIL}\" size=\"25\" maxlength=\"128\"></td>
    </tr>
    <tr>
        <td colspan=\"2\" class=\"buttonCell\"><input class=\"buttonSend payee\" type=\"submit\" value=\"{$section->params[7]->value}\">
        <input name=\"LMI_PAYEE_PURSE\" type=\"hidden\" value=\"{$IdShopZP}\">
        </td>
    </tr>
    </tbody>
    </table>
</form>
</div> 
<!-- =============== END CONTENT ============= -->";
$__module_subpage[1]['form'] = "<table width=\"90%\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#99FF99\" class=\"TableCheckBgColor\">
  <tr class=\"Text\">
    <td><p class=\"Text\">Инструкции покупателю:</p>
        <p>Ваш платеж успешно принят к обработке системой </p></td>
  </tr>
  <tr>
    <td align=\"center\"><form id=\"succes_pay\" name=\"succes_pay\" method=\"post\" action=\"php echo $ShopURL; \">
              <table width=\"450\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" class=\"TableFonSystems\">
                <tr>
                  <td colspan=\"2\" class=\"HeadTable\"><p><strong>Форма выполненного платежа</strong><strong> </strong></p></td>
                </tr>
                <tr>
                  <td class=\"TitleNameTable\">Номер счета магазина</td>
                  <td class=\"ValueTable\">php echo $LMI_PAYMENT_NO; </td>
                </tr>                <tr>
                  <td class=\"TitleNameTable\">Номер платежа Z-PAYMENT</td>
                  <td class=\"ValueTable\">php echo $LMI_SYS_INVS_NO; </td>
                </tr>
                <tr>
                  <td class=\"TitleNameTable\">Номер счета Z-PAYMENT</td>
                  <td class=\"ValueTable\">php echo $LMI_SYS_TRANS_NO; </td>
                </tr>
                <tr>
                  <td class=\"TitleNameTable\">Дата выполнения операции </td>
                  <td class=\"ValueTable\">php echo $LMI_SYS_TRANS_DATE; </td>
                </tr>
                <tr>
                  <td class=\"TitleNameTable\">Переменная 1</td>
                  <td class=\"ValueTable\">php echo $USER_VALUE1; </td>
                </tr>
                <tr>
                  <td class=\"TitleNameTable\">Переменная 2</td>
                  <td class=\"ValueTable\">php echo $USER_VALUE2; </td>
                </tr>
                <tr class=\"TextSystemTable1\">
                  <td colspan=\"2\">
				    <input type=\"submit\" value=\"Вернуться\"></td>
                  </tr>
              </table>
            </form></td>
  </tr>
</table>
 
";
$__module_subpage[2]['form'] = "<div class=\"content merchant_fail\">
<h3 class=\"contentTitle\"><span class=\"contentTitleTxt\">Ваш платеж НЕ принят системой!</span> </h3><form id=\"fail_pay\" name=\"fail_pay\" method=\"post\" action=\"{$ShopURL}\">
<table width=\"450\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" class=\"tableTable\">
                <tr>
                  <td colspan=\"2\" class=\"HeadTable\"><p><strong>Форма НЕвыполненного платежа</strong><strong> </strong></p></td>
                </tr>
                <tr>
                  <td class=\"TitleNameTable\">Номер счета магазина</td>
                  <td class=\"ValueTable\">{$LMI_PAYMENT_NO}</td>
                </tr>
                <tr>
                  <td class=\"TitleNameTable\">Номер платежа Z-PAYMENT</td>
                  <td class=\"ValueTable\">{$LMI_SYS_INVS_NO}</td>
                </tr>
                <tr>
                  <td class=\"TitleNameTable\">Номер счета Z-PAYMENT</td>
                  <td class=\"ValueTable\">{$LMI_SYS_TRANS_NO}</td>
                </tr>
                <tr>
                  <td class=\"TitleNameTable\">Дата выполнения операции </td>
                  <td class=\"ValueTable\">{$LMI_SYS_TRANS_DATE}</td>
                </tr>
                <tr class=\"TextSystemTable1\">
                  <td colspan=\"2\"><input class=\"buttonSend\" name=\"submit\" type=\"submit\" value=\"{$section->params[9]->value}\"></td>
                </tr>
</table>
</form>
</div>
";
$__module_subpage[3]['form'] = "<div class=\"content\" id=\"cont_tests3\"></DIV>
";
return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
};