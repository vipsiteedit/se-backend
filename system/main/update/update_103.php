<?php
se_db_query("ALTER TABLE shop_deliverytype
  ADD COLUMN id_parent INT(10) UNSIGNED DEFAULT NULL AFTER id");
se_db_query("ALTER TABLE shop_deliverytype
    ADD CONSTRAINT FK_shop_deliverytype_shop_deliverytype_id FOREIGN KEY (id_parent)
        REFERENCES shop_deliverytype(id) ON DELETE CASCADE ON UPDATE RESTRICT");