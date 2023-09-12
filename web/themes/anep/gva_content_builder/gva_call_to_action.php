<?php 

if(!class_exists('element_gva_call_to_action')):
   class element_gva_call_to_action{
      public function render_form(){
         $fields = array(
            'type' => 'gsc_call_to_action',
            'title' => t('Call to Action'),
            'fields' => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title'),
                  'admin'     => true
               ),
               array(
                  'id'        => 'title_font_size',
                  'type'      => 'text',
                  'title'     => t('Title Font Size')
               ),
               array(
                  'id'        => 'sub_title',
                  'type'      => 'text',
                  'title'     => t('Sub Title'),
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
                  'desc'      => t('HTML tags allowed.'),
               ),
               array(
                  'id'           => 'style',
                  'type'         => 'select',
                  'title'        => 'Style Layout',
                  'options'      => array(
                     'style-v1'    => t('Style V1'),
                     'style-v2'    => t('Style V2'),
                     'style-v3'    => t('Style V3')
                  )
               ),               
               array(
                  'id'        => 'box_background',
                  'type'      => 'text',
                  'title'     => t('Box Background'),
                  'desc'      => t('Box Background, e.g: #f5f5f5')
               ),
               array(
                  'id'        => 'width',
                  'type'      => 'text',
                  'title'     => t('Max width for content'),
                  'desc'      => 'e.g 660px'
               ),
               array(
                  'id'        => 'style_text',
                  'type'      => 'select',
                  'title'     => 'Skin Text for box',
                  'options'   => array(
                        'text-light'  => 'Text light',
                        'text-dark'   => 'Text dark',
                  ),
                  'std'       => 'text-dark'
               ),
               array(
                 'id'        => 'info',
                 'type'      => 'info',
                 'title'      => 'Settings Button'
               ),
               array(
                  'id'        => 'link',
                  'type'      => 'text',
                  'title'     => t('Link'),
               ),
               array(
                  'id'        => 'button_title',
                  'type'      => 'text',
                  'title'     => t('Button Title'),
                  'desc'      => t('Leave this field blank if you want Call to Action with Big Icon'),
               ),
               array(
                  'id'        => 'style_button',
                  'type'      => 'select',
                  'title'     => 'Style button #1',
                  'options'   => array(
                        'btn-theme'           => 'Button Theme',
                        'btn-black'           => 'Button Black',
                        'btn-white'           => 'Button white'
                  ),
                  'default'    => 'btn-theme'
               ),
                              
               array(
                  'id'        => 'target',
                  'type'      => 'select',
                  'title'     => t('Open in new window'),
                  'desc'      => t('Adds a target="_blank" attribute to the link'),
                  'options'   => array( 'off' => 'Off', 'on' => 'On' ),
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

      function render_content( $attr = array(), $content = ''  ){
         global $base_url;
         extract(gavias_merge_atts(array(
            'title'           => '',
            'title_font_size' => '',
            'sub_title'       => '',
            'image'           => '',
            'content'         => '',
            'style'           => '',
            'width'           => '',
            'link'            => '',
            'button_title'    => '',
            'style_button'    => 'btn-theme',                     
            'target'          => '',
            'style_text'      => 'text-dark',
            'box_background'  => '',
            'el_class'        => '',
            'animate'         => '',
            'animate_delay'   => '',
            
         ), $attr));
         if($image) $image = $base_url . $image; 
         // target
         if( $target =='on' ){
            $target = 'target="_blank"';
         } else {
            $target = false;
         }
         
         $class = array();
         $class[] = $el_class;
         $class[] = $style_text;
         $class[] = $style;
         $css_background = '';
         if($box_background){
            $class[] = 'has-background';
            $css_background = 'style="background:' . $box_background . '"';
         }
         if($animate) $class[] = 'wow ' . $animate; 
         ob_start();
         ?>

        
         <div class="widget gsc-call-to-action <?php print implode(' ', $class) ?>" <?php print $css_background ?> <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="content-inner clearfix" >
               <div class="content" <?php if($width){ ?> style="max-width: <?php echo $width ?>" <?php } ?> >
                  <?php if($image){ ?>
                     <div class="image-icon">
                        <img src="<?php print $image ?>" alt="<?php print strip_tags($title) ?>"/> 
                     </div> 
                  <?php } ?> 
                  <?php if($sub_title){ ?>
                     <div class="sub-title"><span><?php print $sub_title; ?></span></div>
                  <?php } ?> 
                  <?php if($title){ ?>
                     <h2 class="title" <?php if($title_font_size){ ?> style="font-size: <?php echo $title_font_size ?>" <?php } ?>>
                        <span><?php print $title; ?></span>
                     </h2>
                  <?php } ?>                  
                  <div class="desc"><?php print $content; ?></div>
               </div>
               <?php if($link){?>
                  <div class="button-action">
                     <a href="<?php print $link ?>" class="<?php print $style_button ?>" <?php print $target ?>>
                        <i class="btn-curve"></i><span><?php print $button_title ?></span></a>            
                  </div>
               <?php } ?>  
            </div>
         </div>
         <?php return ob_get_clean() ?>
      <?php
      }

   }
endif;   
