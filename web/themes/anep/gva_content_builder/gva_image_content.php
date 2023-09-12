<?php 

if(!class_exists('element_gva_image_content')):
   class element_gva_image_content{
      public function render_form(){
         return array(
           'type'          => 'gsc_image_content',
            'title'        => t('Image content'),
            'fields' => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title'),
                  'admin'     => true
               ),
               array(
                  'id'        => 'max_width',
                  'type'      => 'text',
                  'title'     => t('Max Width Title'),
                  'desc'      => t('e.g: 200px')
               ),
               array(
                  'id'        => 'background',
                  'type'      => 'upload',
                  'title'     => t('Images')
               ),
               array(
                  'id'        => 'image_radius',
                  'type'      => 'select',
                  'title'     => t('Image Radius'),
                  'options'   => array(
                     ''           => t('--None--'), 
                     'radius-1x'  => t('Radius 1x'), 
                     'radius-2x'  => t('Radius 2x'),
                     'radius-5x'  => t('Radius 5x'),
                  ),
               ),
               array(
                  'id'        => 'content',
                  'type'      => 'textarea',
                  'title'     => t('Content'),
                  'desc'      => t('Some HTML tags allowed'),
               ),
               array(
                  'id'        => 'link',
                  'type'      => 'text',
                  'title'     => t('Link'),
               ),

               array(
                  'id'        => 'text_link',
                  'type'      => 'text',
                  'title'     => t('Text Link'),
               ),

               array(
                  'id'        => 'target',
                  'type'      => 'select',
                  'title'     => t('Open in new window'),
                  'desc'      => t('Adds a target="_blank" attribute to the link'),
                  'options'   => array( 'off' => 'No', 'on' => 'Yes' ),
                  'std'       => 'on'
               ),

               array(
                  'id'        => 'skin',
                  'type'      => 'select',
                  'title'     => t('Skin'),
                  'options'   => array( 
                     'skin-v1' => t('Skin 1'), 
                     'skin-v2' => t('Skin 2'), 
                     'skin-v3' => t('Skin 3'),
                     'skin-v4' => t('Skin 4') 
                  ),
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
                  'options'   => gavias_content_builder_delay_aos(),
                  'desc'      => '0 = default',
                  'class'     => 'width-1-2'
               ), 
         
            ),                                     
         );
      }

      public static function render_content( $attr = array(), $content = '' ){
         global $base_url;
         extract(gavias_merge_atts(array(
            'title'              => '',
            'max_width'          => '',
            'content'            => '',
            'background'         => '',
            'image_radius'       => 'radius-2x',
            'link'               => '',
            'text_link'          => 'Read more',
            'target'             => '',
            'skin'               => 'skin-v1',
            'el_class'           => '',
            'animate'            => '',
            'animate_delay'      => ''
         ), $attr));

         // target
         if( $target =='on' ){
            $target = 'target="_blank"';
         } else {
            $target = false;
         }
         
         if($background) $background = $base_url . $background; 

         if($skin) $el_class .= ' ' . $skin;
         if($animate) $el_class .= ' wow ' . $animate;
         $title_html = $link ? "<a {$target} href=\"{$link}\">{$title}</a>" : $title;
         ob_start();
         ?>
      
         <div class="gsc-image-content <?php print $el_class; ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <?php if($background){ ?>
            <div class="image">
               <?php if($link){ ?><a <?php print $target ?> href="<?php print $link ?>"><?php } ?>
                  <img class="<?php print $image_radius ?>" src="<?php print $background ?>" alt="<?php print strip_tags($title) ?>" />
               <?php if($link){ ?></a><?php } ?>
            </div>
            <?php } ?>
            <div class="box-content">     
               <?php if($title_html){ ?>
                  <h4 class="title" <?php if($max_width){ ?> style="max-width: <?php echo $max_width ?>" <?php } ?>><span><?php print $title_html ?></span></h4>
               <?php } ?>
               <?php if($content){ ?>
               <div class="desc"><?php print $content; ?></div>
               <?php } ?>
               <?php if($link){ ?>
                  <div class="read-more">
                     <a class="btn-black" <?php print $target ?> href="<?php print $link ?>">
                        <span><?php print $text_link ?></span>
                        <i class="fas fa-chevron-right"></i>                        
                     </a>
                  </div>
               <?php } ?>
            </div>  
         </div>
         

         <?php return ob_get_clean() ?>
        <?php            
      } 

   }
endif;   
