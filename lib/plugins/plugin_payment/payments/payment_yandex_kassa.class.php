<?php
require_once dirname(__FILE__)."/basePayment.class.php";

/**
 * Плагин платежной системы Yandex
 */

use YandexCheckout\Client;

class payment_yandex_kassa extends basePayment{

    private $client;

    public function __construct($order_id = 0, $payment_id = 0)
    {
        parent::__construct($order_id, $payment_id);
        $client = new Client();
    }

    public function setVars()
    {
        return array('shopId'=>'ID магазина', 'scid'=>'Номер витрины','wmpassw'=>'секретный код');
    }

    public function startform()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        return $macros->execute($this->startform);
    }

    public function blank($pagename)
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $this->client->setAuth($macros->execute('[PAYMENT.SHOPID]'), $macros->execute('[PAYMENT.SCID]'));
        $summ = $macros->execute('[ORDER.SUMMA]');
        $shopSuccessURL = $this->getPathPayment('[MERCHANT_SUCCESS]', $pagename);
        $order_id = $macros->execute("[ORDER.ID]");
        $userEmail = $macros->execute('[USER.USEREMAIL]');
        $userPhone = str_replace(array('+7', ' ', ')', '(', '-'), array('7', '', '','',''), $macros->execute("[USER.PHONE]"));
        if (strpos($userPhone, '8')===0)
            $userPhone = substr($userPhone, 1, strlen($userPhone)-1);

        $idempotenceKey = uniqid('', true);
        $response = $this->client->createPayment(
            array(
                'amount' => array(
                    'value' => $summ,
                    'currency' => 'RUB',
                ),
                'payment_method_data' => array(
                    'type' => 'bank_card',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => $shopSuccessURL,
                ),
                'receipt' => array(
                    'customer' => array(
                        'email' => $userEmail,
                        'phone' => $userPhone
                    ),
                    'email' => $userEmail,
                    'phone' => $userPhone
                ),
                'description' => 'Заказ №'.$order_id,
            ),
            $idempotenceKey
        );

        //get confirmation url
        $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();
        print_r($confirmationUrl);




//        $url = ($this->test) ? 'https://demomoney.yandex.ru/eshop.xml' : 'https://money.yandex.ru/eshop.xml';
//        //$url = "https://demomoney.yandex.ru/eshop.xml";
//
//        $shopId = $macros->execute('[PAYMENT.SHOPID]');
//        $scid = $macros->execute('[PAYMENT.SCID]');
//        $customerNumber = $macros->execute('[USER.USEREMAIL]');
//        $customerEmail = $macros->execute('[USER.USEREMAIL]');
//        $shopSuccessURL = $this->getPathPayment('[MERCHANT_SUCCESS]', $pagename);
//        $shopFailURL = $this->getPathPayment('[MERCHANT_FAIL]', $pagename);
    }

    public function result()
    {
        define('SANDBOX', 1);
        $post = $_POST;
        if (SANDBOX == true) {
            $this->logs("I get POST request: <pre>".print_r($post,1)."</pre>");
            $this->logs("I get GET request: <pre>".print_r($_GET,1)."</pre>");
        }
       if ($_POST['action'] == 'checkOrder'){
            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <checkOrderResponse performedDatetime=\"". date("c")."\"
            code=\"0\" invoiceId=\"". $_POST['invoiceId']."\" 
            shopId=\"".$_POST['shopId']."\"/>\n";
            $this->logs("", true);
            exit;
       } elseif ($_POST['action'] == 'paymentAviso') {
        $this->order_id = intval($post['orderNumber']);
        $res = $this->getPaymentLog();
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $shopPassword = $macros->execute('[PAYMENT.WMPASSW]');
        $hash = strtoupper(md5($_POST['action'].';'.$_POST['orderSumAmount'].';'.$_POST['orderSumCurrencyPaycash'].';'.$_POST['orderSumBankPaycash'].';'.$_POST['shopId'].';'.$_POST['invoiceId'].';'.$_POST['customerNumber'].';'.$shopPassword));

        if (SANDBOX == true) {
            $this->logs($_POST['md5'].'='.$hash);
            $this->logs("I get sha1_hash: <pre>".$post['md5']."</pre>");
        }

        if ($post['invoiceId'] && ($post['md5'] == $hash)) {
            $this->activate($this->order_id);
            echo '<?xml version="1.0" encoding="UTF-8"?>
            <paymentAvisoResponse
                performedDatetime ="'.date('c').'"
                code="0" invoiceId="'.$_POST['invoiceId'].'" 
                shopId="'.$_POST['shopId'].'"/>
            ';
            if (SANDBOX == true) {
                $this->logs("Order is payed");
                $this->logs("",true);
            }
        } else {
            echo '<?xml version="1.0" encoding="UTF-8"?>
            <paymentAvisoResponse
                performedDatetime ="'.date('c').'"
                code="1" invoiceId="'.$_POST['invoiceId'].'" 
                shopId="'.$_POST['shopId'].'"/>
            ';
            if (SANDBOX == true) {
                $this->logs("I have a bloblem: Transaction - ".$post['action'].' SIGN - '.$post['md5'].' == '.$hash.' SUM - '.$res['summ'].' == '.$post['orderSumAmount']);
                $this->logs("",true);
            }
        }
      } else {
            if (SANDBOX == true) {
                $this->logs("",true);
            }
      }    
    }

    public function success()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $this->success = '<h4 class="contentTitle">Оплата проведена успешно</h4><p>Ваш заказ № '.$this->order_id.' оплачен</p>';
        return $macros->execute($this->success);
    }

    public function fail()
    {
        $macros = new plugin_macros(0, $this->order_id, $this->payment_id);
        $this->fail = '<h4 class="contentTitle">Ошибка в проведении платежа</h4>';
        return $macros->execute($this->fail);
    }

    private function logs($text, $toFile = false) {
        $this->logText = $this->logText.$text."\r\n <br>";
        if ($toFile == true) {
            if (!is_dir(getcwd().'/system/logs/yandexshop'))
                mkdir(getcwd().'/system/logs/yandexshop');
            $date = date('c');
            file_put_contents(getcwd().'/system/logs/yandexshop/'.$date.'.txt', $this->logText);
        }
    }
}