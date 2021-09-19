<header:css>
    [include_css]
    <?php if(strval($section->parametrs->param4)=='s'): ?>
        <link href="[module_url]jcarousel.responsive.css" rel="stylesheet" type="text/css">
        
        <style>
               .part<?php echo $section->id ?> .jcarousel {
                   font-size: 14px;
                   margin: 0;
               }               
        </style>
        
    <?php endif; ?>
</header:css>

<footer:js>
    <?php if(strval($section->parametrs->param4)=='s'): ?>
        <script type="text/javascript" src="[module_url]jquery.jcarousel.min.js"></script>
        <script type="text/javascript" src="[module_url]jcarousel.responsive.js"></script>
    <?php endif; ?> 
</footer:js>
<div class="<?php if(strval($section->parametrs->param1)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>">
<section class="content b_brendline part<?php echo $section->id ?>" data-seimglist="<?php echo $section->id ?>" >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="b_brendline_brend_group <?php if(strval($section->parametrs->param4)=='s'): ?>slider<?php endif; ?>">
    <?php if(strval($section->parametrs->param4)=='b'): ?>
      <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

        <div class="b_brendline-brend_item" <?php echo $__data->editItemRecord($section->id, $record->id) ?>>
          <?php if(!empty($record->image)): ?>
            <a class="objectImage" href="<?php echo $record->url ?>">
                <img class="b_brendline-brend_img" border="0" src="<?php echo $record->image ?>" border="0" alt="<?php echo $record->image_alt ?>" style="width: 100%">
            </a>
          <?php endif; ?>
        </div>
      
<?php endforeach; ?>
    <?php endif; ?>
    
    <?php if(strval($section->parametrs->param4)=='s'): ?> 
       <div class="jcarousel-wrapper">
   <div class="jcarousel">
    <ul>
                    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

                        <?php if(!empty($record->image)): ?>
         <li>
          <div class="jcarousel-inner">
           <a  href="<?php echo $record->url ?>" class="jcarousel-image-container">
            <img src="<?php echo $record->image ?>" alt="<?php echo $record->image_alt ?>">
           </a>                           
          </div>
         </li>
                        <?php endif; ?>
                    
<?php endforeach; ?>
    </ul>
   </div>
   <a href="#" class="jcarousel-control-prev">&lsaquo;</a>
   <a href="#" class="jcarousel-control-next">&rsaquo;</a>
   <p class="jcarousel-pagination"></p>
  </div> 
    <?php endif; ?>
    
    
    </div>
</section></div>
