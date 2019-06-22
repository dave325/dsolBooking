<?php
    $settings = $this->get_settings();
?>


    <div class="tgx-hover-image">
        <figure class="<?php echo $settings['hover_image_hover_animation']; ?>">

            <?php if ($settings['hover_image']):?>
                
                <div class="hover-image">
                <?php if ( $settings['select_link_to'] == 'url' ):?>
                    <a target="<?php  echo $settings['hover_image_link']['is_external'] ? '_blank' : '_self'?>" href="<?php echo $settings['hover_image_link']['url'];?>">
                     <img src="<?php echo $settings['hover_image']['url']; ?>" alt="hover-image">
                    </a>
                <?php else: ?>
                    <a href="<?php echo $settings['hover_image']['url']; ?>" data-elementor-open-lightbox="<?php echo $settings['hover_image_lightbox'];?>">
                    <img src="<?php echo $settings['hover_image']['url']; ?>" alt="hover-image">
                    </a>
                <?php endif; ?>
                </div><!-- .hover-image -->
            <?php endif; ?>


        <?php if ($settings['hover_image_hover_animation'] == 'default-effect' || $settings['hover_image_caption_title'] || $settings['hover_image_caption_content']):?>
                <?php if ($settings['hover_image_caption_title']):?>

                <figcaption class="image-caption">
                    <h2 class="caption-title">
                        <?php echo $settings['hover_image_caption_title']; ?>
                    </h2>
                <?php if ($settings['hover_image_caption_content']):?>
                    <p class="caption-content">
                        <?php echo $settings['hover_image_caption_content']; ?> 
                    </p>
                <?php endif; ?>
            </figcaption><!-- image-caption -->

             <?php endif; ?>

        <?php else:?>

            <figcaption class="image-caption">
                <?php if ($settings['hover_image_caption_title']):?>
                    <h2 class="caption-title"><?php echo $settings['hover_image_caption_title']; ?></h2>
                <?php endif; ?>

                <?php if ($settings['hover_image_caption_content']):?>
                    <p class="caption-content"><?php echo $settings['hover_image_caption_content']; ?></p>
                <?php endif; ?>
            </figcaption> <!-- image-caption -->
        <?php endif;?>     

        </figure><!-- hover animation -->
    </div> <!-- tgx-hover-image -->

