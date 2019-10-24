<?php

class plugin_shopfilter {
    
    private $group;
    private $selected_filter = array();
    
    public function __construct($basegroup = '', $id_group = 0) {
		if (empty($id_group)) {
			$group = plugin_shopgroups::getInstance();
			$id_group = $group->getGroupId((string)$basegroup);
		}	
		$this->group = $id_group;
        if (isRequest('f')) {
            $get = $_GET['f'];
			if (is_array($get))
				$this->selected_filter = $_GET['f'];   
		}
    }
    
    public function existFilters() {
        $filters = array();
		if (!empty($this->group)) {
			$sgf = new seTable('shop_group_filter', 'sgf');
			$sgf->select('sgf.id_feature, sgf.default_filter, sgf.expanded, sf.type, sf.name, sf.measure');
            $sgf->leftJoin('shop_feature sf', 'sf.id=sgf.id_feature');
			$sgf->where('sgf.id_group=?', $this->group);
			$sgf->andwhere('(sgf.id_feature IS NOT NULL OR sgf.default_filter IS NOT NULL)');
			$sgf->orderBy('sgf.sort', 0);
			$list = $sgf->getList();
            
			if (!empty($list)) {
				foreach($list as $val) {
					$key = (!empty($val['id_feature'])) ? $val['id_feature'] : $val['default_filter'];
                    if (!empty($val['id_feature'])) {
                        $key = (int)$val['id_feature'];
                        if (!in_array($val['type'], array('list', 'colorlist', 'number', 'bool'))) {
                            continue;
                        }
                    }
                    else {
                        $key = $val['default_filter'];
                        if ($key == 'flag_hit')
                            $key = 'hit';
                        elseif ($key == 'flag_new')
                            $key = 'new';
                    }
                    
					$filters[$key] = $val;
				}
			}
        }
		return $filters; 
    }

    public function getCountFiltered() {
		$goods = new plugin_shopgoods();
        return $goods->getGoodsCount();
    }

