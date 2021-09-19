<?php

class Export
{


    public function __construct($settings = null, $options = array())
    {

    }

    public function exportOffers($numpage = 0, $limit = 1000, $params = array())
    {
        $offset = $numpage * $limit;
//        $offers = [[
//            'groups' => [],
//            'article' => string,
//            'name' => string,
//            'images' => [],
//            'note' => text,
//            'text' => text,
//            'features' => [],
//            'price' => float,
//            'price_opt' => float,
//            'price_opt_corp' => float,
//            'price_purchase' => float,
//            'title' => string,
//            'description' => text,
//            'keywords' => string,
//            'page_title' => string
//        ]];

        list($mainRequest, $count) = $this->shopPrice($params);
        $goodsL = $mainRequest->getList($limit, $offset);
        $countpage = floor($count / $limit);

        $idsProducts = array();
        foreach ($goodsL as &$l) {
            $l['groups'] = (!empty($l['groups'])) ? explode(',', $l['groups']) : array();
            $l['images'] = (!empty($l['images'])) ? explode(',', $l['images']) : array();
            $idsProducts[] = $l['id'];
        }
        //$featCols = $this->featuresCols(array(intval($input['idGroup'])));      // особенности
        //$modsCols = $this->modsCols(array(intval($input['idGroup'])));

        $features = $this->features($idsProducts); // характеристики товаров
        //$modifications = $this->modifications($idsProducts); // модификации товаров


        $this->mergerWithFeatures($goodsL, $features); // сливаем списки товаров и их характеристик
        //$goodsL = $this->mergerWithMoffification($goodsL, $modifications, $modsCols);
        return $goodsL;
    }

