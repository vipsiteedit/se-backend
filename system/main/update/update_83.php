<?php
se_db_query("ALTER TABLE shop_modifications_feature
  DROP INDEX shop_price_feature_uk1, ADD UNIQUE INDEX shop_price_feature_uk1 (id_modification, id_price, id_feature, id_value);");
