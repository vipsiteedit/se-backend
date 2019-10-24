<?php

class plugin_geo{

	public $regionlist = array();
	public $countrylist = array();
	public $citylist = array();
	public $id_region = 0;
	public $id_city = 0;
	public $code_country = 0;
	public $geo_data = array();
	private $db;

	public function __construct()
	{
		$this->db = mysql_connect('edgestile.ru','public','public');
		if (!$this->db) return;
		else{
			mysql_query("SET character_set_client='UTF8'", $this->db);
			mysql_query("SET character_set_results='UTF8'", $this->db);
			mysql_query("set collation_connection='utf8_general_ci''", $this->db);
			mysql_select_db('public', $this->db);
		}
	} 
		
	private function ip2int($ip)
	{
		$part = explode(".", $ip);
		$int = 0;
		if (count($part) == 4) {
			$int = $part[3] + 256 * ($part[2] + 256 * ($part[1] + 256 * $part[0]));
		}
    return $int;
	}
	
	public function getRegionList()
	{
		$result = mysql_query("SELECT id, region FROM geo_regions", $this->db);
		if (!$result) return;
		while($row = mysql_fetch_assoc($result)){
			$this->regionlist[] = array('id' => $row['id'], 'region' => $row['region']);
		}
	return ($this->regionlist);
	}
	
	public function getCountryList()
	{
		$result = mysql_query("SELECT code, country_ru FROM geo_countries", $this->db);
		if (!$result) return;
		while($row = mysql_fetch_assoc($result)){
			$this->countrylist[] = array('code' => $row['code'], 'contry' => $row['country_ru']);
		}
	return ($this->countrylist);
	}
	
	public function getCityList()
	{
		$result = mysql_query("SELECT id, city FROM geo_cities", $this->db);
		if (!$result) return;
		while($row = mysql_fetch_assoc($result)){
			$this->citylist[] = array('id' => $row['id'], 'city' => $row['city']);
		}
	return ($this->citylist);
	}
	
	public function getIdRegionFromIp($ip)
	{
		$ip = $this->ip2int($ip);
		$result = mysql_query("SELECT id_region FROM geo_cities WHERE id = (SELECT id_city FROM geo_ip WHERE ip_begin <= $ip AND ip_end >= $ip LIMIT 1)", $this->db);
		if (!$result) return;
		$row = mysql_fetch_assoc($result);
		$this->id_region = $row['id_region'];
	return ($this->id_region);
	}
	
	public function getCodeCountryFromIp($ip)
	{
		$ip = $this->ip2int($ip);
		$result = mysql_query("SELECT code_country FROM geo_ip WHERE ip_begin <= $ip AND ip_end >= $ip LIMIT 1", $this->db);
		if (!$result) return;
		$row = mysql_fetch_assoc($result);
		$this->code_country = $row['code_country'];
	return ($this->code_country);
	}
	
	public function getRealIp(){
	    $ip = $_SERVER['HTTP_X_REAL_IP'];
	    if (empty($ip)) {
		$ip = $_SERVER['REMOTE_ADDR'];
	    }
	return $ip;
	}
	
	public function getIdCityFromIp($ip)
	{
		$ip = $this->ip2int($ip);
		$result = mysql_query("SELECT id_city FROM geo_ip WHERE ip_begin <= $ip AND ip_end >= $ip LIMIT 1", $this->db);
		if (!$result) return;
		$row = mysql_fetch_assoc($result);
		$this->id_city = $row['id_city'];
	return ($this->id_city);
	}
	
	public function getGeoDataFromIp($ip)
	{
		$ip = $this->ip2int($ip);
		$result = mysql_query("SELECT ip.code_country, ip.id_city, ct.id_region,ip.range FROM geo_ip as ip INNER JOIN geo_cities as ct ON ip.id_city = ct.id WHERE ip_begin <= $ip AND ip_end >= $ip LIMIT 1", $this->db);
		if (!$result) return;
		list($code_country, $id_city, $id_region, $range) = mysql_fetch_row($result);
		if ($range){
		    $this->geo_data['range'] = $range;
		}
		if ($code_country){
		    $result = mysql_query("SELECT code, country_ru as name_ru, country_en as name_en FROM geo_countries WHERE code = '$code_country' LIMIT 1");
		    $this->geo_data['country'] = mysql_fetch_assoc($result);
		}
		if ($id_region){
			$result = mysql_query("SELECT id, region as name, UTC FROM geo_regions WHERE id = $id_region LIMIT 1");
			$this->geo_data['region'] = mysql_fetch_assoc($result);
		}
		if ($id_city){
			$result = mysql_query("SELECT id, city as name, district, latitude, longitude FROM geo_cities WHERE id = $id_city");
			$this->geo_data['city'] = mysql_fetch_assoc($result);
		}
	return ($this->geo_data);
	}

	public function getUtcRegion($id_region)
	{
		$result = mysql_query("SELECT UTC FROM geo_regions WHERE id = $id_region LIMIT 1", $this->db);
		if (!$result) return;
		$row = mysql_fetch_assoc($result);
		$utc = $row['UTC'];
		return (int)$utc;
	}
}

?>