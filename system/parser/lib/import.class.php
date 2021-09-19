<?php

class Import
{

    private $data = array();
    private $cycleNum = 0;
    private $checkGroupIdName = FALSE;
    /** проверка наличия массива ид-имя группы в сессии */
    private $feature = array();
    /** @var array характеристики из БД */
    private $modData = array();
    /** @var array данные для заполнения модификации в таблицАХ */
    private $thereModification = array();
    /** @var array вкл/выкл создания характеристик */
    private $productTables = array();
    /** @var array таблицы по товару (временные данные) */

    public $fieldsMap = array();
    private $groups = array();
    private $codeId = array();
    private $onlyPrice = false;

    public $importData = array(
        # shop_price
        #'products' => array(),
        # shop_group
        #'category' => array()
    );

    /**
     * ins Добавление в БД данных как новые строки
     * rld Добавление в БД данных как новые строки, с удалением других товаров
     * upd Обновление БД
     * pre Подготовка данных
     */
    public $mode = 'upd';

    public function __construct($settings = null, $options = array())
    {
        /** сборка */

        if (empty($settings['type']))
            $this->mode = 'upd';
        else if (!empty($settings['reset']))
            $this->mode = 'rld';
        else
            $this->mode = 'ins';
        if (isset($settings['onlyPrice'])) $this->onlyPrice = (bool)$settings['onlyPrice'];

        if (!empty($settings['prepare']) && $settings['prepare'] == "true") $this->mode = 'pre';
    } // сборка

    /************************************************************
     * Добавляем группы и связки групп
     * $groups = [['name', 'code', 'path', 'images', 'note', ]]
     *
     *************************************************************/
    public function importGroups($groups = array())
    {
        $ngr = array();  // Идентификациооный список
        $regions = [];
        foreach ($groups as $group) {
            if (!empty($group['region'])) {
                $regions[] = $group['region'];
            }
            if (!empty($group['code'])) {
                $ngr[md5($group['path'])] = $group['code'];
            }
        }
//        if (!empty($regions)) {
//            $rList = $this->getRegions($regions);
//            foreach ($rList as $item) {
//                $regions[$item['name']] = $item['id'];
//            }
//        }




        foreach ($groups as $group) {
            //echo $group['path'];
            if (!empty($group['path'])) {
                $pathL = explode('/', $group['path']);
                $count = count($pathL);
                $npath = '';
                $parent = '';
                foreach ($pathL as $level => $name) {
                    $tp = $pathL;
                    for ($i = $level + 1; $i < $count; $i++) {
                        unset($tp[$i]);
                    }
                    $npath = join('/', $tp);
                    if (!empty($ngr[md5($npath)])) {
                        $code = $ngr[md5($npath)];
                    } else {
                        $code = strtolower(se_translite_url($npath));
                        $ngr[md5($npath)] = $code;
                    }
                    $item = $this->getGroup($groups, $code);
                    $ngroup = array(
                        'parent' => $parent,
                        'name' => str_replace('&#8260;','/', $name),
                        'code' => $code
                    );
                    if ($level + 1 == $count) {
                        if (!empty($group['picture'])) $ngroup['picture'] = $group['picture'];
                        if (!empty($group['note'])) $ngroup['commentary'] = $group['note'];
                        if (!empty($group['title'])) $ngroup['title'] = $group['title'];
                        if (!empty($group['keywords'])) $ngroup['keywords'] = $group['keywords'];
                        if (!empty($group['description'])) $ngroup['description'] = $group['description'];
                        if (!empty($group['page_title'])) $ngroup['page_title'] = $group['page_title'];
                    }

                    $this->setImportGroup($ngroup);
                    $parent = $code;
                }

            } else {
                $this->setImportGroup($group);
            }
        }
        $this->updateGroupTable();
        //
    }

    private function getRegions($regionsName = array())
    {
        $rtab = new DB('shop_contacts');
        $rtab->select('id, name');
        $rtab->where("name IN ('?')", join(',', $regionsName));
        return $rtab->getList();
    }

    private function getGroup($groups, $code)
    {
        foreach($groups as $group) {
            if (!empty($group['code']) && $group['code'] == $code) {
                 return $group;
            }
        }
    }

    /************************************************************
     *   Модификации товара
     *   $modifiacation = [[
     *       'group'=>[
     *           [
     *               'name'=>'Транспорт',
     *               'type'=>'add' //'set', 'mul'
     *               'features'=>['Марка авто','Время']
     *           ]
     *       ],
     *       'offers'=>[[
     *           'values'=> [['Каблук','1ч']]
     *           'article'=>'',
     *           'price'=>null,
     *           'price_opt'=>null,
     *           'price_opt_corp'=>null,
     *           'price_purchase'=>null,
     *           'text'=>'',
     *           'images'=>[]
     *       ]]
     *   ]];
     *
     *
     *
     * Добавляем товары
     * $offers = [[
     *                'groups'=>[],
     *                'article'=>string,
     *                'name'=>string,
     *                'images'=>[],
     *                'note'=>text,
     *                'text'=>text,
     *                'features'=>[],
     *                'modification'=>[],
     *                'price'=>float,
     *                'price_opt'=>float,
     *                'price_opt_corp'=>float,
     *                'price_purchase'=>float,
     *                'title'=>string,
     *                'description'=>text,
     *                'keywords'=>string,
     *                'page_title'=>string
     *            ]]
     *
     *************************************************************/
    public function importOffers($offers)
    {
        //$this->getGroupsCodesIdsPath(); /** подгрузка кодов-ids, путей-ids категорий из БД */
        //$this->getNamesIds(); 
        $this->getBrandIds();
        $this->getGroups();
        //$this->communications();
        foreach ($offers as $offer) {
            // Добавляем товар в базу
            $this->setImportOffer($offer);
        }
        $this->addData();


        $this->inserRelatedProducts();
        return $this->codeId;
    } // ЗАПУСК ИМПОРТА