    private function shopPrice($params = array())
    {
        /**
         *  @@@@@@@@ @@@@@@ @@@@@     @@    @@@@@@ @@     @ | ПОЛУЧЕНИЕ
         *     @@    @@  @@ @@  @@   @@@@   @@  @@ @@     @ | листа товаров
         *     @@    @@  @@ @@@@@   @@  @@  @@@@@@ @@@@@@ @ |
         *     @@    @@  @@ @@  @@ @@@@@@@@ @@     @@  @@ @ |
         *     @@    @@@@@@ @@@@@  @@    @@ @@     @@@@@@ @ |
         */


        // получаем данные из БД
        $u = new DB('shop_price', 'sp');
        $u->reConnection();  // перезагрузка запроса
        $u->select('COUNT(*) `count`');
        $u->leftJoin('shop_group sg', 'sg.id = sp.id_group');
        $u->leftJoin('shop_brand sb', 'sb.id = sp.id_brand');
        if (!empty($params) && !empty($params['groups']))
            $u->where("sg.code_gr IN ('?')", join("','", $params['groups']));
        elseif (!empty($params) && !empty($params['idGroups']))
            $u->where('sp.id_group IN (?)', join(',', $params['idGroups']));
        elseif (!empty($params)) {
            if (!empty($params['ids'])) {
                $u->where('sp.id IN (?)', join(',', $params['ids']));
            } else {
                $u->where('true');
                foreach ($params['allModeLastParams']['filters'] as $filter) {
                    if ($filter['field'] == 'idGroup') {
                        $u->andWhere('sp.id_group IN (?)', $filter['value']);
                    }
                    if ($filter['field'] == 'idBrand') {
                        $u->andWhere('sp.id_brand IN (?)', $filter['value']);
                    }
                }
                if (trim($params['allModeLastParams']['searchText'])) {
                    $u->andWhere($this->getSearch(trim($params['allModeLastParams']['searchText'])));
                }
            }
            //"params":{"allModeLastParams":{"offset":0,"limit":15,"searchText":"","filters":[{"field":"idGroup","sign":"IN","value":"21,278,79,284,80,307,132,131,130,128,129,216,265,266,267,268,269"}]},"allModeParams":{},"allMode":true}}:
        }

        $result = $u->fetchOne();
        $count = $result["count"];

        // подключение к shop_price
        $u = new DB('shop_price', 'sp');

        // НАЧАЛО ЗАПРОСА
        $select = '
                sp.id id,
                    GROUP_CONCAT(DISTINCT
                        sg.code_gr
                        ORDER BY spg.is_main DESC
                        SEPARATOR ","
                    ) AS groups,
                sp.code code, sp.article article,
                sp.name name, 
				sp.price_purchase price_purchase, sp.price_opt price_opt, sp.price_opt_corp price_opt_corp, sp.price price, sp.bonus bonus,
				sb.name brand,
				(SELECT GROUP_CONCAT(DISTINCT
                    si.picture
                    SEPARATOR \',\'
                ) FROM shop_img `si` WHERE si.id_price=sp.id ORDER BY si.default DESC) AS images,
                sp.curr codeCurrency, sp.measure measurement,
                sp.presence_count count, sp.step_count step_count,
                sp.presence presence, sp.flag_new, sp.flag_hit, sp.enabled, sp.is_market,
                sp.weight weight, sp.volume volume, sp.page_title,
                CONCAT(
                    IFNULL(smw1.name, \'\'),\',\',
                    IFNULL(smw2.name, \'\')
                ) measuresWeight,
                CONCAT(
                    IFNULL(smv1.name, \'\'),\',\',
                    IFNULL(smv2.name, \'\')
                ) measuresVolume,

                sp.min_count,
				CONCAT_WS(":",sp.delivery_time,sp.signal_dt) delivTime,

                GROUP_CONCAT(DISTINCT
                    sa.id_acc
                    SEPARATOR \',\'
                ) AS idAcc,

                sp.title metaHeader, sp.keywords metaKeywords, sp.description metaDescription,
                sp.note, sp.text, sm.id idModification
            ';

        /** все запршиваемые поля должны использоваться в импорте или удалятся при обработке,
         *  иначе идет сдвиг столбцов и модификации не отображаются
         *  features должен возвращаться! тк если нет в товарах характеристик - столбец пропадает
         */

        $u->select($select);
        $u->leftJoin("shop_price_group spg", "spg.id_price = sp.id");
        $u->leftJoin('shop_group sg', 'sg.id = spg.id_group');


        $u->leftJoin('shop_modifications sm', 'sm.id_price = sp.id');
        //$u->leftJoin('shop_img si', 'si.id_price = sp.id');
        $u->leftJoin('shop_brand sb', 'sb.id = sp.id_brand');

        $u->leftJoin('shop_price_measure spm', 'spm.id_price = sp.id');
        $u->leftJoin('shop_measure_weight smw1', 'smw1.id = spm.id_weight_view');
        $u->leftJoin('shop_measure_weight smw2', 'smw2.id = spm.id_weight_edit');
        $u->leftJoin('shop_measure_volume smv1', 'smv1.id = spm.id_volume_view');
        $u->leftJoin('shop_measure_volume smv2', 'smv2.id = spm.id_volume_edit');
        $u->leftJoin('shop_accomp sa', 'sa.id_price = sp.id');

        $u->orderBy('sp.id');
        $u->groupBy('sp.id');
        if (!empty($params) && !empty($params['groups']))
            $u->where("sg.code_gr IN ('?')", join("','", $params['groups']));
        elseif (!empty($params) && !empty($params['idGroups']))
            $u->where('sp.id_group IN (?)', join(',', $params['idGroups']));
        elseif (!empty($params)) {
            if (!empty($params['ids'])) {
                $u->where('sp.id IN (?)', join(',', $params['ids']));
            } else {
                $u->where('true');
                foreach ($params['allModeLastParams']['filters'] as $filter) {
                    writeLog($filter);
                    if ($filter['field'] == 'idGroup') {
                        $u->andWhere('sp.id_group IN (?)', $filter['value']);
                    }
                    if ($filter['field'] == 'idBrand') {
                        $u->andWhere('sp.id_brand IN (?)', $filter['value']);
                    }
                }
                if (trim($params['allModeLastParams']['searchText'])) {
                    $u->andWhere($this->getSearch(trim($params['allModeLastParams']['searchText'])));
                }
            }
            //"params":{"allModeLastParams":{"offset":0,"limit":15,"searchText":"","filters":[{"field":"idGroup","sign":"IN","value":"21,278,79,284,80,307,132,131,130,128,129,216,265,266,267,268,269"}]},"allModeParams":{},"allMode":true}}:
        }
        return array($u, $count);
    } // получение листа товаров

    private function getSearch($search)
    {
        $searchFields = [
            ["title" => "Код", "field" => "sp.code"],
            ["title" => "Наименование", "field" => "sp.name", "active" => true],
            ["title" => "Артикул", "field" => "sp.article", "active" => true],
            ["title" => "Группа", "field" => "sg.name"],
            ["title" => "Бренд", "field" => "sb.name"]
        ];

        $searchItem = trim($search);
        if (empty($searchItem))
            return array();
        $where = array();
        $searchWords = explode(' ', $searchItem);
        foreach ($searchWords as $searchItem) {
            $result = array();
            if (!trim($searchItem)) continue;
            if (is_string($searchItem))
                $searchItem = trim(DB::quote($searchItem), "'");

            $time = 0;
            if (strpos($searchItem, "-") !== false) {
                $time = strtotime($searchItem);
            }

            foreach ($searchFields as $field) {
                $result[] = $field["field"] . " LIKE '%{$searchItem}%'";
            }
            if (!empty($result))
                $where[] = '(' . implode(" OR ", $result) . ')';
        }
        return implode(" AND ", $where);
    }

    private function mergerWithFeatures(&$goodsL, $features)
    {
        /** Добавляем Характеристики в массив (оптимизированный)
         *
         * 1 получаем именной массив id характеристик
         * 2 добавляем характеристики в массив товаров
         *
         * @param array $goodsL массив товаров (до добавления характеристик)
         * @param array $features массив данных по характеристикам (для подстановки)
         * @return array $tempGoodsL массив товаров и характеристик
         */

        if (!empty($features)) {
            foreach ($goodsL as &$v) {
                if (!empty($features[$v['id']])) {
                    $v['features'] = $features[$v['id']];
                }
            }
        }
    } // добавляем характеристики в массив


    private function features($idsProducts)
    {
        /** ХАРАКТЕРИСТИКИ ТОВАРА (оптимизированный) */

        $u = new DB('shop_modifications_feature', 'smf');
        $u->reConnection();  // перезагрузка запроса
        DB::query('SET group_concat_max_len = 8096;'); // увеличить количество символов в характеристиках до N
        $u->select("smf.id_price id,
                sf.name, sf.measure, sfg.name AS `group`,
                IF(
                    smf.id_value IS NOT NULL, sfvl.value, 
                    CONCAT(
                        IFNULL(smf.value_number, ''),
                        IFNULL(smf.value_bool, ''),
                        IFNULL(smf.value_string, '')
                    )
                ) value,
                sf.type");
        $u->where('smf.id_price IN (?)', implode(",", $idsProducts));
        $u->innerJoin('shop_feature sf', 'smf.id_feature = sf.id AND smf.id_modification IS NULL');
        $u->leftJoin('shop_feature_value_list sfvl', 'smf.id_value = sfvl.id');
        $u->leftJoin('shop_feature_group sfg', 'sfg.id = sf.id_feature_group');
        $u->groupBy('smf.id');
        $featureL = $u->getList();
        unset($u); // удаление переменной
        $features = array();
        foreach ($featureL as $f) {
            $features[$f['id']][] = array(
                'name' => trim($f['name']),
                'measure' => $f['measure'],
                'value' => $f['value'],
                'type' => $f['type'],
                'group' => $f['group']
            );
        }

        return $features;


    } // характеристики товара

    private function modifications($idsProducts)
    {
        /**
         * МОДИФИКАЦИИ ТОВАРА
         *
         * расположение : каждая модификация должна записываться отдельной строкой
         * главная стр  : при наличии модификаций - данные с главной не должны записываться
         * ассоциации   : при импорте модификаций, соотноситься должны по ключевому полю с DB (берем за константу "URL товара")
         *
         * shop_modification_group shop_price  shop_modifications  shop_feature  shop_feature_value_list  shop_modifications_feature  shop_modifications_img
         *
         *
         *                                                             shop_price
         *                                                          <id_price - id>
         * shop_feature (в столбцы) <id_feature - id>          shop_modifications_feature  <id - id_modification>   shop_modifications_img
         *                                                       <id_modification - id>
         * shop_feature_value_list (значения) <id_value - id>      shop_modifications
         */

        $u = new DB('shop_modifications', 'sm');
        $u->reConnection();  // перезагрузка запроса
        // GROUP_CONCAT(CONCAT_WS('--', CONCAT_WS('#', smg.name, sf.name), sfvl.value) SEPARATOR '\n') `values`,
        $u->select('sm.id id, sm.id_mod_group idGroup, sm.id_price idProduct, sm.code article,
                sm.value price, sm.value_opt priceOpt, sm.value_opt_corp priceOptCorp,
                sm.count, smg.name nameGroup, smg.vtype typeGroup, sm.description metaDescription,
				GROUP_CONCAT(DISTINCT sf.name, "--", sfvl.value SEPARATOR "##") AS `values`,
				GROUP_CONCAT(DISTINCT si.Picture SEPARATOR ",") AS images');
        $u->where('sm.id_price IN (?)', implode(",", $idsProducts));
        $u->innerJoin('shop_modifications_group smg', 'smg.id = sm.id_mod_group');
        $u->innerJoin('shop_modifications_feature smf', 'sm.id = smf.id_modification');
        $u->innerJoin('shop_feature sf', 'sf.id = smf.id_feature');
        $u->innerJoin('shop_feature_value_list sfvl', 'smf.id_value = sfvl.id');
        $u->leftJoin('shop_modifications_img smi', 'smi.id_modification = sm.id');
        $u->leftJoin('shop_img si', 'si.id = smi.id_img');
        $u->orderBy('sm.id_price');
        $u->groupBy('sm.id');
        $modifications = $u->getList();
        unset($u); // удаление переменной
        return $modifications;
    } // модификации товара

    /* Каталог */
    public function exportGroups($groups = array())
    {
        return $this->getGroupFromCode($groups);
    }


    private function getParentItem($item, $items)
    {
        foreach ($items as $it)
            if ($it["id"] == $item["upid"])
                return $it;
    }

    // получить имя пути
    private function getPathName($item, $items)
    {
        $name = str_replace('/','&#8260;', $item["name"]);
        if (!$item["upid"])
            return $name;

        $parent = $this->getParentItem($item, $items);
        if (!$parent)
            return $name;
        return $this->getPathName($parent, $items) . "/" . $name;
    }

    private function getGroupFromCode($groups = array(), $is_short = false)
    {
        $u = new DB('shop_group','sg');
        $select = '';
        if (!$is_short) {
            $select.=',sg.picture,sg.active, sg.commentary note, count(sp.id) sp_count';
        }
        $u->select('sg.id, sg.upid, sg.code_gr `code`, sg.name'.$select);
        $u->leftJoin('shop_price sp', 'sp.id_group=sg.id');
        if (!empty($groups)) {
            $u->where("sg.code_gr IN ('?')", join("','", $groups));
        }
        $u->groupBy('sg.id');
        $items = $u->getList();
        $result = array();
        foreach($items as &$item) {
            $item['path'] = $this->getPathName($item, $items);
        }
        foreach($items as &$item) {
            unset($item['id'], $item['upid']);
            if ($is_short) unset($item['name']);
        }
        return $items;
    }
}