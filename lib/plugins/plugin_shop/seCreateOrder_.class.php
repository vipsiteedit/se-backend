<?php

/**
 * Класс создания заказа
 * 
 */

class seCreateOrder
{
	private $incart = array();
	private $indelivery = array();
	private $lang = 'rus';
	private $basecurr = 'RUR';
	private $email;
	private $param = array('Код', 'Наименование','Цена','Скидка','Кол-во','Сумма');

	private $user = null;
	private $user_id;
	private $username = '';
	private $userdiscount = 0;
	private $usergroup = 1;
	private $useremail = '';
	private $charset = 'Win-1251';
	private $encode = 'KOI8-R';
	private $seller = null;
	private $goodslist = '';
  	private $summ_discount = 0; 
	private $summ_delivery = 0; 
	private $summ_order = 0; 
	private $show_summ_order = ''; 
	private $show_summ_discount = ''; 
	private $show_summ_delivery = ''; 
	private $show_summ_all = ''; 

	/**
	 * @param integer $user_id	ID пользователя
	 * @param array	$incart		Массив товаров	array('price_id'=>1,'count'=>1,'name'=>'','action'=>'1000011122:version=6')
	 **/
  	function __construct($user_id, $incart = array(), $lang = '')
  	{
  		if (empty($lang))
  		    $this->lang = se_getlang();
  		else $this->lang = $lang;
		
		$this->incart = $incart;
		$this->user_id = $user_id;
		// ### Данные о клиенте

		$this->seller = new seTable();
		$this->seller->from('main', 'm')
			->where("lang='?'", $this->lang)
			->fetchOne();
		$this->basecurr = (($this->seller->basecurr != '')? $this->seller->basecurr: 'RUR');
		$this->user = new seUser();
		$this->user->find($user_id);
		
		$this->username = trim($this->user->last_name . ' ' . $this->user->first_name . ' ' . $this->user->sec_name);
		$this->userdiscount = $this->user->discount;
		$this->usergroup = $this->user->a_group;
		$this->useremail = (!empty($email)? $email: $this->user->email);
  	}

	
	/**
	 * Проверка условий
	 */
	private function ifparam($data1, $data2, $logic)
	{
		switch ($logic)
		{
			case '>=' : $res = ($data1 >= $data2); break; 
			case '<=' : $res = ($data1 <= $data2); break; 
			case '==' : $res = ($data1 == $data2); break; 
			case '<>' : $res = ($data1 != $data2); break; 
			default : $res = 1; 
		}
		return $res; 
	}

	public function discount_order($shopprice, $sum_cart = 0, $summa = 0, $count_goods = 1)
	{
		$discountproc = 0;
		$weeks = array('7', '1', '2', '3', '4', '5', '6'); 
		$fl = 0; 
		$discount = $shopprice->getDiscount();
		if (!empty($discount))
		{
			if ($discount->type == 'g')
			{
				$summa = $sum_cart; 
			}
				
			if (!empty($discount->if_date1) or !empty($discount->if_date2))
    		{
      			$fl = ((!empty($discount->if_date1) or !empty($discount->if_date2)) 
		  		&& $this->ifparam(date('Y-m-d'), $discount->date1, $discount->if_date1) 
		  		&& $this->ifparam(date('Y-m-d'), $discount->date2, $discount->if_date2)); 
			}
    			
			if (!empty($discount->if_summ1) or !empty($discount->if_summ2))
    		{
      			if ($discount->summ_type == 1)
      			{ 
      				// Общие заказы
        			$orders = new seShopOrder(); 
					$summa = $orders->getUserSumm(seUserId()); 
					$summa = (!empty($summ[0]) ? $summ[0] : 0) + $summa; 
				}

   				$fl = ($this->ifparam($summa, $discount->summ1, $discount->if_summ1) 
		  		&& $this->ifparam($summa, $discount->summ2, $discount->if_summ2)); 
			}

   			if (!empty($discount->if_count1) or !empty($discount->if_count2))
			{
   				$fl = ($this->ifparam($count_goods, $discount->count1, $discount->if_cont1) 
			  	&& $this->ifparam($count_goods, $discount->count2, $discount->if_count2)); 
			}


    		$week = $discount->week; 
			if (isset($week[date('w')]))
			{
				$fl = $fl * ($week[date('w')] == 1); 
			}

			if ($fl)
			{
				$discountproc = $discount->percent; 
			}
		}
		
		if ($discountproc < $this->userdiscount) 
		{   
		   	$discountproc = $this->userdiscount;
        }
		
		if ($shopprice->max_discount > 0 && $shopprice->max_discount < $discountproc)
		{
        	$discountproc = $shopprice->max_discount; 
		} 
		return $discountproc; 
	}

