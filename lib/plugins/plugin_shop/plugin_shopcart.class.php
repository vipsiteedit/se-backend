<?php

/**
* Базовый класс для взаимодействия с корзиной
* @filesource plugin_shopcart.class.php
* @copyright SERGEYEDGE
*/

class plugin_shopcart {
    private $options = array();
    public $incart;
    private $sumcart = null;
    private $products = array();
    private $groups = array();
    private $change_cart = false;
    private $events = null;
    private $mode = 'standart';
    private $id_user = 0;
    private $shoppath = '';
    private $startcookie = true;
    private $baseMeasureWeight = array();
    private $baseMeasureVolume = array();

    public function __construct( $options = array() ) {
        //print_r( $_SESSION );
        $this->id_user = seUserId();
        $this->shoppath = plugin_shoppage::getInstance()->getCatalog();
        $this->getBaseMeasure();

        if ( $this->id_user && seUserGroup() ) {
            $cart_mode = plugin_shopsettings::getInstance()->getValue( 'cart_mode' );
            if ( $cart_mode == 'user' )
            $this->mode = 'user';
        } else {
            $this->mode = 'standart';
        }

        $default = array(
            'round' => true,
            'type_price' => 0,
            'presence' => '',
            'curr' => se_getMoney()
        );
        $this->options = array_merge( ( array )$default, ( array )$options );

        $this->options[ 'round' ] = true;

        $u = new seTable( 'shop_order', 'so' );
        $u->select( 'so.id' );
        $u->where( 'so.is_auto_reserved' );
        $u->andWhere( 'NOT so.is_user_paid' );
        $u->andWhere( 'NOT so.is_paid' );
        $u->andWhere( 'NOT so.is_reserved' );
        $u->andWhere( 'NOT so.is_part_paid' );
        $u->andWhere( 'so.created_at <= (NOW() - INTERVAL 1 DAY)' );

        $list = $u->getList();

        if ( $list ) {
            foreach ( $list as $val ) {
                $this->sendMail( 'ordercancelled', $val[ 'id' ] );
            }

            se_db_query( 'UPDATE shop_order SET is_auto_reserved=0 WHERE is_auto_reserved AND NOT is_user_paid AND NOT is_paid AND NOT is_reserved AND NOT is_part_paid AND created_at <= (NOW() - INTERVAL 1 DAY)' );
        }

        $this->getInCart();
        return $this;
    }