    private function setImportOffer($offer)
    {
        if (!empty($offer['name']) && !empty($offer['groups'])) {
            // Получаем
            $offer['code'] = (!empty($offer['code'])) ? $offer['code'] : strtolower(se_translite_url($offer['name']));
            $ids_gr = $this->getGroupCodesIds($offer['groups']);
            $Product = array(
                'id_group' => $ids_gr,
                'name' => $offer['name'],
                'article' => $offer['article'],
                'code' => $offer['code'],
                'price' => isset($offer['price']) ? $offer['price'] : NULL,
                'price_opt' => isset($offer['priceOpt']) ? $offer['priceOpt'] : NULL,
                'price_opt_corp' => isset($offer['priceOptCorp']) ? $offer['priceOptCorp'] : NULL,
                'price_purchase' => isset($offer['pricePurchase']) ? $offer['pricePurchase'] : NULL,
                'bonus' => isset($offer['bonus']) ? $offer['bonus'] : NULL,
                'presence_count' => isset($offer['presenceCount']) ? $offer['presenceCount'] : NULL,
                'presence' => isset($offer['presence']) ? $offer['presence'] : NULL,
                'step_count' => isset($offer['stepCount']) ? $offer['stepCount'] : NULL,

                'weight' => isset($offer['weight']) ? $offer['weight'] : NULL,
                'volume' => isset($offer['volume']) ? $offer['volume'] : NULL,

                'measure' => isset($offer['measure']) ? $offer['measure'] : NULL,
                'note' => isset($offer['note']) ? $offer['note'] : NULL,
                'text' => isset($offer['text']) ? $offer['text'] : NULL,
                'curr' => isset($offer['curr']) ? $offer['curr'] : NULL,

                'enabled' => isset($offer['enabled']) ? $offer['enabled'] : NULL,
                'flag_new' => isset($offer['flagNew']) ? $offer['flagNew'] : NULL,
                'flag_hit' => isset($offer['flagHit']) ? $offer['flagHit'] : NULL,
                'is_market' => isset($offer['isMarket']) ? $offer['isMarket'] : NULL,

                'min_count' => isset($offer['minCount']) ? (int)$offer['minCount'] : NULL,
                'id_brand' => isset($offer['brand']) ? $this->createBrand($offer['brand']) : NULL,

                'title' => isset($offer['title']) ? (int)$offer['title'] : NULL,
                'keywords' => isset($offer['keywords']) ? (int)$offer['keywords'] : NULL,
                'page_title' => isset($offer['pageTitle']) ? (int)$offer['pageTitle'] : NULL,
                'description' => isset($offer['description']) ? (int)$offer['description'] : NULL, //$this->get('description', FALSE, $item),
                //'features'       => $this->get('features', FALSE, $item),
                /** смотреть в БД */
            );

            foreach ($Product as $f => $v) {
                if ($v == null) unset($Product[$f]);
            }


            /** 2 Добавляем меры (веса/объема) */
            if (!empty($offer['measuresWeightView'])
                || !empty($offer['measuresWeightEdit'])
                || !empty($offer['measureVolumeView'])
                || !empty($offer['measureVolumeEdit'])
            )
                if (!empty($offer['measuresWeightView']))
                    $this->importData['measure'][$offer['code']]['id_weight_view'] = $this->getTableId('shop_measure_weight', 'name', $offer['measuresWeightView']);
            if (!empty($offer['measures_weight_edit']))
                $this->importData['measure'][$offer['code']]['id_weight_edit'] = $this->getTableId('shop_measure_weight', 'name', $offer['measuresWeightEdit']);
            if (!empty($offer['measuresVolumeView']))
                $this->importData['measure'][$offer['code']]['id_volume_view'] = $this->getTableId('shop_measure_volume', 'name', $offer['measuresVolumeView']);
            if (!empty($offer['measuresVolumeEdit']))
                $this->importData['measure'][$offer['code']]['id_volume_edit'] = $this->getTableId('shop_measure_volume', 'name', $offer['measuresVolumeEdit']);

            if (!empty($offer['related']))
                foreach ($offer['related'] as $relate) {
                    $this->importData['related'][$offer['code']][] = $relate;
                }

            /** 6 обработчик значений/текста в Остатке */
            if (isset($Product['presence_count']) && !(int)$Product['presence_count'] && $Product['presence_count'] != '0') {
                $Product['presence'] = $Product['presence_count'];
                $Product['presence_count'] = -1;
            }

            /** 7 проверка корректности значений и запись в массив */
            $Product = $this->validationValues($Product);

            /** 9 Cверяем наличие характеристик и значений */
            if (!empty($offer['features'])) {
                $Product = $this->creationFeature($Product, $offer['features'], $offer['code']);
            }

            /** 8 Обрабатываем модификации (если есть) */
            if (!empty($offer['modification']))
                $Product = $this->creationModificationsStart($Product, $offer['modification']);
            if (!empty($offer['images']))
                foreach ($offer['images'] as $newImg) {
                    $this->importData['img'][$offer['code']][]['picture'] = $newImg;
                }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Импорт регионов
     * [{
     *    'name'=>string
     *    'address'=>string,
     *    'phone'=>string,
     *    'additional_phones'=>string,
     *    'images'=>string (url),
     *    'description'=>string,
     *    'sort'=>int,
     *    'url'=>string
     * },
     * ]
     * @param array $regions
     */
    public function importRegions($regions = array())
    {
        $ngr = array();  // Идентификациооный список

        foreach ($regions as $region) {
            if (empty($region['name'])) {
                continue;
            }
            $u = new DB('shop_contacts');
            $u->select('id');
            $u->where("name='?'", $region['name']);
            $res = $u->fetchOne();
            if ($res['id']) {
                $region['id'] = $res['id'];
            }

            $u = new DB('shop_contacts');
            $u->setValuesFields($region);
            $id = $u->save();
            $ngr[$region['name']] = $id;
        }
        return $ngr;
    }

    private function setImportGroup($group)
    {
        if (!empty($group['name'])) {
            // Получаем
            if (empty($group['code']))
                $group['code'] = strtolower(se_translite_url($group['name']));
            if (empty($group['id'])) {
                $ids = $this->getGroupCodesIds(array($group['code']));
                if (!empty($ids)) {
                    $group['id'] = $ids[0];
                }
            }

            if (!empty($group['parent'])) $parIds = $this->getGroupCodesIds(array($group['parent']));
            $group['upid'] = (!empty($parIds)) ? $parIds[0] : NULL;
            $group['code_gr'] = $group['code'];
            $u = new DB('shop_group');
            $u->setValuesFields($group);
            $id = $u->save();
            $this->groups[$group['code_gr']] = $id;
            return $id;
        } else {
            return false;
        }
    }

    private function addData()
    {

        /**
         * Добавить данные
         * @param  string mode                        "upd" - обновление, "ins" - вставка, "rld" - вставка с удалением
         * @method childTablesWentTokeys()            ставим ид в ключи массивов
         * @method array priceCodeId(array $products) сверка кодов с БД и получение idБД
         * @method updateListImport()                 импорт товаров - обновление (совпадения заменяет)
         * @method insertListImport()                 импорт товаров - вставка (совпадения игнорирует)
         */

        try {
            if (!empty($this->importData['products'])) {
                $this->importData['products'] = $this->checkCodeId($this->importData['products']);
                $this->insertUpdateListImport(true);
                //elseif ($this->mode=='ins' or $this->mode=='rld') $this->insertUpdateListImport(false);
                DB::query("SET foreign_key_checks = 1");
            }

            $this->updateGroupTable();
            $this->importData = null;

        } catch (Exception $e) {
            echo($e->getMessage());
            //DB::rollBack();
            return FALSE;
        }

        return true;
    } // ДОБАВИТЬ ДАННЫЕ

    public function insertUpdateListImport($update = false)
    {
        /** Лист продуктов на обновление (при отсутствии - добавление; при наличии - обновление (замена))
         * запись ПРОДУКТОВ и СВЯЗЕЙ группа-продукт
         *
         * 1: подмена id в существующих товарах
         * 2: начать транзакцию
         * 3: если id группы не получена (новая руппа) - получаем
         * 4: для shop_price id_group
         *   записываем ПРОДУКТ в бд (ГРУППЫ записаны при вызове CommunGroup в getRightData)
         *   если имеем дело с новой группой
         *   ... или соответственно массивом
         * 5: сверяем id товара с shop_price,    если нет > отправляем к инсету на добавление
         *   если товар отсутствует - создаем
         *   иначе просто изменяем
         *
         * 6: конец транзакции
         * 7: в случае ошибки - прервать транзакцию
         *
         * обновление: нетТовара-добавляет, есть-изменяет
         * вставка:    нетТовара-добавляет, есть-пропускает
         *
         * @param  bool $update true- updateListImport, false- insertListImport
         * @param  bool $refresh true- товар существует в БД
         * @param  bool $processed true- товар обработан
         * @param  array $img изображения по всем товарам
         * @method writeTempFileRelatedProducts(int)запись сопутств. товаров во времен. файл
         */

        $product_list = $this->importData['products'];

        try {
            $refresh = false;
            $idscode = array();
            $ids = array();
            $img = array();
            foreach ($product_list as $k => $product_unit) {
                $processed = false;

                /** 1 подмена id в существующих товарах */
                $code = $product_unit['code'];
                if (!empty($product_unit['newId']) && !empty($product_unit['newId'])) {
                    $id_price = $product_unit['id'] = $product_unit['newId'];
                    unset($product_unit['newId']);
                    $refresh = true;
                }

                //if ($product_unit['code'] != $_SESSION['lastCodePrice']) {

                DB::beginTransaction();
                /** 2 начать транзакцию */
//print_r($product_unit);
                if (gettype($product_unit['id_group']) == 'array' and /** 3 если id группы не получена (новая руппа) - получаем */
                    count($product_unit['id_group'])
                ) $id_group = $this->CommunGroup($product_unit['id_group'][0], FALSE); //[0]

                $data_unit = $product_unit;
                /** 4 */
                if (gettype($product_unit['id_group']) == 'array' and
                    gettype($product_unit['id_group'][0]) == 'array'
                ) $data_unit['id_group'] = $id_group[0];
                elseif (gettype($product_unit['id_group']) == 'array')
                    $data_unit['id_group'] = $data_unit['id_group'][0];

                $pr_unit = new DB('shop_price');

                if ($refresh == true && $update == true) {
                    /** 5 */
                    $id_price = $data_unit['id'];

                    /** если уже есть в БД  - обновляем */
                    if ($this->onlyPrice) {
                        // Обновляем только цены и остатки
                        $data_unut_sp = array('id' => $id_price);
                        if (isset($data_unit['price'])) $data_unut_sp['price'] = $data_unit['price'];
                        if (isset($data_unit['price_opt'])) $data_unut_sp['price_opt'] = $data_unit['price_opt'];
                        if (isset($data_unit['price_opt_corp'])) $data_unut_sp['price_opt_corp'] = $data_unit['price_opt_corp'];
                        if (isset($data_unit['price_purchase'])) $data_unut_sp['price_purchase'] = $data_unit['price_purchase'];
                        if (isset($data_unit['bonus'])) $data_unut_sp['bonus'] = $data_unit['bonus'];
                        if (isset($data_unit['presence_count'])) $data_unut_sp['presence_count'] = $data_unit['presence_count'];
                        if (isset($data_unit['presence'])) $data_unut_sp['presence'] = $data_unit['presence'];
                        if (isset($data_unit['presence_count'])) $data_unut_sp['presence_count'] = $data_unit['presence_count'];
                        
                        
                        if (isset($data_unit['note'])) $data_unut_sp['note'] = $data_unit['note'];
                        if (isset($data_unit['text'])) $data_unut_sp['text'] = $data_unit['text'];
                        
                        $pr_unit->setValuesFields($data_unut_sp);
                        $pr_unit->save(false);
                        $idscode[$code] = $id_price;
                        unset($data_unut_sp);

                    } else {
                        $this->productTables = $this->replacingIdСhildTables($code, $id_price);
                        if (!empty($this->productTables['img']))
                            foreach ($this->productTables['img'] as $k => $i)
                                $img[] = $i;

                        $data_unut_sp = $data_unit;
                        unset($data_unut_sp[0]['features']);
                        $pr_unit->setValuesFields($data_unut_sp);
                        $id_price = $pr_unit->save(false);
                        /** логические отвечают за обновление (при совпадении - замена) */
                        $product_unit['id'] = $id_price;
                        $idscode[$code] = $id_price;
                        unset($data_unut_sp);

                        $this->insertListChildTablesAfterPrice($id_price, false);

                        //if ($this->productTables['accomp'][$id_price])
                        //    $this->writeTempFileRelatedProducts($this->productTables['accomp'][$id_price]);

                        $this->linkRecordShopPriceGroup($product_unit, $id_group, $id_price);
                    }
                    $processed = true;

                } else {

                    /** иначе создаем */
                    $data_unit['id'] = '';
                    /** удаление существующего товара при вставке*/
                    if ($refresh == true && $id_price) {
                        $u = new DB('shop_price', 'sp');
                        $u->where('id=?', $id_price)->deleteList();
                    }
                    $pr_unit->setValuesFields($data_unit);
                    $id_price = $pr_unit->save();
                    $product_unit['id'] = $id_price;
                    $idscode[$code] = $id_price;
                    $this->productTables = $this->replacingIdСhildTables($code, $id_price);
                    if (!empty($this->productTables['img']))
                    foreach ($this->productTables['img'] as $k => $i)
                        $img[] = $i;

                    $this->insertListChildTablesAfterPrice($id_price, true);

                    /** вынимаем номер строки из массива */

                    //if ($this->productTables['accomp'][$id_price])
                    //    $this->writeTempFileRelatedProducts($this->productTables['accomp'][$id_price]);

                    $this->linkRecordShopPriceGroup($product_unit, $id_group, $id_price);

                    $processed = true;
                };

                if ($processed == true) {
                    $_SESSION['lastCodePrice'] = $product_unit['code'];
                    $_SESSION['lastIdPrice'] = $id_price;
                }

                DB::commit();
                /** 6 конец транзакции */
                if ($processed == true) $ids[$code] = $id_price;
                $product_list[$k] = $product_unit;
            };

            if (count($ids) > 0) {
                $this->checkCreateImg($ids, $img);
                $this->creationModificationsFinal($ids);
            }

        } catch (Exception $e) {
            $idscode = array();
            echo 'ОШИБКА!';
            echo $e->getMessage();
            DB::rollBack();
            /** 7 в случае ошибки - прервать транзакцию */
            return false;
        };
        foreach ($idscode as $code => $id_price) {
            $this->codeId[$code] = $id_price;
        }
        return true;
    } // обновление: нет товара-добавление, есть - обновление (замена)


    private function getGroupsCodesIdsPath()
    {
        /** получить данные коды-ids, пути-ids групп в сессию (для генирации кодов новых групп - предотвращения совпадений)
         * 1 запрос на получение данных
         * 2 обработка пути группы
         * 3 заполнение данных сессии
         */


        /** 1 запрос на получение данных */
        $u = new DB('shop_group', 'sg');
        $u->reConnection();
        /** перезагрузка запроса */

        $u->select('sg.id, sg.code_gr, sg.name endname, sgt.level,
					GROUP_CONCAT(CONCAT_WS(\':\', sgtp.level, sgp.name) SEPARATOR \';\') name');
        $u->innerJoin("shop_group_tree sgt", "sgt.id_child = sg.id AND sg.id <> sgt.id_parent
											   OR sgt.id_child = sg.id AND sgt.level = 0");
        $u->innerJoin("shop_group_tree sgtp", "sgtp.id_child = sgt.id_parent");
        $u->innerJoin("shop_group sgp", "sgp.id = sgtp.id_child");
        $u->orderBy('sgt.level');

        $u->groupBy('sg.id');
        $groups = $u->getList();
        unset($u);


        /** 2 обработка пути группы */
        foreach ($groups as $k => $i) {

            $path = '';
            $pathArray = array();
            $dataname = explode(";", $i['name']);

            foreach ($dataname as $k2 => $i2) {
                $ki = explode(":", $i2);
                $pathArray[$ki[0]] = $ki[1];
            }

            foreach (range(0, count($pathArray) - 1, 1) as $number)
                $path .= $pathArray[$number] . "/";

            /** подстановка окончания, а в родительской - удаление слеша */
            if ($i["level"] == 0) $path = substr($path, 0, -1); /** удаление последнего знака (в родительской группе) */
            else                   $path .= $i["endname"];

            unset($groups[$k]['level']);
            unset($groups[$k]['endname']);
            $groups[$k]['name'] = $path;
        }


        /** 3 заполнение данных сессии */
        if (!empty($list))
            foreach ($list as $k => $i) {
                $code_gr = $i['codeGr'];
                $_SESSION["getId"]['code_gr'][$code_gr] = $i['id'];
                $_SESSION["getId"]['path_group'][$code_gr] = $i['id'];
            }

    } // подгрузка кодов-ids, путей-ids категорий из БД

    private function getPricesIds($codes = false)
    {
        $codeId = array();
        $u = new DB('shop_price', 'sp');
        $u->select('sp.id, sp.code');
        if (!empty($codes))
            $u->where('sp.code in ("?")', join('","', $codes));
        $arrayDB = $u->getList();
        foreach ($arrayDB as $k => $i) $codeId[$i['code']] = $i['id'];
        return $codeId;
    }

    private function getNamesIds()
    {
        /** Получить данные по характеристикам и модификациям, получить их значения
         * подгрузка связки имя-id из БД:     1 ... характеристик        2 ... значений характеристик
         *                                    3 ... групп модификаций    4 ... соотношений Характеристик и ГруппМодификаций
         */


        $this->getFeatureInquiry("shop_feature", "sf", "sf.id, CONCAT_WS(':', sf.name, sf.type) name");
        $this->getFeatureInquiry("shop_feature_value_list", "sfvl", "sfvl.id, CONCAT_WS('||', sfvl.id_feature, sfvl.value) name");
        $this->getFeatureInquiry("shop_modifications_group", "smg", "smg.id, smg.name");
        $this->getFeatureInquiry("shop_group_feature", "sgf", "CONCAT_WS(':', sgf.id_group, sgf.id_feature) name, sgf.id");
    } // подгрузка связки имя-id характеристик и модификаций из БД

    private function getBrandIds()
    {
        $codeId = array();
        $u = new DB('shop_brand', 'sb');
        $u->select('sb.id, sb.code');
        $arrayDB = $u->getList();
        foreach ($arrayDB as $k => $i)
            $_SESSION["getId"]['id_brand'][$i['code']] = $i['id'];
    }

    private function getFeatureInquiry($table, $abbreviation, $select)
    {
        /** обычный запрос характеристик, модификаций к БД  [smg]=>aray( [<name>]=><id> ) */
        $u = new DB($table, $abbreviation);
        $u->select($select);
        $list = $u->getList();
        unset($u);
        foreach ($list as $k => $i) $this->feature[$abbreviation][$i['name']] = $i['id'];
    } // обычный запрос характеристик, модификаций к БД

    private function getParamNameId($abv, $name)
    {
        $table = '';
        switch ($abv) {
            case 'sf':
                {
                    $where = "CONCAT_WS(':', sf.name, sf.type)";
                    $table = 'shop_feature';
                    break;
                }
            case 'sfvl':
                {
                    $where = "CONCAT_WS('||', sfvl.id_feature, sfvl.value)";
                    $table = 'shop_feature_value_list';
                    break;
                }
            case 'smg':
                $where = 'smg.name';
                $table = 'shop_modifications_group';
                break;
            case 'sgf':
                {
                    $where = "CONCAT_WS(':', sgf.id_group, sgf.id_feature)";
                    $table = 'shop_group_feature';
                    break;
                }
        }


        if (empty($table)) return;
        /** обычный запрос характеристик, модификаций к БД  [smg]=>aray( [<name>]=><id> ) */
        if (empty($this->feature[$abv][$name])) {
            $u = new DB($table, $abv);
            $u->select($abv . '.id');
            $u->where("{$where}='?'", $name);
            $res = $u->fetchOne();
            unset($u);
            if (!empty($res)) {
                $this->feature[$abv][$name] = $res['id'];
                return $res['id'];
            }
        } else {
            return $this->feature[$abv][$name];
        }
    }


    private function addCategoryMain($code_group, $path_group)
    {
        /** Добавить Категорию / массив категорий
         * @param $code_group нумерованны массив или строка (автопреобразуется в массив)
         * @param $path_group нумерованны массив или строка (автопреобразуется в массив)
         */

        /** унификация (приводим к одному формату) */
        if (gettype($code_group) == string) $code_group = array($code_group);
        if (gettype($path_group) == string) $path_group = array($path_group);
        /** категории приходят массивом */
        if (count($code_group) > 0 or count($path_group) > 0) {
            $list_group = max(count($code_group), count($path_group)) - 1;
            /** перебираем пришедшие группы */
            foreach (range(0, $list_group) as $unit_group) {
                if (!$this->check($code_group[$unit_group], 'code_group') or
                    !$this->check($path_group[$unit_group], 'path_group')
                ) {
                    /** раскладываем путь, создаем группы по пути + код присваиваем ПОСЛЕДНЕЙ группе */
                    $ways = (array)explode('/', $path_group[$unit_group]);
                    $countWays = count($ways) - 1;
                    $list_code = array();
                    $list_code[$countWays] = $code_group;
                    /** проходим по пути проверяя наличие группы, при отсутствие - генерим, получаем id */
                    foreach (range(0, $countWays) as $number) {
                        $this->addCategoryParents($number, $ways, $list_code, $unit_group);
                    }
                }
            }
        }
    } // Добавить Категорию / массив категорий

    private function addCategoryParents($number, $ways, $code_group, $unit_group)
    {
        /** Добавить категорию  (с поддержкой родитель-групп)
         *
         *
         *    @@    @@@@@@  @@@@@@     @@@@@@    @@    @@@@@@@@    @@@@@@    @@    @@@@@@
         *   @@@@   @@   @@ @@   @@    @@       @@@@      @@       @@  @@   @@@@   @@  @@
         *  @@  @@  @@   @@ @@   @@    @@      @@  @@     @@       @@@@@@  @@  @@  @@@@@@
         * @@@@@@@@ @@   @@ @@   @@    @@     @@@@@@@@    @@       @@     @@@@@@@@ @@ @@
         * @@    @@ @@@@@@  @@@@@@     @@@@@@ @@    @@    @@       @@     @@    @@ @@  @@
         *
         *
         * @param int $number - номер ранжирования по пути группы
         * @param array $ways - нумерованный массив, групп из пути разбитый по слешам, нулевой - родительский
         * @param str $code_group - строка, код группы
         * @param int $unit_group - число, порядковое группы в пришедшем массиве
         *
         * 1 определяем потребность в создании группы
         * 2 определение родителя
         * 3 generete group data. принять хотя бы 1 из 2 параметров - остальные сгенерировать, если отсутствуют
         * 4 write DB shop_group, get id group, write id in session
         */


        /**
         * 1 определяем потребность в создании группы
         * @var array $section_groups массив путь на кирилице strtolower(str_ireplace(" ", "-", rus2translit(implode("--", $section_groups)))). Преобразование в "klassicheskie-kovry--kovry"
         */
        $ar_pop_wa = $ways[$number];
        $section_groups = array_slice($ways, 0, $number + 1);
        $path_new_group = implode("/", $section_groups);
        /** имя на кирилице */

        if (!$_SESSION["getId"]["path_group"][$path_new_group]) {

            /** 2 определение родителя */
            if ($number != 0) {
                $ar_pop_wa_par = array_slice($ways, 0, $number);
                $ar_pop_wa_par = implode("/", $ar_pop_wa_par);
                if ($_SESSION["getId"]["path_group"][$ar_pop_wa_par])
                    $pare = $_SESSION["getId"]["path_group"][$ar_pop_wa_par];
                else $pare = NULL;
                // else $this->addCategoryParents($number, $ways, $code_group, $unit_group)
            }


            /** 3 generete group data. принять хотя бы 1 из 2 параметров - остальные сгенерировать, если отсутствуют */
            $parent = NULL;
            $error = False;
            if (!empty($ar_pop_wa)) {
                $name = $ar_pop_wa;
                $parent = $pare;
                $code_gr = $this->generationGroupCode($ar_pop_wa);
            } elseif (!empty($code_group[$unit_group])) {
                $name = $code_group[$unit_group];
                $parent = $pare;
                $code_gr = $this->generationGroupCode($ar_pop_wa);
            } else
                $error = True;


            /** 4 write DB shop_group, get id group, write id in session */
            if ($error != True) {

                $newCat = array(
                    'code_gr' => $code_gr,
                    'name' => $name,
                );
                if ($parent != NULL) $newCat['upid'] = $parent;

                if (!empty($id) or !empty($code_gr) or !empty($name)) {
                    DB::insertList('shop_group', array($newCat), TRUE);
                }

                $u = new DB('shop_group', 'sg');
                $u->select('sg.id');
//                $u->leftJoin('shop_group_tree sgt', 'sgt.id_child = sg.id');
                $u->where("sg.code_gr = '?'", $code_gr);
//                $u->andWhere('sgt.id_parent = ?', $parent);
                $id = $u->fetchOne();
                unset($u);

                $_SESSION["getId"]['code_gr'][$code_gr] = $id['id'];
                $_SESSION["getId"]["path_group"][$path_new_group] = $id['id'];
            }
        }
    } // ДОБАВИТЬ КАТЕГОРИЮ

    private function generationGroupCode($ar_pop_wa)
    {
        /** Генерация кода группы, с сверкой по сесии  $_SESSION["getId"]['code_gr']
         * @param  $ar_pop_wa     имя группы на килилице
         * @param  $ar_pop_wa_new имя группы на латыни
         * @return $code          версия кода "<код>, <код>-1, <код>-2, <код>-3"
         */


        $ar_pop_wa_new = strtolower(se_translite_url($ar_pop_wa));
        $stop = false;
        $num = 0;
        $code = $ar_pop_wa_new;

        while ($stop == false) {
            if (!$_SESSION["getId"]['code_gr'][$code]) {
                return $code;
            } else {
                $num += 1;
                $code = $ar_pop_wa_new . "-" . $num;
            }
        }
    } // Генерация кода группы

    private function check($id, $type = 'category')
    {
        /** Проверить */

        foreach ($this->importData[$type] as $cat)
            if ($cat['id'] == $id) return TRUE;
        return FALSE;
    } // Проверить


    private function communications()
    {
        /** Подготовка данных по связям __товаров__ с __группами__ */


        $u = new DB('shop_group', 'sg');
        $u->reConnection();
        /** перезагрузка запроса */
        if ($_SESSION['coreVersion'] > 520) {
            $u->select('
                sg.id,
                GROUP_CONCAT(
                    sgp.name
                    ORDER BY sgt.level
                    SEPARATOR "/"
                ) name,
                sg.code_gr
            ');
            $u->innerJoin("shop_group_tree sgt", "sg.id = sgt.id_child");
            $u->innerJoin("shop_group sgp", "sgp.id = sgt.id_parent");
            $u->orderBy('sgt.level');
        } else {
            $u->select('sg.*');
            $u->orderBy('sg.id');
        }
        $u->groupBy('sg.id');
        $groups = $u->getList();
        unset($u);

        foreach ($groups as $key => $item) {
            $_SESSION["getId"]["code_group"][$item['codeGr']] = $item['id'];
            $_SESSION["getId"]["path_group"][$item['name']] = $item['id'];
        }
    } // Подготовка данных по связям __товаров__ с __группами__

    private function getTableId($reconTable, $column, $code)
    {
        if (!empty($code)) {
            $u = new DB($reconTable, $reconTable);
            $u->select($reconTable . '.id');
            $u->where($reconTable . '.' . $column . '=?', $code);
            $objects = $u->fetchOne();
            return $objects['id'];
        } else {
            return NULL;
        }
    }


    private static function createBrand($name)
    {

        /**
         * создаем бренд, если не существует
         * @param  str $lObj имя бренда
         * @param  str $key столбец (код)
         * @param  int $id ид бренда в shop_brand
         * @return int $id
         */
        if (empty($_SESSION["getId"]['id_brand'][$name])) {
            try {
                $data['name'] = $name;
                $data['code'] = strtolower(se_translite_url($name));
                $u = new DB('shop_brand');
                $u->setValuesFields($data);
                $id = $u->save();
                $_SESSION["getId"]['id_brand'][$name] = $id;
                /** запоминаем связку в сессии (для быстродействия) */
                return $id;
            } catch (Exception $e) {
                /** "Двухсловный бренд" и "Двухсловный   бренд" - скриптом распозн как разн назван, БД - как одно */
                return null;
            }
        } else {
            return $_SESSION["getId"]['id_brand'][$name];
        }

    } // создаем бренд, если не существует

    private function getIdGroup($key = 'id', $delimiter, $item)
    {
        /** Получение ид группы
         *
         *
         *
         * 1 разбиение переменной на список, если осталась строкой - приводим к общему формату
         * 2 сопоставление вход_списка с данными из таблицы
         *
         * @param $key ключ
         * @param $delimiter разделитель
         * @param $item таблСверки
         * ключ / разделитель / таблСверки / колонка / значение
         */

        if (isset($item[$this->fieldsMap[$key]]) and !empty($item[$this->fieldsMap[$key]])) {
            /** 1 разбиение переменной на список, если осталась строкой - приводим к общему формату */
            $listObj = $item[$this->fieldsMap[$key]];
            if ($delimiter !== FALSE) $listObj = preg_split($delimiter, $listObj);
            if (gettype($listObj) == string or gettype($listObj) == integer) $listObj = array($listObj);

            /** 2 сопоставление вход_списка с данными из таблицы */
            $get = array();
            foreach ($listObj as $lObj) {
                $code = $_SESSION["getId"][$key][$lObj];
                array_push($get, $code);
            }
            if (count($get) == 1)
                $get = $get[0];
            return $get;

        } else
            return NULL;
    } // ПОЛУЧИТЬ ID ГРУППЫ

    private function CommunGroup($offer, $creatGroup)
    {
        /** Создание Групп и Связей с ними товаров
         *
         *
         *
         * 1 получаем данные для обработки
         * 2 унифицируем
         * 3 если данные есть, но абсолютно отсутствует информация по id:
         *   приравниваем $id_gr_cg к массиву для прохождения его через условия
         * 4 проверка совпадения длины не пустых столбцов - если не совпадают, инициализируем 501 ошибку
         * 5 сверяем id с базой (если присутствуют)
         *   ...если отсутствуют, сверяем коды с базой (если присутствуют)
         *   ...если отсутствуют, сверяем имена с базой (если присутствуют)
         *   ...если отсутствуют, добавляем категории и передаем данные для последующей привязки
         *      (если в базе не найдены совпадения)
         * 6 унифицируем конечный результат
         */

        // // фильтрация путей групп - получение значений после последних слешей
        // $listObj = $item[$this->fieldsMap['path_group']];
        // $listObj = preg_split("/,(?!\s+)/ui", $listObj);
        // foreach($listObj as &$lo) {
        //     $lo = explode("/", $lo);
        //     if(gettype($lo) == 'array') $lo = array_pop($lo);
        // }
        // $item[$this->fieldsMap['path_group']] = implode(",", $listObj);

        /** 1 получаем данные для обработки */
        $id_gr_ig = array(NULL);
        if (!empty($_SESSION["idImportGroup"])) {
            return array($_SESSION["idImportGroup"]);
        }

        $code_group = $offer['groups'];

        $id_gr_cg = (!empty($code_group)) ? $this->getGroupCodesIds($code_group) : false;

        /** 2 унифицируем */
        if (gettype($code_group) != 'array' and $code_group != NULL) $code_group = array($code_group);
        /** 3 если данные есть, но абсолютно отсутствует информация по id: ... */
        if (gettype($id_gr_cg) != 'array') $id_gr_cg = array($id_gr_cg);


        /** 4 проверка совпадения длины не пустых столбцов - если не совпадают, инициализируем 501 ошибку */
        $error = FALSE;
        if ($error == TRUE) {
            header("HTTP/1.1 501 Not Implemented");
            echo 'Не корректные данные в импортируемом файле, количество пареметров в столбцах групп не совпадает!';
            exit;
        }

        /**
         * если имеем дело с массивом - отсеиваем группы со всеми пустыми параметрами
         *
         * измеряем длину массивов по id, коду и пути
         * определяем наибольшее значение
         * Array        Array           Array
         * (            (               (
         *     [0] =>       [0] => 12       [0] => dub
         *     [1] =>       [1] => 15       [1] => dizajn-1
         * )            )               )
         * проходим по наибольшему значению ранжированием
         * если все значения в строке "таблицы" пустые - удаляем
         */
        if (gettype($code_group) == 'array') {
            $cou_id_gr_ig = count($id_gr_ig);
            $cou_id_gr_cg = count($id_gr_cg);
            $cou_code_group = count($code_group);
            $cou_min = max($cou_id_gr_ig, $cou_id_gr_cg, $cou_code_group);
            /** инициализируем список NULL id, равный длине $id_gr_cg */
            $id_gr_ig = array_fill(0, $cou_min, NULL);
            $range = range(0, $cou_min - 1, 1);
            foreach ($range as $ran) {
                if ($id_gr_ig[$ran] == NULL and $id_gr_cg[$ran] == NULL and $code_group[$ran] == NULL) {
                    unset($id_gr_ig[$ran]);
                    $id_gr_ig = array_values($id_gr_ig);
                    unset($id_gr_cg[$ran]);
                    $id_gr_cg = array_values($id_gr_cg);
                    unset($code_group[$ran]);
                    $code_group = array_values($code_group);
                };
            };
        };

        /** 5 сверяем id с базой (если присутствуют) ... */
        $id_gr = $id_gr_ig;
        if (gettype($id_gr) == 'array') {
            $start = 0;
            foreach ($id_gr as $i)
                if ($i == NULL) $start = $start + 1;
            if ($start != 0) $id_gr = $id_gr_cg;
        };
        /*
		if(gettype($id_gr) == 'array'){
            $start = 0;
            foreach($id_gr as $i)
                if($i == NULL) $start = $start + 1;
            if($start != 0)    $id_gr = $id_gr_pg;
        };
		*/


        if ($creatGroup == TRUE) {
            if (getType($code_group) == 'array') {

                /** унифицируем */
                if (gettype($id_gr) != 'array') $id_gr = array($id_gr);
                if (getType($code_group) != 'array') $code_group = array($code_group);

                $start = 0;
                foreach ($id_gr as $i)
                    if ($i == NULL) $start = $start + 1;

                if ($start != 0) {
                    $this->addCategoryMain($code_group, $listObj);
                    $id_gr = array($item);
                };
            };
        };

        /** 6 унифицируем конечный результат */
        if (gettype($id_gr) == 'integer') $id_gr = array($id_gr);
        return $id_gr;
    } // СОЗДАНИЕ ГРУПП И СВЯЗЕЙ С ТОВАРАМИ

    private function creationFeature($Product, $features, $code)
    {
        /** Cверяем наличие характеристик и значений <shop_feature> <shop_feature_value_list>.
         *
         *
         * если нет - создаем;
         * Заменяем значения на id
         *
         * @param array $Product все параметры продукта (одной строчки)
         * param array $item нужен для получения id товара
         */
        // Новая версия получения характеристик
        $Product["features"] = array();

        foreach ($features as $k => $f) {
            $feature = array();
            if (empty($f['type'])) $f['type'] = 'string';
            if ($f['type'] == 's') $f['type'] = 'string';
            if ($f['type'] == 'l') $f['type'] = 'list';
            if ($f['type'] == 'b') $f['type'] = 'bool';
            if ($f['type'] == 'n') $f['type'] = 'number';
            if ($f['type'] == 'c') $f['type'] = 'colorlist';
            $feature[$f['name']] = array("value" => $f['value'], "measure" => !empty($f['measure']) ? $f['measure'] : '', "type" => $f['type'], 'group' => $f['group']);

            $Product["features"] = $this->featureReconciliation($feature, "sf", $k);
            $Product["features"] = $this->featureValueReconciliation($Product["features"], "sfvl", $k);
            //print_r($Product["features"]);
            /** выстраиваем формат сохранения */
            $insertListFeatures = array();
            if (!empty($Product["features"]))
                foreach ($Product["features"] as $k => $i) {
                    $value = $i["value"];
                    $inserUnut = array(
                        "id_feature" => $k,
                        "value_string" => null,
                        "value_number" => null,
                        "value_bool" => null,
                        "id_value" => null,
                    );
                    if ($i["type"] == "string") $inserUnut["value_string"] = $value;
                    elseif ($i["type"] == "number") $inserUnut["value_number"] = $value;
                    elseif ($i["type"] == "bool") $inserUnut["value_bool"] = $value;
                    elseif ($i["type"] == "list") $inserUnut["id_value"] = $value;
                    elseif ($i["type"] == "colorlist") $inserUnut["id_value"] = $value;
                    unset($value);
                    $this->importData['features'][$code][] = $inserUnut;
                    //print_r($this->importData['features']);
                    //exit;
                }
            unset($Product["features"]);
        }
        return $Product;
    } // СОЗДАНИЕ ХАРАКТЕРИСТИК Cверяем наличие характеристик и значений

    private function getFeatureGroup($name)
    {
        if (empty($this->featureGroup[$name])) {
            $u = new DB("shop_feature_group");
            $u->select('id');
            $u->where("name='?'", $name);
            $ur = $u->fetchOne();
            if (empty($ur)) {
                $u = new DB("shop_feature_group");
                $newfeat = array('name' => $name);
                $u->setValuesFields($newfeat);
                $id = $u->save();
            } else {
                $id = (int)$ur['id'];
            }
            if ($id) {
                $this->featureGroup[$name] = $id;
                return $id;
            }
            unset($u);
        } else {
            return $this->featureGroup[$name];
        }
    }

    private function featureReconciliation($features, $section)
    {
        /** Проверка наличия характеристики в БД <shop_feature> или ее создание */

        $nameFeatures = array_keys($features);
        foreach ($nameFeatures as $k => $i) {
            if (!empty($i)) {
                $value = $i;
                $type = trim($features[$i]["type"]);
                $measure = trim($features[$i]["measure"]);

                /** ищем связку в сессии - присваиваем id */
                $ident = $value . ':' . $type;
                if ($id = $this->getParamNameId($section, $ident)) {
                    $features[$id] = $features[$value];
                    unset($features[$value]);
                } else {
                    /** если нет характеристики - создаем и добавляем в сессию, присваиваем id */
                    $idFeatureGroup = NULL;
                    if (!empty($features[$i]["group"])) {
                        $idFeatureGroup = $this->getFeatureGroup($features[$i]["group"]);
                    }
                    $newfeat = array('name' => trim($value), 'type' => $type, 'measure' => $measure, 'idFeatureGroup' => $idFeatureGroup);

                    if ($newfeat['name'] && $newfeat['type']) {
                        //DB::insertList('shop_feature', array($newfeat),TRUE);

                        $u = new DB("shop_feature");
                        $u->setValuesFields($newfeat);
                        $id = $u->save();
                        unset($u);

                        //$id = $list[0]['id'];
                        $this->feature[$section][$ident] = $id;
                        $features[$id] = $features[$value];
                    }
                }
                unset($features[$i], $value, $type);
            }
        }

        return $features;
    } // Проверка наличия характеристики в БД

    private function featureValueReconciliation($features, $section)
    {
        /** Проверка наличия значения характеристики в БД <shop_feature_value_list> или ее создание */

        $nameFeatures = $features;

        foreach ($nameFeatures as $k => $i) {
            $value = $i["value"];

            if (!empty($value) and !empty($k)) {
                $type = $i["type"];
                $ident = $value;
                if ($type == 'list' || $type == 'colorlist') {
                    /** ищем связку в сессии - присваиваем id */
                    $ident = $k . '||' . $value;
                    if ($val = $this->getParamNameId($section, $ident)) {
                        $features[$k] = array("value" => $val, "type" => $type, 'text' => $value);
                    } else {
                        /** если нет значения характеристики - создаем и добавляем в сессию, присваиваем id */
                        $newfeat = array('value' => $value, 'id_feature' => $k);
                        if ($newfeat['value'] and $newfeat['id_feature']) {

                            //DB::insertList('shop_feature_value_list', array($newfeat),TRUE);

                            $u = new DB("shop_feature_value_list");
                            $u->setValuesFields($newfeat);
                            $val = $u->save();
                            $this->feature[$section][$k . '||' . $value] = $val;
                            $features[$k] = array("value" => $val, "type" => $type, 'text' => $value, 'new' => '1');
                        }
                    }
                    unset($value, $type);
                }
            }
        }
        return $features;
    } // Проверка наличия значения характеристики в БД

    private function creationModificationsStart($Product, $mods)
    {
        /** создание модификации Главная (группы, самой модификации, параметров), передача данных модификации товара
         *
         *
         * @param array $Product массив для БД с ключами-значениями
         * @param array $item нумерованный массив ячеек строки
         * @param array $_SESSION ["fieldsMap"] массив ключ-номер столбца
         * @param array $mod все параметры модификации
         * @return array $Product              массив для БД с ключами-значениями
         * @return array $this->modData        данные по модификации
         *
         * 1 получаем массив группа,модификация,значение
         * 2 получаем все параметры модификации и удаляем их из основного массива
         *
         * Таблицы:              shop_modifications_group  shop_modifications_img      shop_feature  shop_modifications
         *                       shop_feature_value_list   shop_modifications_feature  shop_price    shop_group_feature
         * Помимо, достаем ids:  shop_img                  shop_price
         */

        /** 1 получаем массив группа,модификация,значение */

        $this->thereModification[$Product['code']] = false;
        $modParam = array();
        if (!empty($mods))
            foreach ($mods as $k => $i) {
                $unit = array(
                    'shop_modifications_group' => $i['group'],
                    'shop_feature' => $i['name'],
                    'shop_feature_value_list' => $i['value'],
                );
                array_push($modParam, $unit);
                $this->thereModification[$Product['code']] = true;
            }

        if (!empty($modParam)) {

            /** 2 получаем все параметры модификации и удаляем их из основного массива */

            $mod = array(
                'price' => $Product['price'],
                'price_opt' => $Product['price_opt'],
                'price_opt_corp' => $Product['price_opt_corp'],
                'price_purchase' => $Product['price_purchase'],
                'bonus' => $Product['bonus'],

                'article' => $Product['article'],
                'presence_count' => $Product['presence_count'],
                'img_alt' => $Product['img_alt'],
                'description' => $Product['description'],
                'mod_param' => $modParam,
            );
            unset($modParam);
            foreach (array_keys($mod) as $i) {
                if ($i == 'article' or $i == 'mod_param' or $i == 'img_alt') {
                } elseif ($i == 'presence_count') $Product[$i] = 0;
                else $Product[$i] = "";
            }
            $this->modData[$Product['code']][] = $mod;
        }
        return $Product;
    } // СОЗДАНИЕ МОДИФИКАЦИИ

    private function creationModificationsFinal($idsPrice)
    {
        /**
         * Cоздание модификации Главная
         *
         * 1 получаем модификации, рассортированные по товарам
         * 2 создаем группы модификаций, модификации, параметры,
         *   соединяем <shop_modifications_group> и <shop_feature> (при их отсутствии)
         * 3 Преобразуем данные к записи в БД
         *
         * @param array $idsPrice массив идТовара-создание(bull)
         * @param boll $this ->thereModification    вкл/выкл генерации модификации
         * @param object $this ->modData            данные для генерации модификаций
         */


        /** 1 получаем модификации, рассортированные по товарам */

        $priceMods = array();
        /** с сортировкой по товарам */
        foreach ($idsPrice as $k => $i)
            if (!empty($this->modData[$k])) $priceMods[$k] = $this->modData[$k];


        if (count($priceMods) > 0) {

            /** 2 создаем группы модификаций, модификации, параметры, соединяем (при их отсутствии) */

            $priceMods = $this->createModifGroup($priceMods);
            /** shop_modifications_group */
            $priceMods = $this->createFeature($priceMods);
            /** shop_feature */
            $priceMods = $this->createModifValue($priceMods);
            /** shop_feature_value_list */
            $this->createCommunMod($priceMods);
            /** shop_group_feature */


            /** 3 Преобразуем данные к записи в БД */

            $priceMods = $this->checkCreatMod($priceMods, $idsPrice);
            $priceMods = $this->createModifications($priceMods, $idsPrice);
            /** заполнение shop_modifications */
            $this->createModFeature($priceMods, $idsPrice);
            /** заполнение shop_modifications_feature */
            $this->createModImg($priceMods, $idsPrice);
            /** заполнение shop_modifications_img */
        }

        unset($priceMods);

    } // создание модификации Главная

    private function createModifGroup($priceMods)
    {
        /** создаем группы модификаций (при их отсутствии)
         * @param array $priceMods все данные
         * @param array $this ->feature name-id данные для сверки присутствия в базе
         */


        /** проверяем наличие, если нет - на добавление */

        $newModGroups = array();
        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $k => $i) {
                $mod = $priceMods[$priceKey][$k];

                foreach ($mod['mod_param'] as $key => $value) {
                    $value = $value['shop_modifications_group'];
                    if (!$this->feature['smg'][$value])
                        $newModGroups[$value] = array('name' => $value, 'vtype' => 2);
                    /** vtype 2 - заменяет цену товара */
                }
            }
        }
        foreach ($newModGroups as $k => $i) {
            array_push($newModGroups, $i);
            unset($newModGroups[$k]);
        }


        /** записываем массив */

        if (count($newModGroups) > 0) {
            DB::insertList('shop_modifications_group', $newModGroups, TRUE);
            unset($this->feature['smg']);
            $this->getFeatureInquiry("shop_modifications_group", "smg", "smg.id, smg.name");
        }


        /** получаем id, подставляем */

        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $k => $i) {
                $mod = $priceMods[$priceKey][$k];

                foreach ($mod['mod_param'] as $key => $value) {
                    $value = $value['shop_modifications_group'];
                    $id = $this->feature['smg'][$value];
                    $mod['mod_param'][$key]['shop_modifications_group'] = $id;
                    $priceMods[$priceKey][$k] = $mod;
                }
            }
        }
        unset($this->feature['smg']);


        return $priceMods;
    } // создаем группы модификаций

    private function createFeature($priceMods)
    {
        /** создаем модификации (при их отсутствии)
         * @param array $priceMods все данные
         * @param array $this ->feature name-id данные для сверки присутствия в базе
         */


        /** проверяем наличие, если нет - на добавление */

        $newMods = array();
        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $k => $i) {
                $mod = $priceMods[$priceKey][$k];

                foreach ($mod['mod_param'] as $key => $value) {
                    $value = $value['shop_feature'];
                    if (!$this->feature['sf'][$value])
                        $newMods[$value] = array('name' => $value, 'type' => 'list');
                }
            }
        }
        foreach ($newMods as $k => $i) {
            array_push($newMods, $i);
            unset($newMods[$k]);
        }


        /** записываем массив */

        if (count($newMods) > 0) {
            DB::insertList('shop_feature', $newMods, TRUE);
            unset($this->feature['sf']);
            $this->getFeatureInquiry("shop_feature", "sf", "sf.id, sf.name");
        }


        /** получаем id, подставляем */

        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $k => $i) {
                $mod = $priceMods[$priceKey][$k];

                foreach ($mod['mod_param'] as $key => $value) {
                    $value = $value['shop_feature'];
                    $id = $this->feature['sf'][$value];
                    $mod['mod_param'][$key]['shop_feature'] = $id;
                    $priceMods[$priceKey][$k] = $mod;
                }
            }
        }
        unset($this->feature['sf']);


        return $priceMods;
    } // создаем модификации

    private function createModifValue($priceMods)
    {
        /** создаем значения модификаций (при их отсутствии)
         * @param array $priceMods все данные
         * @param array $this ->feature name-id данные для сверки присутствия в базе
         */


        /** проверяем наличие, если нет - на добавление */

        $newModValues = array();
        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $k => $i) {
                $mod = $priceMods[$priceKey][$k];

                foreach ($mod['mod_param'] as $key => $value) {
                    $sfvl = $value['shop_feature_value_list'];
                    $sf = $value['shop_feature'];
                    if (!$this->feature['sfvl'][$sfvl])
                        $newModValues[$sfvl . ':' . $sf] = array('value' => $sfvl, 'id_feature' => $sf);
                }
            }
        }
        foreach ($newModValues as $k => $i) {
            array_push($newModValues, $i);
            unset($newModValues[$k]);
        }


        /** записываем массив */

        if (count($newModValues) > 0) {
            DB::insertList('shop_feature_value_list', $newModValues, TRUE);
            unset($this->feature['sfvl']);
            $this->getFeatureInquiry("shop_feature_value_list", "sfvl", "sfvl.id, sfvl.value name");
        }


        /** получаем id, подставляем */

        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $k => $i) {
                $mod = $priceMods[$priceKey][$k];

                foreach ($mod['mod_param'] as $key => $value) {
                    $value = $value['shop_feature_value_list'];
                    $id = $this->feature['sfvl'][$value];
                    $mod['mod_param'][$key]['shop_feature_value_list'] = $id;
                    $priceMods[$priceKey][$k] = $mod;
                }
            }
        }
        unset($this->feature['sfvl']);


        return $priceMods;
    } // создаем значения модификаций

    private function createCommunMod($priceMods)
    {
        /** создаем связи <shop_modifications_group> и <shop_feature> (при их отсутствии)
         * @param array $priceMods все данные
         * @param array $this ->feature name-id данные для сверки присутствия в базе
         */


        /** проверяем наличие, если нет - на добавление */

        $newLigaments = array();
        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $k => $i) {
                $mod = $priceMods[$priceKey][$k];

                foreach ($mod['mod_param'] as $key => $value) {
                    $value = $value['shop_modifications_group'] . ":" . $value['shop_feature'];
                    if (!$this->feature['sgf'][$value]) {
                        $idId = explode(':', $value);
                        $newLigaments[$idId[0] . ':' . $idId[1]] = array('id_group' => $idId[0], 'id_feature' => $idId[1]);

                    }
                }
            }
        }
        foreach ($newLigaments as $k => $i) {
            array_push($newLigaments, $i);
            unset($newLigaments[$k]);
        }


        /** записываем массив */

        if (count($newLigaments) > 0) {
            DB::insertList('shop_group_feature', $newLigaments, TRUE);
            unset($this->feature['sgf']);
            //$this->getFeatureInquiry("shop_group_feature","sgf","CONCAT_WS(':', sgf.id_group, sgf.id_feature) name, sgf.id");
            //unset($this->feature['sgf']);
        }

    } // создаем связи групп модификаций и характеристик

    private function checkCreatMod($priceMods, $idsPrice)
    {
        /** Проверяем наличие модификации по таблице <shop_modifications_feature>
         * 1 генерируем ключ, например: 47662:148,47663:151
         * 2 Сверяем ключ с БД
         */


        /** 1 генерируем ключ, например: 47662:148,47663:151 */

        foreach ($priceMods as $priceCode => $priceUnit) {
            foreach ($priceUnit as $key => $val) {
                $mod = $priceMods[$priceCode][$key];

                $keyHaving = '';
                $keyArray = array();
                foreach ($mod['mod_param'] as $k => $i)
                    $keyArray[$i['shop_feature']] = $i['shop_feature'] . ':' . $i['shop_feature_value_list'];
                ksort($keyArray);
                foreach ($keyArray as $k => $i) $keyHaving = $keyHaving . $i . ',';
                $keyHaving = substr($keyHaving, 0, -1);

                $priceMods[$priceCode][$key]['keyHaving'] = $idsPrice[$priceCode] . '#' . $keyHaving;
            }
        }


        /** 2 Сверяем ключ с БД.  ответ БД через query # ответ query */

        $idsProdStr = implode(",", $idsPrice);
        $ca = DB::query("SELECT
                           smf.id,
                           smf.id_price,
                           smf.id_modification idmod,
                           GROUP_CONCAT(smf.id_feature, ':', smf.id_value ORDER BY smf.id_feature) AS ff
                         FROM shop_modifications_feature smf
                         WHERE smf.id_price IN ($idsProdStr) AND smf.id_modification IS NOT NULL
                         GROUP BY smf.id_modification");
        $ca->setFetchMode(PDO::FETCH_ASSOC);
        $checkArray = $ca->fetchAll();


        /** генерируем <shopPriceId>#<shopFeatureId>:<shopFeatureValueListId>,... => <shopModificationsFeatureId> */

        foreach ($checkArray as $k => $i) {
            $key = $i['id_price'] . '#' . $i['ff'];
            $checkArray[$key] = $i['idmod'];
            unset($checkArray[$k]);
        }

        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $key => $val) {
                $mod = $priceMods[$priceKey][$key];

                $keyHaving = $mod['keyHaving'];
                if ($checkArray[$keyHaving]) {
                    $priceMods[$priceKey][$key]['idModification'] = $checkArray[$keyHaving];
                    $priceMods[$priceKey][$key]['createMod'] = false;
                } else {
                    $priceMods[$priceKey][$key]['idModification'] = null;
                    $priceMods[$priceKey][$key]['createMod'] = true;
                }
            }
        }
        return $priceMods;
    } // проверка наличия модификации <shop_modifications_feature>

    private function createModifications($priceMods, $idsPrice)
    {
        /** заполнение shop_modifications
         * 1 получаем все параметры
         * 2 получаем ids cуществующих записей shop_modifications
         * 3 записываем (если отсутствует)
         * 4 запрашиваем <idPrice>##<idModGroup> => <id> (с фильтрацией по идТовара)
         * 5 заполняем $priceMods ids модификаций
         */


        /** 1 получаем все параметры */

        $newShopMod = array();
        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $key => $val) {
                $mod = $priceMods[$priceKey][$key];
                $idPrice = $idsPrice[$priceKey];
                $priceMods[$priceKey][$key]['idPrice'] = $idPrice;

                if ($mod['createMod'] == true) {

                    $shopModifGroup = "";
                    foreach ($mod['mod_param'] as $k => $i) {
                        if (!empty($i['shop_modifications_group'])) {
                            $shopModifGroup = $i['shop_modifications_group'];
                            break;
                        }
                    }

                    /** заполняем, ставим заглушки в поля с null перед записью */
                    $idPrice = $idsPrice[$priceKey];
                    $shMods = array(
                        'id_mod_group' => $shopModifGroup ? $shopModifGroup : null,
                        'id_price' => $idPrice ? $idPrice : null,
                        'code' => $mod['article'] ? $mod['article'] : "",
                        'value' => $mod['price'] ? $mod['price'] : 0,
                        'value_opt' => $mod['price_opt'] ? $mod['price_opt'] : 0,
                        'value_opt_corp' => $mod['price_opt_corp'] ? $mod['price_opt_corp'] : 0,
                        'value_purchase' => $mod['price_purchase'] ? $mod['price_purchase'] : 0,
                        'count' => $mod['presence_count'] ? $mod['presence_count'] : -1,
                        'description' => $mod['description'] ? $mod['description'] : "");

                    /** в массив */
                    if ($shMods['id_mod_group'] != null or $shMods['id_price'] != null) {
                        array_push($newShopMod, $shMods);
                    }
                }
            }
        }


        /** 2 получаем ids cуществующих записей shop_modifications */

        $u = new DB("shop_modifications", "sm");
        $u->select("sm.id");
        $exclusionList = $u->getList();
        $excListStr = "";
        foreach ($exclusionList as $k => $i) {
            $excListStr = $excListStr . $i['id'] . ",";
            unset($exclusionList[$k]);
        }
        $excListStr = substr($excListStr, 0, -1);
        unset($u, $exclusionList);


        /** 3 записываем */

        //$u = new DB("shop_modifications", "sm");
        //$u->setValuesFields($shMods);
        //$idModification = $u->save();

        if (count($newShopMod) > 0)
            DB::insertList('shop_modifications', $newShopMod, TRUE);


        /** 4 запрашиваем <idPrice>##<idModGroup> => <id> (с фильтрацией по идТовара) */

        $idsProductsSrt = implode(",", $idsPrice);
        $u = new DB("shop_modifications", "sm");
        $u->select("sm.id, sm.id_price, sm.id_mod_group,
            sm.value price, sm.value_opt price_opt, sm.value_opt_corp price_opt_corp, sm.value_purchase price_purchase,
            sm.count presence_count");
        $u->where("sm.id_price IN ($idsProductsSrt)");
        if ($excListStr != "") $u->andWhere("sm.id NOT IN ($excListStr)");
        $l = $u->getList();
        $list = array();
        foreach ($l as $k => $i) {
            $key = intval($i['idPrice']) . '##' .
                intval($i['idModGroup']) . '##' .
                number_format($i['price'], 2, '.', '') . '##' .
                number_format($i['priceOpt'], 2, '.', '') . '##' .
                number_format($i['priceOptCorp'], 2, '.', '') . '##' .
                number_format($i['pricePurchase'], 2, '.', '') . '##' .
                intval($i['presenceCount']);
            $unit = array('key' => $key, 'id' => $i['id']);
            array_push($list, $unit);
            unset($l[$k]);
        }
        unset($u, $l, $key, $idsProductsSrt, $idsProducts);


        /** 5 заполняем $priceMods ids модификаций */

        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $key => $val) {
                $mod = $priceMods[$priceKey][$key];

                $keyParamGr = intval($mod['idPrice']) . '##' .
                    intval($mod['mod_param'][0]['shop_modifications_group']) . '##' .
                    number_format($mod['price'], 2, '.', '') . '##' .
                    number_format($mod['price_opt'], 2, '.', '') . '##' .
                    number_format($mod['price_opt_corp'], 2, '.', '') . '##' .
                    number_format($mod['price_purchase'], 2, '.', '') . '##' .
                    intval($mod['presence_count']);

                foreach ($list as $k => $i) {
                    if ($i['key'] == $keyParamGr) {
                        $priceMods[$priceKey][$key]['idModification'] = $i['id'];
                        unset($list[$k]);
                        break;
                    }
                }
            }
        }


        return $priceMods;

    } // заполнение shop_modifications

    private function createModFeature($priceMods, $idsPrice)
    {
        /** Записываем значения модификации  <shop_modifications_feature> */


        $newFeatures = array();
        foreach ($priceMods as $priceCode => $priceUnit) {
            foreach ($priceUnit as $key => $val) {
                $mod = $priceMods[$priceCode][$key];

                $priceKey = $idsPrice[$priceCode];
                if ($mod['createMod'] == true and $mod['idModification'] != null) {
                    foreach ($mod['mod_param'] as $k => $i) {
                        $shopModificationsFeature = array(
                            'id_price' => $priceKey, 'id_modification' => $mod['idModification'], 'id_feature' => $i['shop_feature'],
                            'id_value' => $i['shop_feature_value_list']);
                        $keyShopModFeat = '';
                        foreach ($shopModificationsFeature as $KSMF => $iSMF) $keyShopModFeat = $keyShopModFeat . '#' . $iSMF;
                        $newFeatures[$keyShopModFeat] = $shopModificationsFeature;
                    }
                }
            }
        }
        foreach ($newFeatures as $k => $i) {
            array_push($newFeatures, $i);
            unset($newFeatures[$k]);
        }

        if (count($newFeatures) > 0) {
            DB::insertList('shop_modifications_feature', $newFeatures, TRUE);
        }

    }  // Записываем значения модификации

    private function createModImg($priceMods, $idsPrice)
    {
        /** Привязываем изображения к модификациям  <shop_modifications_img>
         * 1 получаем имяРисунка-ид (с фильтрацией по идТовара)
         * 2 заполняем массив на отправку в БД
         * 3 Чистим таблицу картинок модификаций перед записью
         * 4 записываем в <shop_modifications_img>
         */


        /** 1 получаем имяРисунка-ид (с фильтрацией по идТовара) */


        $idsProductsSrt = implode(",", $idsPrice);
        $u = new DB("shop_img", "si");
        $u->select("si.id, si.picture, sp.code");
        $u->where("si.id_price IN ($idsProductsSrt)");
        $u->innerjoin('shop_price sp', 'sp.id = si.id_price');
        $l = $u->getList();
        $list = array();
        $key = '';
        foreach ($l as $k => $i) {
            $key = $i['code'] . '##' . $i['picture'];
            $list[$key] = $i['id'];
            unset($l[$k]);
        }
        unset($u, $l, $key, $idsProductsSrt, $idsProducts);


        /** 2 заполняем массив на отправку в БД */

        $newImgs = array();
        $idModifications = array();
        foreach ($priceMods as $priceKey => $priceUnit) {
            foreach ($priceUnit as $key => $val) {
                $mod = $priceMods[$priceKey][$key];

                if ($mod['img_alt'] != '') {
                    $imgs = $mod['img_alt'];
                    is_array($imgs) ?: $imgs = array($imgs);


                    /** заполняем форму на отправку */

                    $numSort = 0;
                    foreach ($imgs as $k => $i) {
                        $shopModificationsImg = array('id_modification' => $mod['idModification'],
                            'id_img' => $list[$priceKey . '##' . $i],
                            'sort' => $numSort);
                        if (!empty($mod['idModification']) and !empty($list[$priceKey . '##' . $i])) {
                            array_push($newImgs, $shopModificationsImg);
                            $numSort = $numSort + 1;
                        }
                    }
                    array_push($idModifications, $mod['idModification']);
                }
            }
        }


        /** 3 Чистим таблицу картинок модификаций перед записью */

        $idModificationsStr = implode(",", $idModifications);
        if ($idModificationsStr != '') {
            $u = new DB('shop_modifications_img', 'smi');
            $u->where('id_modification IN (?)', $idModificationsStr)->deletelist();
        }

        /** 4 записываем в <shop_modifications_img> */

        if (count($newImgs) > 0) {
            DB::insertList('shop_modifications_img', $newImgs, TRUE);
        }

    } // Привязываем изображения к модификациям


    private function validationValues($Product)
    {
        /** проверка корректности значений и запись в массив
         * @return array $this->importData отправка товаров на сохранение
         * @return array $Product отправка данных для дальнейшей обработки
         */

        $Product = $this->notText($Product, 'price_opt', 'float', true);
        $Product = $this->notText($Product, 'price', 'float', true);
        $Product = $this->notText($Product, 'price_opt_corp', 'float', true);
        $Product = $this->notText($Product, 'price_purchase', 'float', true);
        $Product = $this->notText($Product, 'bonus', 'float', true);
        $Product = $this->bool($Product, 'enabled', false, false);
        $Product = $this->bool($Product, 'flag_new', false, false);
        $Product = $this->bool($Product, 'is_market', true, false);
        $Product = $this->bool($Product, 'flag_hit', false, false);
        $Product = $this->notText($Product, 'step_count', 'int', false);
        $Product = $this->notText($Product, 'weight', 'float', true);
        $Product = $this->notText($Product, 'volume', 'float', true);
        $Product = $this->notText($Product, 'min_count', 'int', true);

        // при пустом поле значение переменной $id==NULL
        if (!empty($Product['code']) and !empty($Product['name'])) {
            $this->importData['products'][] = $Product;

        } else if (!empty($Product['code']) and empty($Product['name'])) {
            /** если имя пустое - подставить код (URL) */
            $Product['name'] = $Product['code'];

            $this->importData['products'][] = $Product;

        }
        return $Product;

    } // ПРОВЕРКА КОРРЕКТНОСТИ ЗНАЧЕНИЙ

    private static function notText($data, $col, $iF, $zero)
    {
        /**
         * проверка числового не отрицательного (большего, чем ноль) значения; в случае не совпадения - замена на значения
         * @param array $data данные по товару
         * @param string $col обозначение колонки в БД
         * @param int $line номер строки
         * @param string $name обозначение колонки в файле
         * @param string $iF новый формат значения: int / float
         * @param bool $zero при ошибке приравнять к: true-0 false-1
         * @param int $subs замена
         * @param string $text текст-подстановка
         */
        //print_r($data);
        if (!isset($data[$col])) return $data;


        $v = $data[$col];
        if ($zero == true) {
            $subs = 0;
            $text = 'значения меньшие чем ноль';
        } /** приравнять к нулю */
        else {
            $subs = 1;
            $text = 'значения меньшие или равные нулю';
        }
        /** приравнять к одному */

        if ($zero ?
            (!(int)$v and $v != '0' and !empty($v) or (float)$v < 0) :
            (!(int)$v and $v != '0' and !empty($v) or (float)$v < 0 or $v == '0')
        ) {
            $v = $subs;
        } elseif (empty($v)) $v = $subs;

        if ($iF == 'int') $data[$col] = (int)$v;
        elseif ($iF == 'float') $data[$col] = round((float)$v, 2);

        return $data;
    } // проверка числового не отрицательного / большего, чем ноль

    private static function bool($data, $col, $oneYes, $yesNo)
    {
        /**
         * проверка логического значения; в случае не совпадения - замена на значения
         * @param array $data данные по товару
         * @param string $col обозначение колонки в БД
         * @param int $line номер строки
         * @param string $name обозначение колонки в файле
         * @param bool $oneYes новый формат значения: true-0/1 или false-Y/N
         * @param bool $yesNo при ошибке приравнять к: true-Y/1 false-N/0
         * @param        $subs    замена, приравнять к ...
         * @param string $text текст-подстановка
         */

        if (!isset($data[$col])) return $data;
        $v = $data[$col];

        if ($oneYes == true) {
            $text = '0/1';
            if ($yesNo == true) $subs = 1;
            else              $subs = 0;
        } else {
            $text = 'Y/N';
            if ($yesNo == true) $subs = 'Y';
            else              $subs = 'N';
        }

        if ($oneYes ?
            ($v != '1' and $v != '0') :
            ($v != 'Y' and $v != 'N')
        ) {
            $v = $subs;
        } elseif (empty($v)) $v = $subs;

        if ($oneYes == true) $data[$col] = (int)$v;
        elseif ($oneYes == false) $data[$col] = $v;

        return $data;
    }

    private function getGroups()
    {
        $u = new DB('shop_group');
        $u->select('id, code_gr');
        $result = $u->getList();
        foreach ($result as $gr) {
            $_SESSION["getId"]['id_group'][$gr['codeGr']] = $gr['id'];
        }
    }

    // Получим список ID групп по массивы групп
    private function getGroupCodesIds($groups = array())
    {
        $result = array();
        if (!empty($groups) && !is_array($groups)) $groups = array($groups);
        foreach ($groups as $code_group) {
            if (empty($this->groups[$code_group])) {
                $u = new DB('shop_group');
                $u->select('id');
                $u->where("code_gr='?'", $code_group);
                $ur = $u->fetchOne();
                if (!empty($ur)) {
                    $result[] = $this->groups[$code_group] = $ur['id'];
                }
            } else {
                $result[] = $this->groups[$code_group];
            }
        }
        return $result;
    }

    private function getPriceCodeId($code)
    {
        if (empty($this->codeId[$code])) {
            $u = new DB('shop_price');
            $u->select('id');
            $u->where("code='?'", $code);
            $ur = $u->fetchOne();
            if (!empty($ur)) {
                $this->codeId[$code] = $ur['id'];
                return $ur['id'];
            } else {
                return false;
            }
        } else {
            return $this->codeId[$code];
        }
    }

    private function checkCodeId($products)
    {

        /**
         * получение ид - кодов по кодам товаров
         * @param  array $products импортируемые данные для таблицы shop_price
         * @return array  $products импортируемые данные для таблицы shop_price
         */
        foreach ($products as &$product) {
            if ($id = $this->getPriceCodeId($product['code'])) {
                $product['newId'] = $id;
            }
        }
        return $products;

    } // получение ид - кодов по кодам товаров

    private function replacingIdСhildTables($oldId, $newId)
    {
        /**
         * замена id в дочерних таблицах и главной
         * @param  array $this ->importData  импортируемые таблицы
         * @param  array $NID newImportData новый массив с новыми ids
         * @param  array $KTN kTableName    имя таблицы
         * @param  array $VTV valuesTV      значения таблицы
         * @return array $TID               импортируемые таблицы
         */

        $NID = array();

        foreach ($this->importData as $KTN => $tableValue) {
            if ($KTN != 'products') {

                foreach ($tableValue as $oldIdTV => $VTV) {
                    if ($oldIdTV == $oldId) {

                        if (array_key_exists('id_price', $VTV)) {
                            $VTV['id_price'] = $newId;

                        } else {
                            foreach ($VTV as $k => $i) {
                                if (!empty($i) && is_array($i))
                                    if (array_key_exists('id_price', $i)) {
                                        $VTV[$k]['id_price'] = $newId;
                                    }
                            }

                        }

                        $NID[$KTN][$newId] = $VTV;
                    }
                }
            }
        }

        return $NID;

    } // замена id в дочерних таблицах и главной

    private function deleteCategory($id_price, $id_group)
    {
        /** Удалить категорию */
        $spg = new DB('shop_price_group', 'spg');
        $spg->select('spg.*');
        $spg->where('id_price = ?', $id_price);
        $spg->andWhere('id_group = ?', $id_group);
        $spg->deleteList();
    } // Удалить категорию


    private static function checkCreateImg($ids, $faileImgs)
    {
        /** Проверить есть ли изображения у товара - если нет, создать
         * @param array $faileImgs данные по изображениям  [<id>]=>{[0..]=>(id_price, picture, default)}
         * @param num $ids ид товаров к которым привязаны изображения
         * @param bool $isFile true - файл корректен  false - не корректен
         * @param array $exten возможные расширения файлов
         */


        /** 1 получаем Корректные изображ товаров из файла, приводим к нум-массиву */
        $Imgs = array();

        foreach ($faileImgs as $k => $i)
            foreach ($i as $k2 => $i2) {

                $exten = array('.jpg', '.jpeg', '.jpe', '.png', '.tif', '.tiff', '.gif', '.bmp', '.dib', '.psd');
                $isFile = false;
                foreach ($exten as $kEX => $iEX)
                    if (strpos(' ' . $i2['picture'], $iEX)) {
                        $isFile = true;
                        break;
                    }

                if ($isFile) {
                    unset($i2['lineNum']);
                    if (!empty($i2)) array_push($Imgs, $i2);
                } elseif (count($Imgs) != 0 && !empty($i['picture']))
                    if (!$_SESSION['errors']['img_alt']) $_SESSION['errors']['img_alt'] = 'ПРИМЕЧАНИЕ[стр. ' . $i2['lineNum'] . ']: столбец "Изображения" не корректное расширение файла';
            }


        /** 2 получаем изображения товара из БД */
        $ids = implode(",", $ids);
        $u = new DB("shop_img", "si");
        $u->select("si.id, si.picture, si.id_price");
        $u->where('si.id_price in (?)', $ids);
        $l = $u->getList();
        $list = array();
        foreach ($l as $k => $i) {
            $key = $i['idPrice'] . "##" . $i['picture'];
            $list[$key] = $i['id'];
            unset($l[$k]);
        }
        unset($u, $l);


        /** 3 отбираем отсутсвующие рисунки */
        if (isset($Imgs) && !is_array($Imgs)) $Imgs = array($Imgs);
        $faileImgsTemp = array();

        if (!empty($Imgs))
        foreach ($Imgs as $k => $i) {
            $key = $ids . "##" . $i['picture'];
            if (isset($list[$key]) && !empty($i) && !$list[$key])
                array_push($faileImgsTemp, $i);
            unset($Imgs[$k]);
        }
        $Imgs = $faileImgsTemp;
        unset($faileImgsTemp);


        /** 4 определяем главное изображение и возвращаем на запись */
        if (count($list) == 0 && !empty($Imgs[0])) $Imgs[0]["default"] = 1;

        if (count($Imgs) > 0)
            DB::insertList("shop_img", $Imgs, true);

    } // Проверить есть ли изображения у товара - если нет, создать

    private function insertListChildTablesAfterPrice($id, $setValuesFields = false)
    {
        /** Заполнение дочерних таблиц После заполнения <shop_price>    1 импорт характеристик    2 импорт изображений*/
        $tableNames = array(
            'category' => array('table' => 'shop_group'),
            'measure' => array('table' => 'shop_price_measure'),
            'features' => array(
                'table' => 'shop_modifications_feature',
                'revise' => 'id_price, id_modification, id_feature,id_value',
                'keyRevise' => 'id_price',
                'empty' => 'id_modification' // проверка пустоту поля
            ),
            'img' => array('table' => 'shop_img'),
        );

        $this->insertListChildTables($id, $tableNames, $setValuesFields);
    } // Заполнение дочерних таблиц После заполнения <shop_price>

    private function insertListChildTables($id, $tableNames, $setValuesFields = false)
    {
        /** Форма заполнения дочерних таблиц
         * @param array $this ->productTables именной массив данных из строки (разбитые по таблицам ключТабл) по одному товару
         * @param int $id ид товара к которому привязаны записи таблиц
         * @param bool $setValuesFields true=изменяемЗапись  false=создаем
         * @param array $tableNames ключТабл=> table=имяТабл, revise=поляСверки, keyRevise=id_price, empty=проверкаПустоты
         * @param array $tableData массив данных для записи в БД
         * @param str $tab имя таблицы
         */

        /** id в ключи в массива <measure,features,prepare...> */

        $keyTable = array_keys($this->productTables);

        foreach ($keyTable as $k => $i) {
            if ($tableNames[$i]['table'] && $this->productTables[$i][$id]) {
                $tableData = $this->productTables[$i][$id];
                $tableData[0] ?: $tableData = array($tableData);

                $uu = new DB($tableNames[$i]['table']);
                $uu->select('id_price');
                $uu->where('id_price=?', $id);
                $is_table = $uu->fetchOne();
                if (!empty($is_table)) {
                    // Удаляем прежние значения
                    $u = new DB($tableNames[$i]['table']);
                    $u->where('id_price=?', $id);
                    $u->deleteList();
                }
                if (!empty($tableData))
                    foreach ($tableData as &$data) {
                        $data['id_price'] = $id;
                    }
                DB::insertList($tableNames[$i]['table'], $tableData, true);
            }
        }
    } // Форма заполнения дочерних таблиц

    public function linkRecordShopPriceGroup($product_unit, $id_group, $id_price)
    {
        /**
         * ЗАПОЛНЕНИЕ shop_price_group
         *
         * Определяем потребность в очистке связей и удаляем не актуальные
         *
         * 1: если значение одно - завернуть в массив для обработки
         *   если значения не соотнесены (отсутствовали данные по id) - совершить вторую попытку
         * 2: получаем данные из базы для определения изменений
         * 3: раскладываем продукт на связи (с группами)
         *   4: формируем данные по импортируемой связи;  определяем главную/второстепенные связи
         *   5: группируем параметры связи
         *   6: сопоставляем параметры с бд
         *   7: если есть хотя бы одно совпадение по бд - отменяем
         * 8: сверка связей с созданым белым листом - в случае не обнаружения среди белых, УДАЛЯЕТ СВЯЗЬ
         *
         * Запись связей в таблицу shop_price_group
         *
         * 9: получаем данные из shop_price_group для последующей сверки данны с id-шниками (проверка на наличие)
         *   ОБЯЗАТЕЛЬНО ПОСЛЕ ОЧИСТКИ ТАБЛИЦЫ!
         * 10: получение элементов массива с определением главной группы
         *   11: если группа первая в списке - значит главная
         *   12: ищим id в базе - есть, добавляем в shop_price_group
         *
         * @param array $product_unit массив параметров $fields по единице товара
         * @param integer $id_group ид группы
         * @param integer $id_price ид товара
         */


        if (gettype($product_unit['id_group']) == 'integer') {
            $product_unit['id_group'] = array($product_unit['id_group']);
            /** 1 */
        } elseif (gettype($product_unit['id_group']) == 'array' and gettype($product_unit['id_group'][0]) == 'array') {
            $product_unit['id_group'] = $id_group;
        }

        if (is_numeric($id_price) and isset($product_unit['id_group'])) {

            $pr_gr = new DB('shop_price_group');
            /** 2 */
            $pr_gr->select('*');
            $pr_gr->where('id_price = ?', $id_price);
            $pr_gr_list = $pr_gr->getList();


            if ($pr_gr_list != NULL) {
                $white_list = array();
                $cycle = 0;
                foreach ($product_unit['id_group'] as $id_gr_unit) {
                    /** 3 */
                    if ($cycle == 0) $is_main = (int)1; /** 4 */
                    else             $is_main = (int)0;
                    $category_unit = array(/** 5 */
                        'id' => NULL,
                        'idPrice' => (int)$id_price,
                        'idGroup' => (int)$id_gr_unit,
                        'isMain' => (bool)$is_main
                    );
                    foreach ($pr_gr_list as $pr_gr_unit) {
                        /** 6 */
                        if ($category_unit['idPrice'] == $pr_gr_unit['idPrice'] and
                            $category_unit['idGroup'] == $pr_gr_unit['idGroup'] and
                            $category_unit['isMain'] == $pr_gr_unit['isMain']
                        ) $category_unit['id'] = $pr_gr_unit['id'];
                    };
                    if ($category_unit['id'] != NULL) $white_list[] = $category_unit;
                    /** 7 */

                    $cycle = $cycle + 1;
                };

                $delete_id_list = array();
                /** 8 */
                foreach ($pr_gr_list as $pr_gr_unit) {
                    if ($pr_gr_unit['idPrice'] == $id_price) {
                        $delete_confirmation = (int)1;
                        foreach ($white_list as $white_unit) {
                            if ($white_unit['id'] == $pr_gr_unit['id']) $delete_confirmation = (int)0;
                        };
                        if ($delete_confirmation == 1) $delete_id_list[] = $pr_gr_unit['id'];
                    };
                };
                if (count($delete_id_list) > 0) {
                    $spg = new DB('shop_price_group', 'spg');
                    $spg->select('spg.*');
                    $spg->where('id IN (?)', join(',', $delete_id_list));
                    $spg->deleteList();
                };
            };

            $pr_gr_list_delete = array();
            /** 9 */
            foreach ($pr_gr_list as &$pr_gr_unit) {
                if (in_array($pr_gr_unit['id'], $delete_id_list) == FALSE) $pr_gr_list_delete[] = $pr_gr_unit;
            };
            $pr_gr_list = $pr_gr_list_delete;

            if (isset($product_unit['id'], $product_unit['id_group'][0])) {
                /** 10 */
                foreach ($product_unit['id_group'] as $id_gr_unit) {
                    if (isset($product_unit['id'], $id_gr_unit)) {
                        $category_unit = array(
                            'id' => NULL,
                            'idPrice' => (int)$id_price,
                            'idGroup' => (int)$id_gr_unit,
                            'isMain' => (bool)0
                        );
                        if ($id_gr_unit == $product_unit['id_group'][0]) /** 11 */
                            $category_unit['isMain'] = (bool)1;
                        if ($pr_gr_list != NULL) {
                            foreach ($pr_gr_list as $pr_gr_unit) {
                                /** 12 */
                                if ($category_unit['idPrice'] == $pr_gr_unit['idPrice'] and
                                    $category_unit['idGroup'] == $pr_gr_unit['idGroup'] and
                                    $category_unit['isMain'] == $pr_gr_unit['isMain']
                                ) $category_unit['id'] = $pr_gr_unit['id'];
                            };
                        };
                        $pr_gr = new DB('shop_price_group');
                        $pr_gr->setValuesFields($category_unit);
                        $pr_gr->save();
                    };
                };
            };
        };
    } // ЗАПОЛНЕНИЕ shop_price_group

    private function writeTempFileRelatedProducts($relatedProducts)
    {
        /**
         * запись сопутствующих товаров в конец временного файла
         *
         * @param array $relatedProducts массив сопутств. товаров по обрабат. товару
         * @param string $path путь времен. файла
         * @param string $idCode связка <id создан. товара>,<code сопутств. товара>
         */


        foreach ($relatedProducts as $k => $i) {
            $idCode = $i['id_price'] . "," . $i['code_acc'] . "\n";
            if (!empty($idCode)) {
                $path = DOCUMENT_ROOT . "/files/tempfiles/relatedProducts.TMP";
                file_put_contents($path, $idCode, FILE_APPEND);
            }
        }
    } // запись сопутств. тов. в конец времен. файла

    private function inserRelatedProducts()
    {
        /**
         * запись сопутствующих товаров в БД
         *
         * @param string $path путь времен. файла
         * @param string $line строка из файла
         * @param string $idCode связка <id создан. товара>,<code сопутств. товара>
         * @param array $DBunit форма отправки данных в БД
         * @param array $arrayDB список на отправку insertList
         * @param array $idDelete список ids товар. на очистку табл. сопутств. тов. (shop_accomp)
         */
        $cods = array();
        if (!empty($this->importData['related']))
            foreach ($this->importData['related'] as $code => $rels) {
                $cods[] = $code;
                foreach ($rels as $its) {
                    if (!in_array($its, $cods)) $cods[] = $its;
                }
            }
        $codeId = $this->getPricesIds($cods);


        if (!empty($this->importData['related'])) {
            $arrayDB = array();
            $idDelete = array();
            foreach ($this->importData['related'] as $code => $relates) {
                foreach ($relates as $relate) {
                    $DBunit = array('id_price' => $codeId[$code], 'id_acc' => $codeId[$relate]);
                    array_push($arrayDB, $DBunit);
                    array_push($idDelete, $DBunit['id_price']);
                    if (count($arrayDB) >= 1000) {
                        $idsStr = implode(",", $idDelete);
                        $u = new DB('shop_accomp', 'sa');
                        $u->where("id_price IN (?)", $idsStr)->deleteList();

                        $arrayDB = $this->codesInIdRelated($arrayDB);

                        DB::insertList('shop_accomp', $arrayDB);
                        $arrayDB = array();
                        $idDelete = array();
                    }
                }
            }

            if (count($arrayDB) > 0) {
                $idsStr = implode(",", $idDelete);
                $u = new DB('shop_accomp', 'sa');
                $u->where("id_price IN (?)", $idsStr)->deleteList();

                $arrayDB = $this->codesInIdRelated($arrayDB);

                DB::insertList('shop_accomp', $arrayDB);
            }
        }
    } // запись сопутств. тов. в БД

    private static function codesInIdRelated($arrayDB)
    {
        /**
         * замена кодов сопустствующих товаров на ид
         * @param  array $arrayDB массив сопутствующих товаров [0][id_price,id_acc] (id_acc - коды)
         * @param  array $codes массив кодов сопутствующих товаров
         * @param  str $codesStr массив кодов сопутствующих товаров в Строку
         * @param  array $codeId связки код-ид по товарам
         * @return array $arrayDB  массив сопутствующих товаров [0][id_price,id_acc] (id_acc - ид)
         */

        $codes = array();
        foreach ($arrayDB as $k => $i)
            $codes[$i['id_acc']] = true;
        $codes = array_keys($codes);

        $codesStr = '';
        foreach ($codes as $k => $i) $codesStr .= "'$i',";
        $codesStr = substr($codesStr, 0, -1);

        if (!empty($codesStr)) {
            $u = new DB('shop_price', 'sp');
            $u->select('sp.id, sp.code');
            $u->where("sp.code IN ($codesStr)");
            $list = $u->getList();
            unset($u, $codes);
        } else $list = array();

        $codeId = array();
        foreach ($list as $k => $i)
            $codeId[$i['code']] = $i['id'];

        foreach ($arrayDB as $k => $i) {
            if ($codeId[$i['id_acc']])
                $arrayDB[$k]['id_acc'] = $codeId[$i['id_acc']];
            else
                unset($arrayDB[$k]);
        }
        $arrayDB = array_values($arrayDB);
        unset($codeId);

        return $arrayDB;
    } // замена кодов сопустствующих товаров на ид

    public function updateGroupTable()
    {
        /** Обновить Группы таблиц */

        $sql = "CREATE TABLE IF NOT EXISTS shop_group_tree (
            id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            id_parent int(10) UNSIGNED NOT NULL,
            id_child int(10) UNSIGNED NOT NULL,
            level tinyint(4) NOT NULL,
            updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
            created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE INDEX UK_shop_group_tree (id_parent, id_child),
            CONSTRAINT FK_shop_group_tree_shop_group_id FOREIGN KEY (id_child)
            REFERENCES shop_group (id) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT FK_shop_group_tree_shop_group_tree_id_parent FOREIGN KEY (id_parent)
            REFERENCES shop_group (id) ON DELETE CASCADE ON UPDATE RESTRICT
            )
            ENGINE = INNODB
            CHARACTER SET utf8
            COLLATE utf8_general_ci;";

        DB::query($sql);

        $tree = array();

        $tbl = new DB('shop_group', 'sg');
        $tbl->select('upid, id');
        $list = $tbl->getList();
        foreach ($list as $it) {
            $tree[intval($it['upid'])][] = $it['id'];
        }


        unset($list);
        $data = $this->addInTree($tree);
        DB::query("TRUNCATE TABLE `shop_group_tree`");
        DB::insertList('shop_group_tree', $data);

    } // Обновить Группы таблиц

    private function addInTree($tree, $parent = 0, $level = 0, &$treepath = array())
    {
        /** добавить в Дерево */

        if ($level == 0) {
            $treepath = array();
        } else
            $treepath[$level] = $parent;

        foreach ($tree[$parent] as $id) {
            $data[] = array('id_parent' => $id, 'id_child' => $id, 'level' => $level);
            if ($level > 0)
                for ($l = 1; $l <= $level; $l++) {
                    $data[] = array('id_parent' => $treepath[$l], 'id_child' => $id, 'level' => $level);
                }
            if (!empty($tree[$id])) {
                $data = array_merge($data, $this->addInTree($tree, $id, $level + 1, $treepath));
            }
        }
        return $data;
    } // добавить в Дерево

    public function clearDB()
    {
        /** Очистить таблицу товаров и все дочерние. Удалить все строки в таблицах БД... */

        try {
            /** 1 удалить все строки в таблицах БД... */
            DB::query("SET foreign_key_checks = 0");
            DB::query("TRUNCATE TABLE shop_group");
            DB::query("TRUNCATE TABLE shop_price");
            DB::query("TRUNCATE TABLE shop_price_group");
            DB::query("TRUNCATE TABLE shop_price_measure");
            DB::query("TRUNCATE TABLE shop_accomp");
            //DB::query("TRUNCATE TABLE shop_brand");
            DB::query("TRUNCATE TABLE shop_img");
            DB::query("TRUNCATE TABLE shop_group_price");
            DB::query("TRUNCATE TABLE shop_discounts");
            DB::query("TRUNCATE TABLE shop_discount_links");
            DB::query("TRUNCATE TABLE shop_modifications");
            DB::query("TRUNCATE TABLE shop_modifications_group");
            DB::query("TRUNCATE TABLE shop_feature_group");
            DB::query("TRUNCATE TABLE shop_feature");
            DB::query("TRUNCATE TABLE shop_group_feature");
            DB::query("TRUNCATE TABLE shop_modifications_feature");
            DB::query("TRUNCATE TABLE shop_feature_value_list");
            DB::query("TRUNCATE TABLE shop_modifications_img");
            DB::query("TRUNCATE TABLE shop_tovarorder");
            DB::query("TRUNCATE TABLE shop_order");
            DB::query("SET foreign_key_checks = 1");
            return true;
        } catch (Exception $e) {
            writeLog($e->getMessage());
            //DB::rollBack();
            return FALSE;
        }

    } // Очистить таблицу товаров и все дочерние


}