    public function getSQLFiltered() {
        $join = $where = $select = array();   
		if (!empty($this->selected_filter)) {
            $default_filter = array('price', 'brand', 'hit', 'new', 'discount', 'special');
            $base_curr = se_baseCurrency();
            $current_curr = se_getMoney(); 
            $i=0;

            //$join[] = array('type'=>'left', 'table'=>'shop_modifications sm', 'on'=>'sp.id = sm.id_price');
            foreach($this->selected_filter as $key => $val) {
                $value = $val;
                if (in_array($key, $default_filter)) {
                    switch ($key) {
                        case 'price': 
                            $pspc = plugin_shop_price_cache::getInstance();
                            $price_from = se_MoneyConvert((float)$value['from'], $current_curr, $base_curr);
                            $price_to = se_MoneyConvert((float)$value['to'], $current_curr, $base_curr);
							$join[] = array('type'=>'inner', 'table'=>'shop_price_cache spc', 'on'=>'sp.id = spc.id_price');
							$where[] ='(spc.price BETWEEN "' . $price_from . '" AND "' . $price_to . '")';
							$select[] = 'spc.modifications AS mod_cache';
                            break;
                        case 'brand':
                            $join[] = array('type'=>'inner', 'table'=>'shop_brand sb', 'on'=>'sp.id_brand=sb.id');
                            $value = join(',', array_map('intval', $value));
                            $where[] = "(sb.id IN ($value))"; 
                            break;
                        case 'hit':
                            if ($value === '1') {
                                $where[]  = "sp.flag_hit = 'Y'";    
                            }
                            elseif ($value === '0') {
                                $where[] = "sp.flag_hit = 'N'"; 
                            }
                            break;
                        case 'new':
                            if ($value === '1') {
                                $where[] = "sp.flag_new = 'Y'";    
                            }
                            elseif ($value === '0') {
                                $where[] = "sp.flag_new = 'N'"; 
                            } 
                            break;
                        case 'discount': 
                            if ($value === '1') {
                                //$where[] = '(SELECT DISTINCT 1 FROM shop_discount_links sdl WHERE sdl.id_price = sp.id OR sdl.id_group = sp.id_group LIMIT 1) IS NOT NULL';
								$join[] = array('type'=>'inner', 'table'=>'shop_discount_links sdl', 'on'=>'sp.id = sdl.id_price');
                            }
                            elseif ($value === '0') {
                                $where[] = '(SELECT DISTINCT 1 FROM shop_discount_links sdl WHERE sdl.id_price = sp.id OR sdl.id_group = sp.id_group LIMIT 1) IS NULL';
                            } 
							break;
						case 'special': 
							if ($value === '1') {
								$join[] = array('type'=>'inner', 'table'=>'shop_leader sl', 'on'=>'sp.id = sl.id_price');
                            }
                            elseif ($value === '0') {
                                $join[] = array('type'=>'left', 'table'=>'shop_leader sl', 'on'=>'sp.id = sl.id_price');
								$where[] = 'sl.id IS NULL'; 
                            } 
							break;  
                    }
                }
                else {
                    $spf = 'spf' . $i++;
                    (int)$key;
                    if (is_array($value)) {
                        if (isset($value['from']) && isset($value['to'])) {
                            (float)$value['from'];
                            (float)$value['to'];
                            if (isset($value['from']) && isset($value['to'])) {
                                $join[] = array('type'=>'inner', 'table'=>'shop_modifications_feature ' . $spf, 'on'=>$spf .'.id_price=sp.id');
                                $where[] = '('.$spf.'.id_feature='.$key.' AND '.$spf.'.value_number BETWEEN "' . (float)$value['from']. '" AND "' . (float)$value['to'] . '")';
                            }
                        } 
                        else {
                            $join[] = array('type'=>'inner', 'table'=>'shop_modifications_feature ' . $spf, 'on'=>"$spf.id_price=sp.id");
                            //$join[] = array('type'=>'inner', 'table'=>'shop_modifications_feature ' . $spf, 'on'=>"$spf.id_price=sp.id OR sm.id=$spf.id_modification");
                            $value = join(',', array_map('intval', $value));
                            $where[] = "({$spf}.id_feature={$key} AND {$spf}.id_value IN ({$value}))";
                        }   
                    }  
                    elseif ($val === '0' || $val === '1') {
                        $join[] = array('type'=>'inner', 'table'=>'shop_modifications_feature ' . $spf, 'on'=>$spf .'.id_price=sp.id');
                        $where[] = '('.$spf.'.id_feature='.$key.' AND '.$spf.'.value_bool = '. (int)$value . ')';
                    } 
                }      
            } 
        }
        return array($join, $where, $select);
    }
    