    private function sendMail( $codeMail, $idOrder = false ) {
        if ( $codeMail ) {
            $urlSendEmail = _HOST_ . '/upload/sendmailorder.php';
            $params = array(
                'lang' => 'rus',
                'from' => 'shopcart',
                'idorder' => $idOrder,
                'codemail' => $codeMail
            );

            setlocale ( LC_ALL, 'ru_RU.UTF8' );

            $ch = curl_init( $urlSendEmail );
            curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params, '', '&' ) );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_HEADER, false );
            $result = curl_exec( $ch );
            curl_close( $ch );

        }
    }

    public function inCart( $id_product = 0 ) {
        if ( empty( $id_product ) || empty( $this->incart ) )
        return;
        foreach ( $this->incart as $val ) {
            if ( $val[ 'id' ] == $id_product )
            return true;
        }
        return;
    }

    private function registerEvent( $key, $old_count, $new_count, $event = '' ) {
        if ( !empty( $this->incart[ $key ] ) ) {
            if ( $old_count > $new_count ) {
                $count = $old_count - $new_count;
                $type = 'remove';
            } else {
                $count = $new_count - $old_count;
                $type = 'add';
            }
            $cart = $this->incart[ $key ];
            $product = $this->products[ $cart[ 'id' ] ];

            $plugin_amount = new plugin_shopamount( 0, $product, $this->options[ 'type_price' ], 1, $cart[ 'modifications' ], $this->options[ 'curr' ], empty( $cart[ 'notavailable' ] ) );

            $this->events[] = array(
                'key' => $key,
                'event' => $type,
                'count' => $count,
                'id' => $product[ 'id' ],
                'name' => $product[ 'name' ],
                'group' => $product[ 'group_name' ],
                'price' => $plugin_amount->getPrice( false ), //$plugin_amount->showPrice( true, $this->options[ 'round' ] ),
                'brand' => $product[ 'brand' ],
                'variant' => !empty( $cart[ 'modifications' ] ) ? plugin_shopmodifications::getName( join( ',', $cart[ 'modifications' ] ) ) : ''
            );
        }

        setcookie( 'shopcart', json_encode( $this->incart ), time() + ( 3600 * 24 * 14 ), '/' );
    }

    public function getEvents() {
        return $this->events;
    }

    private function clearCartUser() {
        if ( !empty( $this->id_user ) ) {
            unset( $_SESSION[ 'user_cart' ][ $this->id_user ] );
            $sc = new seTable( 'shop_cart' );
            $sc->where( 'id_user=?', $this->id_user );
            $sc->deleteList();
        }
    }

    private function delItemCartUser( $key ) {
        if ( !empty( $this->id_user ) && $key ) {
            unset( $_SESSION[ 'user_cart' ][ $this->id_user ][ $key ] );
            $sc = new seTable( 'shop_cart' );
            $sc->where( 'id_user=?', $this->id_user );
            $sc->andWhere( 'code="?"', $key );
            $sc->deleteList();
        }
    }

    private function updItemCartUser( $key, $count ) {
        if ( !empty( $this->id_user ) && $key ) {
            $_SESSION[ 'user_cart' ][ $this->id_user ][ $key ][ 'count' ] = $count;
            $count = str_replace( ',', '.', $count );
            $sc = new seTable( 'shop_cart' );
            $sc->where( 'id_user=?', $this->id_user );
            $sc->andWhere( 'code="?"', $key );
            $sc->update( 'count', $count );
            $sc->save();
        }
    }

    private function addItemCartUser( $key, $item ) {
        //$_SESSION[ 'user_cart' ][ $this->id_user ] = $this->incart;
        if ( !empty( $this->id_user ) && !empty( $key ) && !empty( $item ) ) {
            $_SESSION[ 'user_cart' ][ $this->id_user ][ $key ] = $item;
            $item[ 'count' ] = str_replace( ',', '.', $item[ 'count' ] );
            $sc = new seTable( 'shop_cart' );
            $sc->id_user = $this->id_user;
            $sc->id_price = $item[ 'id' ];
            $sc->code = $key;
            $sc->modifications = join( ',', $item[ 'modifications' ] );
            $sc->count = $item[ 'count' ];
            $sc->save();
        }
    }

    private function getItemsCartUser() {
        $items = array();
        if ( !empty( $this->id_user ) ) {
            if ( !isset( $_SESSION[ 'user_cart' ][ $this->id_user ] ) ) {
                $sc = new seTable( 'shop_cart' );
                $sc->select( 'id, id_user, id_price, code, modifications, count' );
                $sc->where( 'id_user=?', $this->id_user );
                $list = $sc->getList();
                if ( !empty( $list ) ) {
                    foreach ( $list as $val ) {
                        $items[ $val[ 'code' ] ] = array(
                            'id' => $val[ 'id_price' ],
                            'count' => $val[ 'count' ],
                            'modifications' => empty( $val[ 'modifications' ] ) ? 0 : explode( ',', $val[ 'modifications' ] )
                        );
                    }
                }
                $_SESSION[ 'user_cart' ][ $this->id_user ] = $items;
            } else
            $items = $_SESSION[ 'user_cart' ][ $this->id_user ];

            if ( !empty( $_SESSION[ 'shopcart' ] ) ) {
                foreach ( $_SESSION[ 'shopcart' ] as $key => $val ) {
                    if ( $val[ 'id' ] ) {
                        if ( isset( $items[ $key ] ) ) {
                            $this->updItemCartUser( $key, $val[ 'count' ] + $items[ $key ][ 'count' ] );
                        } else {
                            $this->addItemCartUser( $key, $val );
                        }
                    }
                }
                unset( $_SESSION[ 'shopcart' ] );
            }
            $items = $_SESSION[ 'user_cart' ][ $this->id_user ];
        }
        return $items;
    }

    public function clearCart() {
        unset( $_SESSION[ 'shopcart' ] );
        unset( $_SESSION[ 'code_coupon' ] );
        unset( $_SESSION[ 'cartcontact' ] );
        setcookie( 'shopcart', null, -1, '/' );
        $this->incart = null;
        if ( $this->mode == 'user' ) {
            $this->clearCartUser();
        }
    }

    public function getTotalSum() {
        if ( is_null( $this->sumcart ) ) {
            $incart = $this->incart;
            $this->sumcart = 0;
            foreach ( $incart as $key => $val ) {
                $plugin_amount = new plugin_shopamount( 0, $this->products[ $val[ 'id' ] ], $this->options[ 'type_price' ], $val[ 'count' ], $val[ 'modifications' ], $this->options[ 'curr' ], empty( $val[ 'notavailable' ] ) );
                $this->sumcart += $plugin_amount->getAmount( false );
            }
        }
        return $this->sumcart;
    }

    private function getBaseMeasure() {
        $mtab = new seTable( 'shop_measure_weight' );
        $mtab->select( 'value, designation' );
        $mtab->where( 'is_base=1' );
        $rweight = $mtab->fetchOne();
        $this->baseMeasureWeight = $rweight;

        $mtab = new seTable( 'shop_measure_volume' );
        $mtab->select( 'value, designation' );
        $mtab->where( 'is_base=1' );
        $rvolume = $mtab->fetchOne();
        $this->baseMeasureVolume = $rvolume;
    }

    public function fetchBtcCourse() {
        $this->btc_course = 1;

        $file = __DIR__ . '/btc.dat';

        if ( !file_exists( $file ) || filemtime( $file ) < ( time() - 60 ) ) {
            //$url = 'https://api.binance.com/api/v3/avgPrice?symbol=BTCRUB';
            $url = 'https://www.blockchain.com/ru/ticker';

            $curl = curl_init();
            curl_setopt( $curl, CURLOPT_URL, $url );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
            $data = curl_exec( $curl );

            curl_close( $curl );

            file_put_contents( __DIR__ . '/btc.log', date( '[Y-m-d H:i:s] - ' ) . $data . '\n', FILE_APPEND );

            $data = json_decode( $data, true );

            if ( $data && $data[ 'RUB' ][ 'last' ] ) {
                file_put_contents( $file, $data[ 'RUB' ][ 'last' ] );
                $this->btc_course = $data[ 'RUB' ][ 'last' ];
            }

        }

        if ( !$data[ 'RUB' ][ 'last' ] && file_exists( $file ) ) {
            $this->btc_course = file_get_contents( $file );
        }

        return $this->btc_course;
    }

    public function formatBtcPrice( $price ) {
        if ( !isset( $this->btc_course ) ) {
            $this->fetchBtcCourse();
        }

        $price = round( $price / $this->btc_course, 5 ) . ' <span class="fMoneyFlang btc">BTC</span>';

        return $price;
    }

    private function getPaymentDiscount( $total ) {
        $result = 0;
        if ( isset( $_SESSION[ 'payment_type_id' ] ) ) {
            $t = new seTable( 'shop_payment' );
            $t->select( 'id, discount' );
            $t->find( intval( $_SESSION[ 'payment_type_id' ] ) );
            if ( $t->fetchOne() ) {
                $p = new plugin_shopdelivery();
                $delivery = $p->getDelivery();
                $total += $delivery[ 'price' ] ?? 0.00;

                $result = $total * $t->discount / 100;
            }
        }
        return $result;
    }

    public function getTotalCart( $check_payment = true ) {
        $total = $total_cart = $discount = $weight = $volume = $count = 0;
        $goods_id = array();
        $incart = $this->incart;
        $total_cart = $this->getTotalSum();
        foreach ( $incart as $key => $val ) {
            $plugin_amount = new plugin_shopamount( 0, $this->products[ $val[ 'id' ] ], $this->options[ 'type_price' ], $val[ 'count' ], $val[ 'modifications' ], $this->options[ 'curr' ], empty( $val[ 'notavailable' ] ) );
            $plugin_amount->sum_cart = $total_cart;
            $plugin_amount->checkCoupon();
            $total += $plugin_amount->getAmount();
            $presence_count = $plugin_amount->getActualCount();
            $count += $presence_count;
            $discount += $plugin_amount->getDiscount() * $presence_count;

            $weight += $this->products[ $val[ 'id' ] ][ 'weight' ]  * $presence_count;
            $volume += $this->products[ $val[ 'id' ] ][ 'volume' ]  * $presence_count;
            $goods_id[] = $val[ 'id' ];
        }
        //$coupon = $this->getCoupon();

        if ( $check_payment )
        $payment_discount = $this->getPaymentDiscount( $total );

        if ( $payment_discount != 0 ) {
            //$discount -= $payment_discount;
            $total += $payment_discount;
        }

        $result =  array(
            'goods_id' => $goods_id,
            'count' => $count,
            'countPositions' => count( $incart ),
            'sum_total' => $total,
            'sum_total_products' => $total - $payment_discount,
            'sum_discount' => $discount,
            'show_total' => se_formatMoney( ceil( $total - $payment_discount ), $this->options[ 'curr' ], '&nbsp;', $this->options[ 'round' ] ),
            'show_discount' => se_formatMoney( max( 0, $discount ), $this->options[ 'curr' ], '&nbsp;', $this->options[ 'round' ] ),
            'weight' => ( $weight > 0 ) ? $weight : 0.001,
            'showWeight' => ( $weight > 0 ) ? ( $weight * $this->baseMeasureWeight[ 'value' ] ) . ' ' . $this->baseMeasureWeight[ 'designation' ] : '',
            'volume' => ( $volume > 0 ) ? $volume : 1,
            'showVolume' => ( $weight > 0 ) ? ( $weight * $this->baseMeasureVolume[ 'value' ] ) . ' ' . $this->baseMeasureVolume[ 'designation' ] : '',
            'curr' => $this->options[ 'curr' ],
            'payment_discount' => $payment_discount,
        );

        $result[ 'show_total2' ] = se_formatMoney( ceil( $total + $discount - $payment_discount ), $this->options[ 'curr' ], '&nbsp;', $this->options[ 'round' ] );

        if ( isset( $_SESSION[ 'payment_type_id' ] ) && $_SESSION[ 'payment_type_id' ] == 14 && get( 'page', 0 ) == 'oformlenie-zakaza' && false ) {
            $result[ 'show_total2' ] = $this->formatBtcPrice( $total );
            $result[ 'show_discount' ] = $this->formatBtcPrice( $discount );
        }

        return $result;
    }

    private function getInCart() {
        $this->incart = array();
        if ( $this->mode == 'user' ) {
            $this->incart = $this->getItemsCartUser();
        } else {
            if ( !empty( $_SESSION[ 'shopcart' ] ) ) {
                $this->incart = $_SESSION[ 'shopcart' ];
            } elseif ( !empty( $_COOKIE[ 'shopcart' ] ) && $this->startCookie ) {
                $this->incart = $_SESSION[ 'shopcart' ] = json_decode( $_COOKIE[ 'shopcart' ], 1 );
                $this->startCookie = false;
            }
        }
        if ( !empty( $this->incart ) ) {

            $products  = array();

            foreach ( $this->incart as $key => $val ) {
                if ( empty( $val[ 'id' ] ) ) {
                    unset( $this->incart[ $key ] );
                    continue;
                } else
                $products[] = $val[ 'id' ];
            }
            if ( !empty( $products ) ) {
                $shop_price = new seTable( 'shop_price', 'sp' );
                $shop_price->addField( 'signal_dt', "varchar('20')" );
                $shop_price->addField( 'delivery_time', "varchar('125')" );
                $shop_price->select( '(SELECT sb.name FROM shop_brand AS sb WHERE sb.id=sp.id_brand LIMIT 1) AS brand,
				sp.id, sp.name, sp.code, sp.article, sp.measure, sp.price, spg.id_group, sg.name AS group_name,
				sp.price_opt, sp.price_opt_corp, sp.step_count, sp.discount, sp.max_discount, 
				sp.curr, sp.presence_count, sp.presence, sp.volume, sp.weight,
				sp.delivery_time, sp.signal_dt,
				(SELECT si.picture FROM shop_img si WHERE si.id_price = sp.id ORDER BY si.`default` DESC, si.sort ASC LIMIT 1) AS img,
				concat_ws("/",sg.code_gr, sp.code) AS url' );
                $shop_price->innerJoin( 'shop_price_group spg', 'sp.id=spg.id_price' );
                $shop_price->innerJoin( 'shop_group sg', 'spg.id_group = sg.id' );
                $shop_price->where( 'sp.id IN (?)', join( ',', $products ) );
                $list = $shop_price->getList();
                echo se_db_error();
                //print_r( $list );
                if ( !empty( $list ) ) {
                    foreach ( $list as $val ) {
                        $this->products[ $val[ 'id' ] ] = $val;
                        if ( !isset( $this->groups[ $val[ 'id_group' ] ] ) ) {
                            $this->groups[ $val[ 'id_group' ] ] = array();
                        }
                        if ( !in_array( $val[ 'id' ], $this->groups[ $val[ 'id_group' ] ] ) ) {
                            $this->groups[ $val[ 'id_group' ] ][] = $val[ 'id' ];
                        }
                    }
                }
            }

            foreach ( $this->incart as $key => $val ) {
                $plugin_amount = new plugin_shopamount( 0, $this->products[ $val[ 'id' ] ], $this->options[ 'type_price' ], $val[ 'count' ], $val[ 'modifications' ], $this->options[ 'curr' ], empty( $val[ 'notavailable' ] ) );
                //$plugin_amount->sum_cart = $total_cart;
                $count = $plugin_amount->getActualCount();
                if ( ( float )$count > 0 ) {
                    if ( $val[ 'count' ] != $count ) {
                        $this->incart[ $key ][ 'count' ] = $count;
                        if ( $this->mode == 'user' )
                        $this->updItemCartUser( $key, $count );
                        else
                        $_SESSION[ 'shopcart' ][ $key ][ 'count' ] = $count;
                    }
                } else {
                    unset( $_SESSION[ 'shopcart' ][ $key ], $this->incart[ $key ], $this->products[ $val[ 'id' ] ] );
                    if ( $this->mode == 'user' ) {
                        $this->delItemCartUser( $key );
                    }
                }
            }
        }
        return $this->incart;
    }

    public function getGoodsCart() {
        $goods_list = array();
        $incart = $this->incart;
        if ( empty( $incart ) ) return;

        $sum_cart = $this->getTotalSum();
        foreach ( $incart as $key => $value ) {
            if ( !$value[ 'id' ] ) continue;
            $goods[ 'key' ] = $key;
            $goods[ 'price_id' ] = $value[ 'id' ];
            $goods[ 'params' ] = $goods[ 'paramsname' ] = '';
            $product = $this->products[ $value[ 'id' ] ];
            $goods[ 'name' ] = $product[ 'name' ];
            $goods[ 'code' ] = $product[ 'code' ];
            $goods[ 'article' ] = $product[ 'article' ];
            $goods[ 'delivery_time' ] = $product[ 'delivery_time' ];
            $goods[ 'signal_dt' ] = $product[ 'signal_dt' ];

            $goods[ 'img' ] = $product[ 'img' ];
            $goods[ 'measure' ] = $product[ 'measure' ];
            $goods[ 'volume' ] = $product[ 'volume' ];
            $goods[ 'weight' ] = $product[ 'weight' ];
            $goods[ 'link' ] = seMultiDir() . '/' . $this->shoppath . '/' . $product[ 'url' ] . SE_END;
            $plugin_amount = new plugin_shopamount( 0, $product, $this->options[ 'type_price' ], $value[ 'count' ], $value[ 'modifications' ], $this->options[ 'curr' ], empty( $value[ 'notavailable' ] ) );
            $plugin_amount->checkCoupon();
            $plugin_amount->sum_cart = $sum_cart;
            $goods[ 'price' ] = $plugin_amount->getPrice( false );
            $goods[ 'oldprice' ] = $plugin_amount->showPrice( false, $this->options[ 'round' ] );
            $goods[ 'newprice' ] = $plugin_amount->showPrice( true, $this->options[ 'round' ] );
            $goods[ 'sum' ] = $plugin_amount->getAmount( true );
            $goods[ 'oldsum' ] = $plugin_amount->showAmount( false, $this->options[ 'round' ] );
            $goods[ 'newsum' ] = $plugin_amount->showAmount( true, $this->options[ 'round' ] );
            $goods[ 'discount' ] = $plugin_amount->getDiscount();
            $goods[ 'show_discount' ] = $plugin_amount->showDiscount( $this->options[ 'round' ] );
            $goods[ 'presence_count' ] = $plugin_amount->showPresenceCount( $this->options[ 'presence' ] );
            $goods[ 'count' ] = $plugin_amount->getActualCount();
            $goods[ 'step' ] = $plugin_amount->getStepCount();
            $goods[ 'show_count' ] = $goods[ 'count' ] . '&nbsp;' . $product[ 'measure' ];
            $goods[ 'curr' ] = $this->options[ 'curr' ];

            if ( !empty( $value[ 'modifications' ] ) ) {
                $goods[ 'params' ] = join( ',', $value[ 'modifications' ] );
                $goods[ 'paramsname' ] = plugin_shopmodifications::getName( $goods[ 'params' ] );
                $article = plugin_shopmodifications::getArticle( $goods[ 'params' ] );
                if ( !empty( $article ) )
                $goods[ 'article' ] = $article;
            }

            $goods_list[] = $goods;
        }

        if ( $sum_cart > 0/* && get( 'page', 0 ) == 'oformlenie-zakaza'*/ ) {
            $total = $this->getTotalCart();
            $sum_cart = $total[ 'sum_total_products' ];
            if ( $sum_cart < 4000 ) {
                $count_add = 1;
            } elseif ( $sum_cart < 8000 ) {
                $count_add = 2;
            } elseif ( $sum_cart < 12000 ) {
                $count_add = 3;
            } elseif ( $sum_cart < 16000 ) {
                $count_add = 4;
            } elseif ( $sum_cart < 20000 ) {
                $count_add = 5;
            } elseif ( $sum_cart < 30000 ) {
                $count_add = 6;
            } elseif ( $sum_cart < 40000 ) {
                $count_add = 7;
            } elseif ( $sum_cart < 50000 ) {
                $count_add = 8;
            } elseif ( $sum_cart < 60000 ) {
                $count_add = 9;
            } else {
                $count_add = 10;
            }

            $shop_price = new seTable( 'shop_price', 'sp' );
            $shop_price->select( 'sp.id, sp.name, sp.code, sp.article, sp.measure, sp.price, sp.id_group, sp.price_opt, sp.price_opt_corp, sp.step_count, sp.discount, sp.max_discount, sp.curr, sp.presence_count, sp.presence, sp.volume, sp.weight, (SELECT si.picture FROM shop_img si WHERE si.id_price = sp.id ORDER BY si.`default` DESC, si.sort ASC LIMIT 1) AS img' );
            $shop_price->where( 'sp.article="?"', 'bonus' );
            $product_add = $shop_price->fetchOne();

            if ( $shop_price->isFind() ) {
                $plugin_amount = new plugin_shopamount( 0, $product_add, $this->options[ 'type_price' ], $count_add, 0, $this->options[ 'curr' ], 1 );
                $goods_list[] = array(
                    'key' => 0,
                    'price_id' => $shop_price->id,
                    'name' => $shop_price->name,
                    'code' => $shop_price->code,
                    'article' => $shop_price->article,
                    'img' => $shop_price->img,
                    'measure' => $shop_price->measure,
                    'price' => $plugin_amount->getPrice( false ),
                    'oldprice' => $plugin_amount->showPrice( false, $this->options[ 'round' ] ),
                    'newprice' => $plugin_amount->showPrice( true, $this->options[ 'round' ] ),
                    'sum' => $plugin_amount->getAmount( true ),
                    'oldsum' => $plugin_amount->showAmount( false, $this->options[ 'round' ] ),
                    'newsum' => $plugin_amount->showAmount( true, $this->options[ 'round' ] ),
                    'discount' => $plugin_amount->getDiscount(),
                    'show_discount' => $plugin_amount->showDiscount( $this->options[ 'round' ] ),
                    'presence_count' => $plugin_amount->showPresenceCount( $this->options[ 'presence' ] ),
                    'count' => $plugin_amount->getActualCount(),
                    'step' => $plugin_amount->getStepCount(),
                    'show_count' => $plugin_amount->getActualCount() . '&nbsp;' . $shop_price->measure,
                    'curr' => $this->options[ 'curr' ],
                    'link' => '#',
                );
            }
        }

        return $goods_list;
    }

    public function updateCart( $items = array() ) {
        $count_items = ( !empty( $items ) ) ? $items : $_POST[ 'countitem' ];
        if ( !empty( $count_items ) ) {
            foreach ( $count_items as $key => $count ) {
                $cart = $this->incart[ $key ];
                if ( $cart ) {
                    $count = max( 0, ( float )$count );
                    $plugin_amount = new plugin_shopamount( $cart[ 'id' ], '', $this->options[ 'type_price' ], $count, $cart[ 'modifications' ], $this->options[ 'curr' ], empty( $cart[ 'notavailable' ] ) );
                    $count = $plugin_amount->getActualCount();
                    $this->registerEvent( $key, $cart[ 'count' ], $count, 'upd' );
                    if ( $cart[ 'count' ] != $count ) {
                        $this->incart[ $key ][ 'count' ] = $count;
                        if ( $this->mode == 'user' ) {
                            $this->updItemCartUser( $key, $count );
                        } else {
                            $_SESSION[ 'shopcart' ][ $key ][ 'count' ] = $count;
                        }
                    }

                    $plugin_amount->sum_cart = $this->getTotalSum();

                    $this->change_cart = true;
                }
            }
        }
    }

    public function delCart( $params = array() ) {
        $del_cart_name = ( !empty( $params[ 'delcartname' ] ) ) ? $params[ 'delcartname' ] : getRequest( 'delcartname', 3 );
        if ( !$del_cart_name ) {
            $del_cart_id = ( !empty( $params[ 'id' ] ) ) ? $params[ 'id' ] : getRequest( 'delcart', 1 );
            $del_cart_param = ( !empty( $params[ 'param' ] ) ) ? $params[ 'param' ] : getRequest( 'delcartparam', 3 );
            if ( is_array( $del_cart_param ) )
            $del_cart_param = 'param:' . join( ',', $del_cart_param );

            $del_cart_name = md5( $del_cart_id . '_' . $del_cart_param );
        }
        if ( $del_cart_name ) {
            $count = $this->incart[ $del_cart_name ][ 'count' ];
            unset( $_SESSION[ 'shopcart' ][ $del_cart_name ], $this->incart[ $del_cart_name ] );
            $this->registerEvent( $del_cart_name, $count, 0, 'del' );
            if ( $this->mode == 'user' ) {
                $this->delItemCartUser( $del_cart_name );
            }
            $this->change_cart = true;
            return $del_cart_name;
        }
        return;
    }

    public function useCoupon() {
        if ( !file_exists( SE_ROOT . '/system/logs/shop_coupons_history.upd' ) ) {
            $sql = "CREATE TABLE IF NOT EXISTS `shop_coupons_history` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`code_coupon` varchar(50) NOT NULL,
				`id_coupon` int(10) unsigned NOT NULL,
				`id_user` int(10) unsigned DEFAULT NULL,
				`id_order` int(10) unsigned NOT NULL,
				`discount` float(10,2) DEFAULT NULL,
				`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY (`id`),
				KEY `id_coupon` (`id_coupon`),
				KEY `id_user` (`id_user`),
				KEY `id_order` (`id_order`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

            if ( se_db_query( $sql ) ) {
                se_db_query( "ALTER TABLE `shop_coupons_history`
				ADD CONSTRAINT `shop_coupons_history_fk1` FOREIGN KEY (`id_order`) REFERENCES `shop_order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
				ADD CONSTRAINT `shop_coupons_history_fk` FOREIGN KEY (`id_coupon`) REFERENCES `shop_coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;" );

                $file = fopen( SE_ROOT . '/system/logs/shop_coupons_history.upd', 'w+' );
                fclose( $file );
            }
        }
        $r_coupon = array( 'discount' => 0, 'id' => null );
        if ( !empty( $_SESSION[ 'code_coupon' ] ) && $coupon = $this->getCoupon( $_SESSION[ 'code_coupon' ] ) ) {
            $r_coupon[ 'discount' ] = se_Money_Convert( $coupon[ 'discount' ], $this->options[ 'curr' ], se_baseCurrency() );
            $shop_coupon = new seTable( 'shop_coupons' );
            $shop_coupon->select( 'id, count_used, status' );
            $shop_coupon->where( "code = '?'", $coupon[ 'code' ] );
            if ( $shop_coupon->fetchOne() ) {
                $r_coupon[ 'id' ] = $shop_coupon->id;
                if ( $shop_coupon->count_used <= 1 ) {
                    $shop_coupon->update( 'status', "'N'" );
                }
                $shop_coupon->addupdate( 'count_used', 'count_used-1' );
                $shop_coupon->where( "code = '?'", $coupon[ 'code' ] );
                $shop_coupon->save();
            }
        }
        return $r_coupon;
    }

    public function getCoupon( $code = '' ) {

        if ( !file_exists( SE_ROOT . '/system/logs/shop_coupons_id_user.upd' ) ) {
            $u = new seTable( 'shop_coupons' );
            if ( !$u->isFindField( 'id_user' ) ) {
                $u->addField( 'id_user', 'int(10) UNSIGNED DEFAULT NULL', 1 );
                se_db_query( 'ALTER TABLE shop_coupons ADD CONSTRAINT FK_shop_coupons_se_user_id FOREIGN KEY (id_user) REFERENCES se_user(id) ON DELETE SET NULL ON UPDATE CASCADE' );
            }
        }

        $coupon = array();
        $code_coupon = '';
        if ( !empty( $code ) ) {
            $code_coupon = $code;
        } elseif ( getRequest( 'code_coupon', 3 ) ) {
            $code_coupon = getRequest( 'code_coupon', 3 );
        } elseif ( !empty( $_SESSION[ 'code_coupon' ] ) ) {
            $code_coupon = $_SESSION[ 'code_coupon' ];
        }
        unset( $_SESSION[ 'code_coupon' ] );
        unset( $_SESSION[ 'promo_payment' ] );
        if ( $code_coupon ) {
            $coupon_valid = true;
            $shop_coupon = new seTable( 'shop_coupons' );
            $shop_coupon->select( 'id, type, discount, currency, expire_date, min_sum_order, count_used, payment_id, only_registered, id_user' );
            $shop_coupon->where( "status = '?'", 'Y' );
            $shop_coupon->andwhere( "code = '?'", $code_coupon );
            if ( $shop_coupon->fetchOne() ) {

                $total = $this->getTotalCart();
                $sum_cart = $total[ 'sum_total' ];

                if ( $shop_coupon->expire_date > 0 && $shop_coupon->expire_date < date( 'Y-m-d' ) ) {
                    $coupon_valid = false;
                }

                if ( $shop_coupon->only_registered == 'Y' && !seUserGroup() && !seUserId() || ( $shop_coupon->id_user && $shop_coupon->id_user != seUserId() ) ) {
                    $coupon_valid = false;
                }

                if ( $shop_coupon->min_sum_order > 0 ) {
                    if ( $sum_cart < se_Money_Convert( $shop_coupon->min_sum_order, $shop_coupon->currency, $this->options[ 'curr' ] ) )
                    $coupon_valid = false;
                }

                if ( !$shop_coupon->count_used > 0 ) {
                    $coupon_valid = false;
                    $shop_coupon->update( 'status', "'N'" );
                    $shop_coupon->addupdate( 'count_used', 0 );
                    $shop_coupon->where( "code = '?'", $code_coupon );
                    $shop_coupon->save();
                }
                if ( $coupon_valid ) {
                    if ( $shop_coupon->payment_id ) {
                        $_SESSION[ 'promo_payment' ] = $shop_coupon->payment_id;
                    }

                    $_SESSION[ 'code_coupon' ] = $coupon[ 'code' ] = $code_coupon;
                    if ( $shop_coupon->type == 'p' || $shop_coupon->type == 'g' ) {
                        $value = min( $shop_coupon->discount, 100 );
                        if ( $shop_coupon->type == 'g' ) {
                            $sum_cart = 0;
                            $coupon[ 'products' ] = array();
                            $coupon[ 'value' ] = $value . ' %';
                            $shop_coupons_goods = new seTable( 'shop_coupons_goods' );
                            $shop_coupons_goods->select( 'group_id, price_id' );
                            $shop_coupons_goods->where( 'coupon_id = ?', $shop_coupon->id );
                            $shop_coupons_goods->andWhere( '(group_id IS NOT NULL OR price_id IS NOT NULL)' );
                            $list = $shop_coupons_goods->getList();
                            unset( $shop_coupons_goods );
                            if ( !empty( $list ) ) {
                                $coupon_products_id = array();
                                foreach ( $list as $val ) {
                                    if ( !empty( $val[ 'price_id' ] ) && isset( $this->products[ $val[ 'price_id' ] ] ) ) {
                                        $coupon_products_id[] = $val[ 'price_id' ];
                                    } elseif ( !empty( $val[ 'group_id' ] ) && isset( $this->groups[ $val[ 'group_id' ] ] ) ) {
                                        $coupon_products_id = array_merge( $coupon_products_id, $this->groups[ $val[ 'group_id' ] ] );
                                    }
                                }
                                unset( $list );
                                $coupon_products_id = array_unique( $coupon_products_id );

                                if ( !empty( $coupon_products_id ) ) {
                                    foreach ( $this->incart as $key => $val ) {
                                        if ( in_array( $val[ 'id' ], $coupon_products_id ) ) {
                                            $plugin_amount = new plugin_shopamount( 0, $this->products[ $val[ 'id' ] ], $this->options[ 'type_price' ], $val[ 'count' ], $val[ 'modifications' ], $this->options[ 'curr' ], empty( $val[ 'notavailable' ] ) );
                                            $sum_cart += $plugin_amount->getAmount();
                                            $coupon[ 'products' ][] = $key;
                                        }
                                    }
                                }
                            }
                            $coupon[ 'discount' ] = ( $sum_cart ) * ( $value / 100 );
                            $coupon[ 'value' ] = $value . ' %';
                        } else {
                            $coupon[ 'discount' ] = ( $sum_cart ) * ( $value / 100 );
                            $coupon[ 'value' ] = $value . ' %';
                        }
                    } else {
                        $discount = se_Money_Convert( $shop_coupon->discount, $shop_coupon->currency, $this->options[ 'curr' ] );
                        $value = ( $discount <= $sum_cart ) ? $discount : $sum_cart;
                        $coupon[ 'discount' ] = $value;
                        $coupon[ 'value' ] = se_FormatMoney( $shop_coupon->discount, $shop_coupon->currency );
                    }
                    $coupon[ 'show' ] = se_FormatMoney( $coupon[ 'discount' ], $this->options[ 'curr' ], '&nbsp;', $this->options[ 'round' ] );
                    if ( $this->options[ 'round' ] )  $coupon[ 'discount' ] = round( $coupon[ 'discount' ] );
                }
            } else return;
        }

        if ( $coupon ) {
            $coupon[ 'discount' ] = 0;
        }

        return $coupon;
    }

    private function addInCart( $product_id = 0, $count = 0, $modifications = null ) {
        if ( empty( $product_id ) )
        return;
        if ( empty( $count ) ) {
            $shop_price = new seTable( 'shop_price' );
            $shop_price->select( 'step_count' );
            $shop_price->where( 'id=?', $product_id );
            $shop_price->fetchOne();
            $count = ( float )$shop_price->step_count;
            if ( !$count > 0 )
            $count = 1;
        }
        $mod = ( is_array( $modifications ) ) ? join( '_', $modifications ) : $modifications;
        $cart_name = md5( $product_id . '_' . $mod );

        if ( !empty( $this->incart[ $cart_name ][ 'count' ] ) ) {
            $count += $this->incart[ $cart_name ][ 'count' ];
            $this->updateCart( array( $cart_name => $count ) );
        }
        {

            $shop_price = new seTable( 'shop_price', 'sp' );
            $shop_price->select( '(SELECT sb.name FROM shop_brand AS sb WHERE sb.id=sp.id_brand LIMIT 1) AS brand, 
			sp.id, sp.name, sp.code, sp.article, sp.measure, sp.price, spg.id_group, sp.price_opt, 
			sp.price_opt_corp, sp.step_count, sp.discount, sp.max_discount, sp.curr, sp.presence_count, 
			sp.presence, sp.volume, sp.weight,
			sp.delivery_time, sp.signal_dt,
			(SELECT si.picture FROM shop_img si WHERE si.id_price = sp.id ORDER BY si.`default` DESC, si.sort ASC LIMIT 1) AS img, 
			(SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications,
			concat_ws("/",sg.code_gr, sp.code) AS url' );
            $shop_price->innerJoin( 'shop_price_group spg', 'sp.id=spg.id_price' );
            $shop_price->innerJoin( 'shop_group sg', 'spg.id_group = sg.id' );
            $shop_price->where( 'sp.id = ?', $product_id );
            $this->products[ $product_id ] = $shop_price->fetchOne();
            echo se_db_error();
            if ( $this->products[ $product_id ][ 'modifications' ] && empty( $_SESSION[ 'modifications' ][ $product_id ] ) && plugin_shopsettings::getInstance()->getValue( 'modification_mode' ) == 'placeholder' ) {
                $psm = new plugin_shopmodifications( $product_id );
                return 'error:' . 'Вы не указали параметр "' . $psm->getFeatureName() . '",<br> необходимо выбрать значение из выпадающего списка.';
            }
            $plugin_amount = new plugin_shopamount( $product_id, $this->products[ $product_id ], $this->options[ 'type_price' ], $count, $modifications, '', !isRequest( 'notavailable' ) );

            $count = $plugin_amount->getActualCount();

            unset( $plugin_amount );

            if ( $count ) {
                $this->incart[ $cart_name ][ 'id' ] = $product_id;
                $this->incart[ $cart_name ][ 'modifications' ] = $modifications;
                $this->incart[ $cart_name ][ 'count' ] = $count;
                if ( isRequest( 'notavailable' ) ) {
                    $this->incart[ $cart_name ][ 'notavailable' ] = true;
                }

                $this->registerEvent( $cart_name, 0, $count, 'add' );
            } elseif ( isset( $this->incart[ $cart_name ] ) ) {
                unset( $this->incart[ $cart_name ] );
            }

            if ( $this->mode == 'user' ) {
                $this->addItemCartUser( $cart_name, $this->incart[ $cart_name ] );
            } else
            $_SESSION[ 'shopcart' ] = $this->incart;
        }
        $this->change_cart = true;
        return $cart_name;
    }

    public function addCart( $params = array() ) {

        $product_id = ( !empty( $params[ 'id' ] ) ) ? $params[ 'id' ] : $_REQUEST[ 'addcart' ];

        if ( !empty( $product_id ) ) {
            if ( is_array( $product_id ) ) {
                foreach ( $product_id as $key => $params ) {
                    if ( is_array( $params ) ) {
                        $id = ( int )$key;
                        foreach ( $params as $val ) {
                            $count = !empty( $params[ 'count' ][ $val ] ) ? ( float )$params[ 'count' ][ $val ] : $_REQUEST[ 'addcartcount' ][ $val ];
                            $modifications = explode( ',', $val );
                            $this->addInCart( $id, $count, $modifications );
                        }
                    } else {
                        $id = ( int )$params;
                        $count = !empty( $params[ 'count' ][ $id ] ) ? ( float )$params[ 'count' ][ $id ] : $_REQUEST[ 'addcartcount' ][ $id ];
                        $modifications = !empty( $params[ 'modifications' ][ $id ] ) ? $params[ 'modifications' ][ $id ] : $_SESSION[ 'modifications' ][ $id ];
                        $this->addInCart( $id, $count, $modifications );
                    }
                }
            } else {
                $id = ( int )$product_id;
                $count = ( !empty( $params[ 'count' ] ) ? $params[ 'count' ] : getRequest( 'addcartcount', 2 ) );
                if ( empty( $_SESSION[ 'modifications' ][ $id ] ) ) $_SESSION[ 'modifications' ][ $id ] = null;
                $modifications = !empty( $params[ 'modifications' ] ) ? $params[ 'modifications' ] : $_SESSION[ 'modifications' ][ $id ];

                $cart_name = $this->addInCart( $id, $count, $modifications );
            }
        }

        $plugin_shopstat = new plugin_shopstat();
        $plugin_shopstat->saveEvent( 'add cart', $product_id );
        return $cart_name;
    }

    public function __destruct() {
        if ( $this->change_cart ) {
            $plugin_shopstat = new plugin_shopstat();
            $plugin_shopstat->saveCart();
        }
    }

    public function getRelated( $id = 0, $groups = true ) {
        $s = array();

        $result = array();

        if ( !$id ) {
            foreach ( $this->incart as $val ) {
                $s[] = $val[ 'id' ];
            }
        } else {
            $s[] = $id;
        }

        $t = new seTable( 'shop_accomp', 'sa' );
        $t->select( 'DISTINCT sa.id_acc, sa.id_group, sp.name, sp.code, sp.article, sp.measure, sp.price, 
		sp.price_opt, sp.price_opt_corp, sp.step_count, sp.discount, sp.max_discount, sp.curr, 
		sp.presence_count, sp.presence, sp.volume, sp.weight, 
		sp.signal_dt, sp.delivery_time,
		(SELECT si.picture FROM shop_img si WHERE si.id_price = sp.id ORDER BY si.`default` DESC, si.sort ASC LIMIT 1) AS img, 
		(SELECT 1 FROM shop_modifications sm WHERE sm.id_price=sp.id LIMIT 1) AS modifications,
		concat_ws("/",sg.code_gr, sp.code) AS url' );
        $t->leftJoin( 'shop_price sp', 'sp.id=sa.id_acc' );
        $t->innerJoin( 'shop_group sg', 'sg.id=sp.id_group' );
        $t->where( 'sa.id_price IN (?)', join( ',', $s ) );
        $t->andWhere( '(sa.id_acc NOT IN (?) OR sa.id_acc IS NULL)', join( ',', $s ) );
        $list = $t->getList( 0, 20 );

        $p = plugin_shopgroups::getInstance();

        $image = new plugin_ShopImages();

        $image2 = new plugin_ShopImages( 'group' );

        foreach ( $list as $val ) {
            if ( !empty( $val[ 'id_acc' ] ) ) {
                $amount = new plugin_shopamount( 0, $val, $this->options[ 'type_price' ], 1 );
                $result[] = array(
                    'type' => 'product',
                    'id' => $val[ 'id_acc' ],
                    'name' => $val[ 'name' ],
                    'image' => $image->getPictFromImage( $val[ 'img' ], '200x200', 's' ),
                    'price' => $amount->showPrice(),
                    'link' => $goods[ 'link' ] = seMultiDir() . '/' . $this->shoppath . '/' . $val[ 'url' ] . SE_END,
                );
            } elseif ( !empty( $val[ 'id_group' ] ) && $groups ) {
                if ( $group = $p->getGroup( ( int )$val[ 'id_group' ] ) ) {
                    $result[] = array(
                        'type' => 'group',
                        'id' => $val[ 'id_group' ],
                        'name' => $group[ 'name' ],
                        'image' => $image2->getPictFromImage( $group[ 'image' ], '200x200', 's' ),
                        'link' => seMultiDir() . '/' . $this->shoppath . '/' . $group[ 'url' ] . URL_END,
                    );
                }
            }
        }

        return $result;
    }

    public function getPaymentList( $id_delivery, $all = false, $amount = 0 ) {
        $payment_list = array();
        $lang = se_getLang();
        $selected = false;
        $key_select = false;
        $selected_payment = !empty( $_SESSION[ 'payment_type_id' ] ) ? $_SESSION[ 'payment_type_id' ] : null;
        $_SESSION[ 'payment_type_id' ] = null;
        $first_delivery = null;
        $shop_payment = new seTable( 'shop_payment', 'sp' );
        $select = '';
        if ( $all && $id_delivery )
        $select = ", (SELECT id_payment FROM shop_delivery_payment WHERE id_payment = sp.id AND id_delivery = $id_delivery) as delivery";
        $shop_payment->select( 'sp.id, sp.name_payment, sp.logoimg, sp.way_payment, sp.startform, sp.max_amount'.$select );
        $shop_payment->where( "sp.lang = '?'", $lang );
        $shop_payment->andWhere( "sp.active = 'Y'" );

        $days = array( 0 => 'sun', 1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat' );
        $d = date( 'w' );
        $day_w = $days[ $d ];

        if ( $day_w ) {

            $time = date( 'H:i' );
            $shop_payment->innerJoin( 'shop_payment_time spt', 'sp.id=spt.id_payment' );
            $shop_payment->andWhere( 'spt.is_' . $day_w );
            $shop_payment->andWhere( 'spt.' . $day_w . '_from <= "?"', $time );
            $shop_payment->andWhere( '(spt.' . $day_w . '_to > "?" OR spt.' . $day_w . '_to = "00:00")', $time );

        }

        if ( $id_delivery && !$all ) {
            $shop_payment->innerjoin( 'shop_delivery_payment sdp', 'sdp.id_payment=sp.id' );
            $shop_payment->andWhere( 'sdp.id_delivery=?', $id_delivery );
        }

        $shop_payment->orderby( 'sort', 0 );
        $payment_list = $shop_payment->getlist();

        $first_payment = 0;

        if ( !empty( $payment_list ) ) {
            foreach ( $payment_list as $key => $val ) {
                if ( $val[ 'logoimg' ] )
                $payment_list[ $key ][ 'logoimg' ] = '/images/'.$lang.'/shoppayment/'.$val[ 'logoimg' ];

                $payment_list[ $key ][ 'disabled' ] = false;

                if ( $amount && $val[ 'max_amount' ] > 0 && $amount > $val[ 'max_amount' ] ) {
                    $payment_list[ $key ][ 'disabled' ] = true;

                } elseif ( !$first_payment ) {
                    $first_payment = $key;
                }

                $payment_list[ $key ][ 'selected' ] = '';
                if ( !$selected && $val[ 'id' ] == $selected_payment && !$payment_list[ $key ][ 'disabled' ] ) {
                    $selected = $val[ 'id' ];
                    if ( !$all || empty( $id_delivery ) )
                    $payment_list[ $key ][ 'selected' ] = $_SESSION[ 'payment_type_id' ] = $val[ 'id' ];
                    elseif ( !empty( $val[ 'delivery' ] ) )
                    $payment_list[ $key ][ 'selected' ] = $_SESSION[ 'payment_type_id' ] = $val[ 'id' ];

                }

                $payment_list[ $key ][ 'delivery' ] = false;
                if ( $all && $id_delivery ) {
                    $payment_list[ $key ][ 'delivery' ] = empty( $val[ 'delivery' ] );
                    if ( is_null( $first_delivery ) && $val[ 'delivery' ] && !$payment_list[ $key ][ 'disabled' ] )
                    $first_delivery = $key;
                }

            }
            if ( empty( $_SESSION[ 'payment_type_id' ] ) ) {
                if ( !$all )
                $payment_list[ $first_payment ][ 'selected' ] = $_SESSION[ 'payment_type_id' ] = $payment_list[ $first_payment ][ 'id' ];
                else
                $payment_list[ $first_delivery ][ 'selected' ] = $_SESSION[ 'payment_type_id' ] = $payment_list[ $first_delivery ][ 'id' ];
            }
        }
        return $payment_list;
    }
}