	/**
	 * Специальное предложение
	 */
	public function special_order($shopprice)
	{
		$discountproc = 0;
		$discount = $shopprice->getSpecial();
		if (empty($discount)) return 0;

		if ($discount->expires_date > date('Y-m-d'))
		{
			$discountproc = $discount->newproc; 
		}

		
		if ($shopprice->max_discount > 0 && $shopprice->max_discount < $discountproc)
		{
        	$discountproc = $shopprice->max_discount; 
		} 

		return $discountproc; 
	}
	
	public function getItemList()
	{
		$data = array();	
		$shopprice = new seShopPrice();
		$sum_cart = 0;
		foreach ($this->incart as $item)
  		{
			$price_id = $item['price_id'];
			$count = $item['count'];
			$shopprice->select();
			$shopprice->where('id=?', $price_id)->andWhere("enabled='Y'")->fetchOne();
			$sum_cart += se_MoneyConvert($shopprice->price * $count, $shopprice->curr, $this->basecurr);
		}
		unset($shopprice);
		
		$shopprice = new seShopPrice();
		foreach ($this->incart as $item)
  		{
			//print_r($item);
		
			$price_id = $item['price_id'];
			$count = $item['count'];
			if ($price_id)
			{
			    $shopprice->select();
			    $shopprice->where('id=?', $price_id)->fetchOne();
			//echo "!!!!!!!!!!!!!!!!!!!!!!!!! ".$price_id;

			    if ($shopprice->id)
        		    {
			
				$summ = se_MoneyConvert($shopprice->price * $count, $shopprice->curr, $this->basecurr);
        			$bonus = se_MoneyConvert($shopprice->bonus, $shopprice->curr, $this->basecurr);
				
				$specialproc = $this->special_order($shopprice);

				if ($specialproc == 0) 
				{
					$discountproc = $this->discount_order($shopprice, $sum_cart, $summ, $count);
        			} 
				else 
				{
					$discountproc = $specialproc;
				}
				// Рассчет скидок на товар вычисляется из бонусной стоимости
	
				$discount = round($discountproc/100 * $bonus, 2);
				
				if (empty($item['action']))
				{ 
					$item['name'] = (empty($item['name']) ? $shopprice->name: $item['name']);
				}
				else
				{
					// Если заказ определяется по типу
  					list($action_serial,) = explode(":", $item['action']); 
					if (empty($item['name']))
					{
						$item['name'] = $shopprice->name; 
					}	
					$item['name'] .= ' :: ' . $action_serial; 
				}
				if (isset($item['action'])) $action = $item['action'];
				else $action = '';
				$data[]=array(
					'price_id'=>$price_id, 
					'code'=>$shopprice->article,
					'discount'=>$discount,
					'count'=>$count,
					'action'=>$action,
					'name'=>$item['name'],
					'price'=>se_MoneyConvert($shopprice->price, $shopprice->curr, $this->basecurr));

			    }
			} else
			{
				$data[]=array(
					'price_id'=>'null', 
					'code'=>$item['code'],
					'discount'=>$item['discount'],
					'count'=>$count,
					'action'=>$item['action'],
					'name'=>$item['name'],
					'price'=>$item['price']);
			}
		}
		
		//print_r($data);
		return $data;
	}
	

