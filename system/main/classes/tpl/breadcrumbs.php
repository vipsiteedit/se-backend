<ol class="breadcrumb">
 <?php foreach($this->breadcrumbs as $item): ?>
    <li <?php if (!empty($item['active'])): ?>class="active"<?php endif ?> itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
    <?php if (!empty($item['lnk'])): ?>
        <a href="<?php echo $item['lnk'] ?>" itemprop="url"><span itemprop="title"><?php echo $item['name'] ?></span></a>
    <?php elseif: ?>
        <span itemprop="title"><?php echo $item['name'] ?></span>
    <?php endif ?>
    </li>
 <?php endforeach ?>
</ol>