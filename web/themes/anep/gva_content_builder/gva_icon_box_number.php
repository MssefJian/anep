<?php 

if(!class_exists('element_gva_icon_box_number')):
   class element_gva_icon_box_number{
      public function render_form(){
         $fields = array(
            'type'            => 'gva_icon_number',
            'title'           => t('Icon Box Workflow'),
            'fields' => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => 'Title',
                  'admin'     => true
               ),
               array(
                  'id'        => 'number',
                  'type'      => 'text',
                  'title'     => t('Box Number'),
                  'default'   => ''
               ),
               array(
                  'id'        => 'icon',
                  'type'      => 'text',
                  'title'     => 'Icon',
               ),
               array(
                  'id'        => 'image',
                  'type'      => 'upload',
                  'title'     => t('Image Icon'),
                  'desc'      => t('Use image icon instead of icon class'),
               ),
               array(
                  'id'        => 'content',
                  'type'      => 'textarea',
                  'title'     => t('Content'),
               ),
               array(
                  'id'        => 'link',
                  'type'      => 'text',
                  'title'     => t('Link'),
               ),               
               
               array(
                  'id'        => 'style',
                  'type'      => 'select',
                  'title'     => t('Style'),
                  'options'   => array( 
                     'style-1' => t('Style I'),
                     'style-2' => t('Style II'), 
                     'style-3' => t('Style III') 
                  )
               ),
               array(
                  'id'        => 'background_box',
                  'type'      => 'select',
                  'title'     => t('Background Box'),
                  'options'   => array(
                     ''           => '-- None --',
                     'bg-black'   => 'Background Black',
                     'bg-white'   => 'Background White',
                     'bg-theme'   => 'Background Theme'
                  ),
                  'default'   => 'text-black'
               ),
               array(
                  'id'        => 'text_style',
                  'type'      => 'select',
                  'title'     => t('Text Style'),
                  'options'   => array( 
                     'text-dark' => t('Dark'), 
                     'text-light' => t('White')
                  )
               ),
               array(
                  'id'        => 'target',
                  'type'      => 'select',
                  'title'     => t('Open in new window'),
                  'desc'      => t('Adds a target="_blank" attribute to the link'),
                  'options'   => array( 0 => 'No', 1 => 'Yes' ),
               ),
               array(
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
                  'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
               ),
               array(
                  'id'        => 'animate',
                  'type'      => 'select',
                  'title'     => t('Animation'),
                  'desc'      => t('Entrance animation for element'),
                  'options'   => gavias_content_builder_animate(),
                  'class'     => 'width-1-2'
               ), 
               array(
                  'id'        => 'animate_delay',
                  'type'      => 'select',
                  'title'     => t('Animation Delay'),
                  'options'   => gavias_content_builder_delay_wow(),
                  'desc'      => '0 = default',
                  'class'     => 'width-1-2'
               ), 
            ),                                     
         );
         return $fields;
      }

      public static function render_content( $attr = array(), $content = '' ){
         global $base_url;
         extract(gavias_merge_atts(array(
            'title'                 => '',
            'number'                => '',
            'icon'                  => '',
            'image'                 => '',
            'content'               => '',
            'link'                  => '',            
            'style'                 => 'style-1',
            'background_box'        => '',
            'text_style'            => 'dark',            
            'target'                => '',
            'el_class'              => '',
            'animate'               => '',
            'animate_delay'         => ''
         ), $attr));

         if($image) $image = $base_url . $image; 

         if( $target ){
            $target = 'target="_blank"';
         } else {
            $target = false; 
         }
         if($image) $el_class .= ' icon-image';
         $el_class .= ' ' . $text_style;
         $el_class .= ' ' . $style;

         if($background_box) $el_class .= ' ' . $background_box; 
         if($animate) $el_class .= ' wow ' . $animate; 
         ob_start();
         ?>
       
 
         <?php if($style =='style-1'){ ?>
         <div class="widget gsc-icon-box-number clearfix <?php print $el_class; ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="box-content">
               <?php if($number || $icon || $image){ ?>
                  <div class="box-inner">
                     <?php if($number){ ?>
                     <div class="box-number">
                        <span><?php print $number ?></span>             
                     </div> 
                     <?php } ?> 
                     <div class="content-inner">
                        <?php if($icon){ ?>
                        <div class="box-icon">
                           <span class="icon <?php print $icon ?>"></span>             
                        </div> 
                        <?php } ?>  
                        <?php if($image){ ?>
                        <div class="box-image">
                           <span class="icon-image"><img src="<?php print $image ?>" alt="<?php print strip_tags($title) ?>"/> </span>
                        </div> 
                        <?php } ?> 
                     </div>
                  </div>
               <?php } ?>                
               <?php if($title){ ?>
                  <h4 class="box-title">
                     <a class="link" <?php if($link) print 'href="'. $link .'"' ?> <?php print $target ?>><?php print $title ?></a>   
                  </h4>
               <?php } ?>
               <?php if($content){ ?>
               <div class="box-desc"><?php print $content ?></div>
               <?php } ?>
            </div>             
         </div>
         <?php } ?>


         <?php if($style =='style-2' || $style =='style-3'){ ?>
         <div class="widget gsc-icon-box-number clearfix <?php print $el_class; ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="box-content">
               <?php if($number || $icon || $image){ ?>
                  <div class="box-inner">
                     <?php if($number){ ?>
                     <div class="box-number">
                        <span><?php print $number ?></span>             
                     </div> 
                     <?php } ?> 
                     <?php if($icon){ ?>
                     <div class="box-icon">
                        <span class="icon <?php print $icon ?>"></span>             
                     </div> 
                     <?php } ?>  
                     <?php if($image){ ?>
                     <div class="box-image">
                        <span class="icon-image"><img src="<?php print $image ?>" alt="<?php print strip_tags($title) ?>"/> </span>
                     </div> 
                     <?php } ?>                     
                  </div>
               <?php } ?>
               <?php if($title || $content){ ?>
                  <div class="content-inner"> 
                     <?php if($title){ ?>
                        <h4 class="box-title">
                           <a class="link" <?php if($link) print 'href="'. $link .'"' ?> <?php print $target ?>><?php print $title ?></a>   
                        </h4>
                     <?php } ?>
                     <?php if($content){ ?>
                     <div class="box-desc"><?php print $content ?></div>
                     <?php } ?>                  
                  </div>
               <?php } ?>
            </div>             
         </div>
         <?php } ?>


         <?php return ob_get_clean() ?>
         <?php
      }

   }
endif;   