	/**
	 * Формирование заказа
	 * @param array	$indelivery	Параметры доставки ([id], [phone],[calltime],[address],[postindex])
	 * @param string $email		Email пользователя, куда отправлять заказ
	 * @param array	$param		Список заголовков  array('Код', 'Наименование','Цена','Скидка','Кол-во','Сумма')
	 */
	public function execute($indelivery = array(), $email = '', $param = array())
	{

		//$mass_order[][id],[article],[name],[valuta],[count],[price],[skidka];
  		//$mass_delivery[type], [phone],[calltime],[address],[postindex];
		//$action=   (serial;host=50)
		// next = заказ продление программы;
		// host = заказ тарифный план хостинга
		// version = заказ новой версии

  		// Формирование данных для отображения заказа
		if (!empty($parametr))
			$this->param = $param;
		
		$this->indelivery = $indelivery;
		if (!empty($this->incart))
		{
  			$this->summ_discount = 0; 
			$this->summ_delivery = 0; 
			$this->summ_order = 0; 
			$this->goodslist = ''; 

  			// Создание заказа
			$this->summ_delivery = 0.00; 
			if (!empty($this->indelivery['id']))
			{
  					$delivery = new seTable();
  					$delivery->from('shop_deliverytype', 'dt')->find($this->indelivery['id']);

					$this->summ_delivery = se_MoneyConvert($delivery->price, $delivery->curr, $this->basecurr); 
			}
  			$order = new seShopOrder();
  			$order->user_id = $this->user_id;
  			$order->date_order = date('Y-m-d');
  			$order->discount = 0;
  			$order->curr = $this->basecurr;
  			$order->status = 'N';
  			$order->delivery_payee = $this->summ_delivery;
  			if (isset($this->indelivery['id']))
  			$order->delivery_type = $this->indelivery['id'];
  			$order->delivery_status = 'N';
  			$order_id = $order->save();
  
  			$this->order_id = $order_id;
			if ($order_id > 0)
  			{
		  		// Доставка
				if (!empty($this->indelivery))
				{
		    		$delivery = new seTable();
		    		$delivery->from('shop_delivery', 'sd');
		  			$delivery->id_order = $order_id;
					$delivery->telnumber = $this->indelivery['phone'];
					$delivery->email = $this->email;
					$delivery->calltime = $this->indelivery['calltime'];
					$delivery->address = $this->indelivery['address'];
					$delivery->postindex = $this->indelivery['postindex'];
		    		$delivery->save();
		  		}
		    	// позиции товаров
		  		$goods = $order->getGoods();
			
				$itemlist = $this->getItemList();
				foreach ($itemlist as $item)

					// Добавляем позиции товаров в заказ
					$goods->insert();
					$goods->order_id = $order_id;
  					$goods->price_id = $item['price_id'];
					$goods->discount = $item['discount'];
  					$goods->code = $item['code'];
  					$goods->count = $item['count'];
					$goods->nameitem = $item['name'];
					$goods->action = $item['action']; 					
					$goods->price = $item['price'];
  					$goods->save();

					$summ_price = ($item['price'] - $item['discount']) * $item['count']; 

					$this->goodslist .= '
      				<tr vAlign=middle>
        				<td width=200>' . $item['code'] . '&nbsp;</td>
        				<td>' . $item['name'] . '&nbsp;</td>
        				<td>' . se_formatNumber($item['price']) . '&nbsp;</td>
        				<td>' . se_formatNumber($item['discount']) . '&nbsp;</td>
        				<td>' . $item['count'] . '&nbsp;</td>
        				<td>' . se_formatNumber($summ_price) . '&nbsp;</td>
      				</tr>'; 
	  				$this->summ_discount += $item['discount'] * $item['count']; 
					$this->summ_order += $item['price'] * $item['count']; 
		  	}

			$this->summ_all = floatval($this->summ_order - $this->summ_discount + $this->summ_delivery); 
		
			$this->show_summ_order = se_formatMoney($this->summ_order, $this->basecurr); 
			$this->show_summ_discount = se_formatMoney($this->summ_discount, $this->basecurr); 
			$this->show_summ_delivery = se_formatMoney($this->summ_delivery, $this->basecurr); 
			$this->show_summ_all = se_formatMoney($this->summ_all, $this->basecurr); 


			$this->setShopAccount($order_id);
  			$this->setShopContract($order_id);
  			$vars = $this->mailtemplate();
  			
  			// письмо клиенту
  			$this->sendmail('orderuser', $vars);
			// Письмо Админу
  			$this->sendmail('orderadm', $vars);
			return $order_id;
		}
  	}	

	private function setShopAccount($order_id)
	{
		$table = new seShopAccount();
		$max = $table->maxAccount();	
		$table->order_id = $order_id;
		$table->account = $max + 1;
		$table->date_order = date('Y-m-d');
		$table->save();
	}

	private function setShopContract($order_id)
	{
		$table = new seShopContract();
		$max = $table->maxNumber();
		$table->user_id = $this->user_id;	
		$table->order_id = $order_id;
		$table->number = $max + 1;
		$table->date_order = date('Y-m-d');
		$table->save();
	}
	
