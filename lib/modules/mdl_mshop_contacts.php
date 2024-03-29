<?php
function module_mshop_contacts($razdel, $section = null)
{
 $__module_subpage = array();
 $__data = seData::getInstance();
 $thisreq = $__data->req;
 $_page = $thisreq->page;
 $_razdel = $thisreq->razdel;
 $_sub = $thisreq->sub;
 if (strpos(dirname(__FILE__),'/lib/modules'))
   $__MDL_URL = 'lib/modules/mshop_contacts';
 else $__MDL_URL = 'modules/mshop_contacts';
 $__MDL_ROOT = dirname(__FILE__).'/mshop_contacts';
 $this_url_module = $__MDL_ROOT;
 $url_module = $__MDL_URL;
 if (file_exists($__MDL_ROOT.'/php/lib.php')){
	require_once $__MDL_ROOT.'/php/lib.php';
 }
 if (count($section->objects))
	foreach($section->objects as $record){ $__record_first = $record->id; break; }
 if (file_exists($__MDL_ROOT.'/i18n/'.se_getlang().'.xml')){
	$__langlist = simplexml_load_file($__MDL_ROOT.'/i18n/'.se_getlang().'.xml');
	append_simplexml($section->language, $__langlist);
	foreach($section->language as $__langitem){
	  foreach($__langitem as $__name=>$__value){
	   $__name = strval($__name);
	   $__value = strval($section->traslates->$__name);
	   if (!empty($__value))
	     $section->language->$__name = $__value;
	  }
	}
 }
 if (file_exists($__MDL_ROOT.'/php/parametrs.php')){
   include $__MDL_ROOT.'/php/parametrs.php';
 }
 // START PHP
 $plugin_shopgeo = new plugin_shopgeo();                     
 
 if (isRequest('ajax'.$razdel)) {
     $response = array();
     /*
     if (isRequest('confirm')) {
         $plugin_shopgeo->confirmCity();
         $response['confirm'] = true;
     }
     
     if (isRequest('c')) {
         $response['search'] = $search = trim(urldecode(getRequest('c', 3)));
         if (!empty($search) && mb_strlen($search) >= 2) {
             ob_start();
             include $__MDL_ROOT . '/php/subpage_list.php';
             include $__data->include_tpl($section, 'subpage_list');
             $response['html'] = ob_get_contents();
             ob_end_clean();         
         }         
     }
     if (isRequest('set_region')) {
         $id_city = getRequest('set_region', 1);
         
         $city = $plugin_shopgeo->getCity($id_city, true);
         if (!empty($city)) {
             $plugin_shopgeo->confirmCity();
             $response['set'] = $id_city;
         }
     
     }
     */
     echo json_encode($response);
     exit();
 }

 // include content.tpl
 if((empty($__data->req->sub) || $__data->req->razdel!=$razdel) && file_exists($__MDL_ROOT . "/tpl/content.tpl")){
	if (file_exists($__MDL_ROOT . "/php/content.php"))
		include $__MDL_ROOT . "/php/content.php";
	ob_start();
	include $__data->include_tpl($section, "content");
	$__module_content['form'] =  ob_get_contents();
	ob_end_clean();
 } else $__module_content['form'] = "";
 //BeginSubPageymap
 $__module_subpage['ymap']['admin'] = "";
 $__module_subpage['ymap']['group'] = 0;
 $__module_subpage['ymap']['form'] =  '';
 if($razdel == $__data->req->razdel && !empty($__data->req->sub)
 && $__data->req->sub=='ymap' && file_exists($__MDL_ROOT . "/tpl/subpage_ymap.tpl")){
	include $__MDL_ROOT . "/php/subpage_ymap.php";
	ob_start();
	include $__data->include_tpl($section, "subpage_ymap");
	$__module_subpage['ymap']['form'] =  ob_get_contents();
	ob_end_clean();
 } //EndSubPageymap
 return  array('content'=>$__module_content,
              'subpage'=>$__module_subpage);
}