<?php

namespace SE\Shop;

use SE\DB as DB;
use SE\Exception;

class OptionItems extends Base
{
    protected $tableName = "shop_option_value";
    protected $sortBy = "sort";
    protected $sortOrder = "asc";

    protected function getSettingsFetch()
    {
        $result["select"] = "sov.*, so.name AS nameOption";
        $joins[] = array(
            "type" => "left",
            "table" => 'shop_option so',
            "condition" => 'sov.id_option = so.id'
        );
        $convertingValues[] = array(
            "price"
        );
        $result["joins"] = $joins;
        $result["convertingValues"] = $convertingValues;
        return $result;
    }

    public function fetch()
    {
        parent::fetch();

        foreach ($this->result['items'] as &$item) {
            if ($item['image']) {
                if (strpos($item['image'], "://") === false) {
                    $item['imageUrl'] = 'http://' . $this->hostname . "/images/rus/options/" . $item['image'];
                    $item['imageUrlPreview'] = "http://{$this->hostname}/lib/image.php?size=64&img=images/rus/options/" . $item['image'];
                } else {
                    $item['imageUrl'] = $item['image'];
                    $item['imageUrlPreview'] = $item['image'];
                }
            }
        }
        return $this->result["items"];
    }
}