	private function mailtemplate()
	{
	  // Создание шаблона письма
 		$mail['send_latter']['THISNAMESITE'] = $_SERVER['HTTP_HOST']; 
		$mail['send_latter']['CURDATE'] = date("d.m.Y H:i:s"); 
		$mail['send_latter']['NAMECLIENT'] = $this->username; 
		$mail['send_latter']['SHOP_ORDER_NUM'] = sprintf("%06u", $this->order_id); 
		$mail['send_latter']['SHOP_ORDER_VALUE_LIST'] = '
			<table cellSpacing="1" cellPadding="3" border="0" width="100%">
  			<tr class="tableRow" id="tableHeader" vAlign="middle">
    			<td class="cartorder_art" width="50">' . $this->param[0] . '</td>
    			<td class="cartorder_name">' . $this->param[1] . '</td>
    			<td class="cartorder_price" width="50">' . $this->param[2] . '</td>
    			<td class="cartorder_discount" width="50">' . $this->param[3] . '</td>
    			<td class="cartorder_cn" width="50">' . $this->param[4] . '</td>
    			<td class="cartorder_summ" width="50">' . $this->param[5] . '</td>
  			</tr>
  			' . $this->goodslist . '
			</table>'; 
		$mail['send_latter']['SHOP_ORDER_SUMM'] = se_formatMoney($this->summ_order - $this->summ_discount, $this->basecurr); 
 		$mail['send_latter']['SHOP_ORDER_DEVILERY'] = $this->show_summ_delivery; 
 		$mail['send_latter']['SHOP_ORDER_TOTAL'] = $this->show_summ_all; 
 		$mail['send_latter']['SHOP_ORDER_DISCOUNT'] = $this->show_summ_discount; 
  		$mail['send_latter']['ADMIN_MAIL_SUPPORT'] = (empty($this->seller->esales)?$this->seller->esupport: $this->seller->esales); 
  		$mail['send_latter']['ADMIN_COMPANY'] = $this->seller->company; 
		return $mail;
	}

  	// Отправление сообщение на e-mail
  	private function sendmail($mailtype, $vars, $typpost = "html")
  	{
		$from = $vars['send_latter']['ADMIN_COMPANY'];
		$email_from = $vars['send_latter']['ADMIN_MAIL_SUPPORT'];
		$array_change = $vars['send_latter'];
  		if ($mailtype == 'orderuser')
		{ 
			$email_to = $this->email;
		}
		else
		{
  			// письмо админу
			$email_to = $email_from;
		}
    	
	/* получатели */
    	$from = convert_cyr_string(str_replace('№', 'No', $from), $this->charset, $this->encode);

    	$arr_email_to = preg_split("/[\s,;]+/", $email_to);
    	$email_to = '';
    	foreach ($arr_email_to as $k => $v)
    	{
      		$email_to .= (!empty($email_to) ? ', ' : '') . substr($v, 0, strpos($v, "@")) . ' <' . $v . '>';
		}

    	$email_from = preg_split("/[\s,;]+/", $email_from);

    	$from = preg_replace("/(&amp;|&quot;|\"|\'|&#039;|&lt;|&gt;)/",'', $from); 
	
		$mailtempl = new seTable();
		$mailtempl->from('shop_mail', 'sm')
			->where("lang='?'", $this->lang)
			->andWhere("mailtype='?'", $mailtype)
			->fetchOne();
	
		$mail_text = $mailtempl->data;
			
		if ($typpost != 'html') 
		{
			$mail_text['letter'] = str_replace('<br>', "\r\n", $mail_text['letter']); 
		}
		else
		{
    		$mail_text['letter'] = str_replace("\r\n", "<br>", $mail_text['letter']); 
		}
	
		foreach ($array_change as $k => $v)
    	{
      		$mail_text['letter'] = str_replace("[" . $k . "]", $v, $mail_text['letter']); 
	  		$mail_text['subject'] = str_replace("[" . $k . "]", $v, $mail_text['subject']); 
 		}
    		$mail_text['subject'] = convert_cyr_string(str_replace('№', 'No', $mail_text['subject']), $this->charset, $this->encode); 
		$headers = ''; 
		$headers .= 'Content-type: text/' . $typpost . '; charset=' . $this->encode . "\n"; $headers .= 'From: ' . $from . ' <' . 
			(!empty($email_from[0]) ? $email_from[0] : $email_from) . ">\n";
     
		$headers .= 'Subject: ' . $mail_text['subject'] . "\n"; 
		$headers .= 'Return-path: ' . $email_from[0] . "\n"; 
		$headers .= "MIME-Version: 1.0\n"; 
		$headers .= "Content-Type: multipart/alternative;\n"; 
		$headers .= 'Content-type: text/' . $typpost . '; charset=' . $this->encode . "\n"; 
		$mail_text['letter'] = convert_cyr_string(str_replace('№', 'No', $mail_text['letter']), $this->charset, $this->encode); 
	
		mail($email_to, $mail_text['subject'], $mail_text['letter'], $headers, '-f' . $email_from[0]); 

	}

}

?>