    public function getFilterValues($tree_group = null, $flLive = false)
    {
        $filter_list = $this->existFilters();
        if (empty($filter_list)) return;
        
        $filters = array();
        
        list($join, $where) = $this->getSQLFiltered();
		
        foreach ($filter_list as $key => $feature) {
            if (is_numeric($key)) {
                
                if ($feature['type'] == 'list' || $feature['type'] == 'colorlist') {
                    $sf = new seTable('shop_modifications_feature', 'smf');
                 
                    $sf->select('sfvl.value, sfvl.id, sfvl.color, sfvl.image');
                    $sf->innerJoin('shop_feature_value_list sfvl', 'sfvl.id = smf.id_value');
                    $sf->innerJoin('shop_price sp', 'smf.id_price = sp.id');
                    $sf->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                    $sf->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');
                    
                    $sf->where('sp.enabled="Y"');
                    $sf->andWhere('sgt.id_parent=?', $this->group);
                    $sf->andWhere('smf.id_feature=?', $key);
					if (!empty($join)) {
						foreach($join as $it) {
							if ($it['type'] == 'inner')
								$sf->innerJoin($it['table'], $it['on']);
							else
								$sf->leftJoin($it['table'], $it['on']);
						}
					}
					if (!empty($where)) {
						foreach($where as $wh) {
							$sf->andWhere($wh);
						}
					}
										
                    $sf->groupBy('sfvl.id');
                    $sf->orderBy('sfvl.sort');
                    
                    $list = $sf->getList();
                    
                    if ($list) {
                        $feature_image_dir = '/images/' . se_getLang() . '/shopfeature/';
                        $feature['values'] = array();
                        foreach ($list as $val) {
                            $check = (bool)isset($this->selected_filter[$key]) && is_array($this->selected_filter[$key]) && in_array($val['id'], $this->selected_filter[$key]);
							$feature['values'][$val['id']] = array('value' => $val['value'], 'check' => $check); 

							if ($feature['type'] == 'colorlist') {
								if (!empty($val['image']) && file_exists(SE_ROOT . $feature_image_dir . $val['image']))
									$feature['values'][$val['id']]['image'] = $feature_image_dir . $val['image'];
								else
									$feature['values'][$val['id']]['color'] = $val['color'];
							}
                        }
                        $filters[$key] = $feature;
                    }
                }
                elseif ($feature['type'] == 'number') {
                    $sf = new seTable('shop_modifications_feature', 'smf');
                    
                    $sf->select('MIN(smf.value_number) AS min_value, MAX(smf.value_number) AS max_value');
                    $sf->innerJoin('shop_price sp', 'smf.id_price = sp.id');
                    $sf->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                    $sf->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');
                    
                    $sf->where('sp.enabled="Y"');
                    $sf->andWhere('sgt.id_parent=?', $this->group);
                    $sf->andWhere('smf.id_feature=?', $key);
					if (!empty($join)) {
						foreach($join as $it) {
							if ($it['type'] == 'inner')
								$sf->innerJoin($it['table'], $it['on']);
							else
								$sf->leftJoin($it['table'], $it['on']);
						}
					}
					if (!empty($where)) {
						foreach($where as $wh) {
							$sf->andWhere($wh);
						}
					}
                    
                    if ($sf->fetchOne()) {
                        $feature['type'] = 'range';
                        $feature['min'] = $sf->min_value;
                        $feature['max'] = $sf->max_value; 
                        if (isset($this->selected_filter[$key])) {
                            if (isset($this->selected_filter[$key]['from']))
                                $feature['from'] = (float)$this->selected_filter[$key]['from'];
                            if (isset($this->selected_filter[$key]['to']))
                                $feature['to'] = (float)$this->selected_filter[$key]['to']; 
                        }
                        if ($feature['min'] != $feature['max']) {
                            $filters[$key] = $feature;
                        }
                    }
                }
                elseif ($feature['type'] == 'bool') {
                    $sf = new seTable('shop_modifications_feature', 'smf');
                    
                    $sf->select('DISTINCT smf.value_bool');
                    $sf->innerJoin('shop_price sp', 'smf.id_price = sp.id');
                    $sf->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                    $sf->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');
                    
                    $sf->where('sp.enabled="Y"');
                    $sf->andWhere('sgt.id_parent=?', $this->group);
                    $sf->andWhere('smf.id_feature=?', $key);
					if (!empty($join)) {
						foreach($join as $it) {
							if ($it['type'] == 'inner')
								$sf->innerJoin($it['table'], $it['on']);
							else
								$sf->leftJoin($it['table'], $it['on']);
						}
					}
					if (!empty($where)) {
						foreach($where as $wh) {
							$sf->andWhere($wh);
						}
					}
                    
                    if ($sf->fetchOne()) {
                        $feature['check'] = false;  
                        if (isset($this->selected_filter[$key])) {
                            if ($this->selected_filter[$key] === '1' || $this->selected_filter[$key] === '0') {
                                $feature['check'] = $this->selected_filter[$key];
                            }
                        }
                        $filters[$key] = $feature;
                    }
                }
                
            }
            else {
                if ($key == 'brand') {
                    $sp = new seTable('shop_price', 'sp');
                    $sp->select('sb.id, sb.name');
                    $sp->innerJoin('shop_brand sb', 'sb.id=sp.id_brand');
                    $sp->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                    $sp->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');
                    $sp->where('sp.enabled="Y"');
                    $sp->andWhere('sgt.id_parent=?', $this->group);
					if (!empty($join)) {
						foreach($join as $it) {
							if ($it['type'] == 'inner')
								$sp->innerJoin($it['table'], $it['on']);
							else
								$sp->leftJoin($it['table'], $it['on']);
						}
					}
					if (!empty($where)) {
						foreach($where as $wh) {
							$sp->andWhere($wh);
						}
					}
                    
                    
                    $sp->groupBy('sb.id');
                    $sp->orderBy('sb.name', 0);
                    $list = $sp->getList();
                    
                    if ($list) {
                        if (isRequest('brand')) {
                            $brand = getRequest('brand');
                            $sb = new seTable('shop_brand');
                            $sb->select('id');
                            $sb->where("code='?'", urldecode($brand));
                            $sb->fetchOne();
                            if ($sb->isFind())
                                $this->selected_filter[$key][] = $sb->id;
                        }
                        $feature['values'] = array();
                        $feature['type'] = 'list';
                        foreach ($list as $val) {
                            $check = (bool)isset($this->selected_filter[$key]) && is_array($this->selected_filter[$key]) && in_array($val['id'], $this->selected_filter[$key]);
                            
                            
                            $feature['values'][$val['id']] = array('value' => $val['name'], 'check' => $check); 
                        }
                        $filters[$key] = $feature;
                    }
                    
                }
                elseif ($key == 'price') {
                    $pspc = plugin_shop_price_cache::getInstance();
                    $shop_price = new seTable('shop_price', 'sp');
                    $shop_price->select('
                        MIN(spc.price) AS minprice,
                        MAX(spc.price) AS maxprice 
                    ');
                    $shop_price->innerJoin('shop_price_cache spc', 'sp.id=spc.id_price');
                    $shop_price->innerJoin('shop_price_group spg', 'sp.id=spg.id_price');
                    $shop_price->innerJoin('shop_group_tree sgt', 'spg.id_group=sgt.id_child');
                    $shop_price->where('sp.enabled = "Y"');
                    $shop_price->andWhere('sgt.id_parent=?', $this->group);

					/*if (!empty($join)) {
						foreach($join as $it) {
							if ($it['table'] == 'shop_price_cache spc') continue;
							if ($it['type'] == 'inner')
								$shop_price->innerJoin($it['table'], $it['on']);
							else
								$shop_price->leftJoin($it['table'], $it['on']);
						}
					}
					if (!empty($where)) {
						foreach($where as $wh) {
							$shop_price->andWhere($wh);
						}
					}
					*/
                    //echo $shop_price->getSql();
                    if ($l = $shop_price->fetchOne()) {
                        $base_curr = se_baseCurrency();
                        $current_curr = se_getMoney(); 
                        
                        $feature['type'] = 'range';
                        $feature['min'] = floor(se_MoneyConvert($shop_price->minprice, $base_curr, $current_curr));
                        $feature['max'] = ceil(se_MoneyConvert($shop_price->maxprice, $base_curr, $current_curr));
                        
                        if (isset($this->selected_filter[$key])) {
                            if (isset($this->selected_filter[$key]['from']))
                                $feature['from'] = (float)$this->selected_filter[$key]['from'];
                            if (isset($this->selected_filter[$key]['to']))
                                $feature['to'] = (float)$this->selected_filter[$key]['to']; 
                        }
                        if ($feature['min'] != $feature['max']) {
                            $filters[$key] = $feature;
                        }
                    }
                }
                elseif ($key == 'hit' || $key == 'new') {
                    
                }
                
            }
            
            if (!$feature['expanded'] && isset($this->selected_filter[$key]) && isset($filters[$key])) {
                if ($feature['type'] == 'bool') {
                    if (($this->selected_filter[$key] === '1' || $this->selected_filter[$key] === '0'))
                        $filters[$key]['expanded'] = 1;
                }
                elseif ($feature['type'] == 'number' || $feature['type'] == 'range') {
                    if ($filters[$key]['from'] > $filters[$key]['min'] || $filters[$key]['to'] < $filters[$key]['max'])
                        $filters[$key]['expanded'] = 1;
                }
                else
                    $filters[$key]['expanded'] = 1;
            }
        }
        
        
        
        return $filters;
    }
}
