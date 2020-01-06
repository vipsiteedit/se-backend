<?php
// Add field sort and default
se_db_query("DROP TABLE IF EXISTS `shop_group_feature`;");
se_db_query("CREATE TABLE IF NOT EXISTS `shop_group_feature` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`id_group` int(10) unsigned NOT NULL,
`id_feature` int(10) unsigned NOT NULL,
`sort` int(10) NOT NULL DEFAULT '0',
`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 PRIMARY KEY (`id`),
KEY `shop_group_filter_uk2` (`id_group`),
KEY `FK_shop_group_feature_shop_feature_id` (`id_feature`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");

se_db_query("ALTER TABLE `shop_group_feature`
ADD CONSTRAINT `FK_shop_group_feature_shop_feature_id` FOREIGN KEY (`id_feature`) REFERENCES `shop_feature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `FK_shop_group_feature_shop_group_id` FOREIGN KEY (`id_group`) REFERENCES `shop_modifications_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
");

$req = se_db_query("SELECT  DISTINCT id_mod_group, CONCAT_WS(  '', GROUP_CONCAT(  `id_feature` ) ) AS features
FROM  `shop_modifications_feature` `smf` 
INNER JOIN `shop_modifications` `sm` 
ON (sm.id=smf.id_modification)
GROUP BY  `id_mod_group`, `id_modification`");
while ($res = se_db_fetch_assoc($req)){
//print_r($res);
  $id_group = $res['id_mod_group'];
  $features = explode(',', $res['features']);
  for ($i = count($features)-1; $i>=0; $i--){
     $it = $features[$i];
     se_db_query("INSERT INTO `shop_group_feature` (`id_group`,`id_feature`) VALUES ('{$id_group}','{$it}')");
  }
}
//echo se_db_error();