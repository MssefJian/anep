<?php 
if(!class_exists('element_gva_video_box')):
   class element_gva_video_box{
      public function render_form(){
         $fields = array(
            'type' => 'gsc_video_box',
            'title' => ('Video Box'), 
            'size' => 3,
            'fields' => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title'),
                  'admin'     => true
               ),
               array(
                  'id'        => 'content',
                  'type'      => 'text',
                  'title'     => t('Data Url'),
                  'desc'      => t('example: https://www.youtube.com/watch?v=4g7zRxRN1Xk'),
               ),
               array(
                  'id'        => 'image',
                  'type'      => 'upload',
                  'title'     => t('Image Preview'),
               ),
               array(
                  'id'        => 'button_link',
                  'type'      => 'text',
                  'title'     => t('Button Link')
               ),
               array(
                  'id'        => 'button_text',
                  'type'      => 'text',
                  'title'     => t('Button Text'),
                  'std'       => 'Read More'
               ),
               array(
                  'id'        => 'button_style',
                  'type'      => 'select',
                  'title'     => t('Button Style'),
                  'options'   => array(
                     'btn-theme'    => 'Button Theme',
                     'btn-white'    => 'Button White',
                     'btn-black'    => 'Button Black',
                     'btn-inline'   => 'Button Inline'
                  ),
                  'default'   => 'text-gray'
               ),
               array(
                  'id'        => 'height',
                  'type'      => 'text',
                  'title'     => t('Min Height'),
                  'default'   => '200px'
               ),
               array(
                  'id'        => 'style',
                  'type'      => 'select',
                  'options'   => array(
                     'style-1'         => 'Style 1',
                     'style-2'         => 'Style 2'
                  )
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
               array(
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
                  'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
               ),
            ),                                       
         );
         return $fields;
      }

      public static function render_content( $attr, $content = null ){
         global $base_url;
         extract(gavias_merge_atts(array(
            'title'           => '',
            'content'         => '',
            'image'           => '',
            'button_link'     => '',
            'button_text'     => 'Read More',
            'button_style'    => 'btn-theme',
            'style'           => 'style-1',
            'height'          => '200px',
            'animate'         => '',
            'animate_delay'   => '',
            'el_class'        => '',
         ), $attr));

         $_id = gavias_content_builder_makeid();
         if($image){
            $image = $base_url .$image; 
         }
         if($animate) $el_class .= ' wow ' . $animate; 
         ob_start();
      ?>
  
      <?php if($style == 'style-1'){ ?>
         <div class="widget gsc-video-box <?php print $el_class;?> clearfix <?php print $style ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="video-content">
               <?php if($image){?>
               <div class="image">
                  <img src="<?php print $image ?>" alt="<?php print $title ?>"/>                  
               </div>
               <div class="gsc-video-link">
                  <a class="popup-video" href="<?php print $content ?>"><span class="icon"><i class="fa fa-play"></i></span></a>
               </div>
               <?php } ?>
            </div>   
         </div>  
      <?php } ?>  

      <?php if($style == 'style-2'){ ?>
         <div class="widget gsc-video-box <?php print $el_class;?> clearfix <?php print $style ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <?php if($image){?>
               <div class="image-layer" style="background-image:url('<?php echo $image ?>'); min-height:<?php echo $height ?>"></div>
            <?php } ?> 
            <?php if($title || $button_link){ ?>
               <div class="video-content">
                  <div class="video-inner">
                     <div class="video-text">
                        <div class="gsc-video-link">
                           <a class="popup-video" href="<?php print $content ?>"><span class="icon"><i class="fa fa-play"></i></span></a>
                        </div>
                        <?php if($title){ ?>
                           <h3 class="video-title"><?php print $title ?></h3> 
                        <?php } ?> 
                        <?php if($button_link){ ?>
                        <div class="button-action">
                           <a href="<?php echo $button_link ?>" class="<?php print $button_style; ?>"><i class="btn-curve"></i><span><?php echo $button_text ?></span></a>
                        </div>
                        <?php } ?>
                     </div>
                  </div> 
               </div>  
            <?php } ?>   
         </div>  
      <?php } ?> 
      
      <?php return ob_get_clean() ?>
       <?php
      }
      
   }
endif;   




