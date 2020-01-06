<?php
   se_db_query("ALTER TABLE shop_img
     ADD UNIQUE INDEX UK_shop_img (id_price, picture);");
   se_db_query("INSERT LOW_PRIORITY IGNORE
       INTO `shop_img` (`id_price`,`picture`,`picture_alt`, `sort`, `default`)
       SELECT `sp`.`id`, `sp`.`img`, `sp`.`img_alt`, 0, 1 FROM `shop_price` AS `sp` WHERE `sp`.`img` IS NOT NULL AND TRIM(`sp`.`img`) <> ''
       ON DUPLICATE KEY UPDATE `default` = 1;");
