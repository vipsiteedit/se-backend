<?php
require_once dirname(__FILE__)."/basePayment.class.php"; 

/**
 * PHP class to send Yandex.Money HTTP notifications
 * @author sanmai
 * @license MIT
 * 
 * Usage:
 * 
 * $notification = new YandexNotification();
 * // Set notification properties as you need
 * // By default date is set to now and amount is initialized with a random float value
 * $notification->codepro = false;
 * $notification->label = 53243;
 * $notification->dispatch('https://www.darom.jp/personal/payments/paypal', 'S4KDHyTSvH4AuimHP0N6ibsN');
 */
class YandexNotification
{
	/** @var string Тип уведомления, фиксированное значение p2p-incoming */
	public $notification_type = 'p2p-incoming';
	/** @var string Идентификатор операции в истории счета получателя */
	public $operation_id = 'test-notification';
	/** @var amount Сумма операции */
	public $amount = 0;
	/** @var string Код валюты счета пользователя. Всегда 643 (рубль РФ согласно ISO 4217) */
	public $currency = 643;
	/** @var datetime Дата и время совершения перевода в формате RFC3339 */
	public $datetime = '';
	/** @var string Номер счета отправителя перевода */
	public $sender = '41001000040';
	/** @var boolean Перевод защищен кодом протекции */
	public $codepro = false;
	/** @var string Дополнительные данные, например номер корзины */
	public $label = '';
	/** @var string SHA-1 hash параметров уведомления */
	public $sha1_hash = '';
	/** @var boolean Флаг означает, что уведомление тестовое (по умолчанию параметр отсутствует) */
	public $test_notification = true;
	
	public function __construct()
	{
		$this->datetime = date(DateTime::RFC3339);
		$this->amount = rand(1, 1000) + rand(1, 99)/100; 
	}
	
	/** @return YandexNotification */
	private function hash($notification_secret)
	{
		if (is_bool($this->codepro)) {
			$this->codepro = $this->codepro ? 'true' : 'false';	
		}
		if (is_bool($this->test_notification)) {
			$this->test_notification = $this->test_notification ? 'true' : 'false';
		}
		$dataToSign = array();
		foreach (array(
			'notification_type', 'operation_id', 'amount', 
			'currency', 'datetime', 'sender', 'codepro', 
			'notification_secret', 'label'
		) as $key) {
			$dataToSign[] = $key == 'notification_secret' ? $notification_secret : $this->{$key};
		}
		$this->sha1_hash = sha1(implode('&', $dataToSign));
		return $this;
	}
	
	/**
	 * Signs and sends a notification, outputs all response headers and actual response 
	 * @param string $url
	 * @param string $notification_secret
	 */
	public function dispatch($url, $notification_secret)
	{
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => false,
			CURLOPT_VERBOSE => false,
			CURLOPT_POSTFIELDS => http_build_query(get_object_vars($this->hash($notification_secret))),
			CURLOPT_CONNECTTIMEOUT => 60,
			CURLOPT_FAILONERROR => false,
			CURLOPT_HEADER => true,
		));
		
		echo curl_exec($ch);
 
		if (curl_error($ch)) {
			var_dump(curl_error($ch), curl_errno($ch));	
		}
		curl_close($ch);
	}
}

/**
 * @author Sergey Shchelkonogov
 * @copyright 2013
 * 
 * Плагин платежных систем
 */
 
class payment_yandex extends basePayment{
  private $client_id = '';
  private function postData($url, $postdata)
  {
		$curl = curl_init($url);
		curl_setopt ($curl, CURLOPT_HEADER, 0);
		curl_setopt ($curl, CURLOPT_POST, 1);
		curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
		return curl_exec ($curl);  
  }
  
  public function setVars()
  {
		return array('ym_ident'=>'Код магазина Yandex', 'ym_secret'=>'Секретный ключ');
  }   

  public function startform($payment_id, $text) 
  {
		$macros = new plugin_macros(0, $this->order_id, $payment_id);
		return $macros->execute($text);
  }

  public function blank($pagename) 
  {
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$strpost = 'client_id=[PAYMENT.YM_IDENT]&response_type=code&redirect_uri=[MERCHANT_RESULT]&scope=account-info operation-history';
	    $strpost = str_replace(array('%3D','%26'), array('=','&'), rawurlencode($macros->execute($this->getPathPayment($strpost, $pagename))));
		header('Location: https://sp-money.yandex.ru/oauth/authorize?'.$strpost);
		
		//$this->postData('https://sp-money.yandex.ru/oauth/authorize', $strpost);
		//header('Location: https://sp-money.yandex.ru/oauth/authorize');
		exit;
  }
  
  public function result() 
  {
   if (!$this->payment_id) return;
	  if (isset($_GET['code'])) {
			// Получение токина
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		 $params = array(
            'grant_type'    => 'authorization_code',
            'client_id'     => $macros->execute('[PAYMENT.YM_IDENT]'),
            'code'          => $_GET['code'],
			'client_secret' => $macros->execute('[PAYMENT.YM_SECRET]')
			//'redirect_uri'  => $macros->execute('[MERCHANT_RESULT]')
        );
		print_r($params);
		$response = self::get('https://sp-money.yandex.ru/oauth/token', $params, 'POST');
        $result = @json_decode($response, true);
		print_r($result);
		//echo $result = $this->postData('https://sp-money.yandex.ru//oauth/token', $strpost);
	  }
	  
		exit;
  }

  public function success()
  {
		$this->order_id = $_GET['wsb_order_num'];
		$transaction_id = $_GET['wsb_tid'];
		// API Request (webpay.by)
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$postdata = '*API=&API_XML_REQUEST='.urlencode($macros->execute('
		<?xml version="1.0" encoding="ISO-8859-1" ?>
		<wsb_api_request>
		<command>get_transaction</command>
		<authorization>
		<username>[PAYMENT.WP_LOGIN]</username>
		<password>[PAYMENT.WP_PASSWORD]</password>
		</authorization>
		<fields>
		<transaction_id>'.$transaction_id.'</transaction_id>
		</fields>
		</wsb_api_request>'));
		$url = ($this->test) ? "https://sandbox.webpay.by" :  "https://billing.webpay.by";
		$curl = curl_init($url);
		curl_setopt ($curl, CURLOPT_HEADER, 0);
		curl_setopt ($curl, CURLOPT_POST, 1);
		curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
		$response = curl_exec ($curl);
		curl_close ($curl);
		if (!empty($response)) {
			 $xmlDoc = new SimpleXMLElement($response);
			 if ($xmlDoc->fields->transaction_id == $transaction_id) { 
				if ($xmlDoc->status == 'success') {
					$this->activate($this->order_id, $xmlDoc->fields->amount, $xmlDoc->fields->currency_id, $xmlDoc->fields->rrn, $macros->execute('[PAYMENT.WEBPAY]'), $this->name_payment);//print_r($xmlDoc);
					$this->success = str_replace(array('[PAY.PAY_NUM]','[PAY.TRANS_NUM]','[PAY.TRANS_DATE]'), array($xmlDoc->fields->order_id,$xmlDoc->fields->transaction_id, date('Y-m-d H:i:s', intval($xmlDoc->fields->batch_timestamp))), $this->success);
				}
			 }
		}
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		$this->success = $macros->execute($this->success);  
		return $this->success;
		
  }

  public function fail() 
  {
		$macros = new plugin_macros(0, $this->order_id, $this->payment_id);
		return $macros->execute($this->fail);
  }
}