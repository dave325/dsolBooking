<?php
        $settings = $this->get_settings();
        $symbol = '';

        if ( ! empty( $settings['currency_symbol'] ) ) {
            if ( 'custom' !== $settings['currency_symbol'] ) {
                $symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
            } else {
                $symbol = $settings['currency_symbol_custom'];
            }
        }

        $price = explode( '.', $settings['price'] );
        $intpart = $price[0];
        $fraction = '';
        if ( 2 === sizeof( $price ) ) {
            $fraction = $price[1];
        }



        $this->add_render_attribute( 'button', 'class', [
                'tgx-price-table__button',
                'tgx-button',
            ]
        );

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'button', 'href', $settings['link']['url'] );

            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'button', 'target', '_blank' );
            }
        }

        if ( ! empty( $settings['button_hover_animation'] ) ) {
            $this->add_render_attribute( 'button', 'class', 'tgx-animation-' . $settings['button_hover_animation'] );
        }
        ?>
        <div class="tgx-price-table text-<?php echo $settings['layout_position'];?>">
            <?php if ( $settings['title_position'] == 'top' ) : ?>
                <?php if ( $settings['heading'] ) : ?>
                    <div class="tgx-price-table__header">
                        <?php if ( ! empty( $settings['heading'] ) ) : ?>
                            <h4 class="tgx-price-table__heading"><?php echo esc_attr($settings['heading']); ?></h4>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="tgx-price-table__price">

                <?php if ( ! empty( $symbol ) ) : ?>
                    <span class="tgx-price-table__currency"><strong><?php echo esc_attr($symbol); ?></strong></span>
                <?php endif; ?>

                <?php if ( ! empty( $settings['price'] ) ) : ?>
                    <span class="tgx-price-table__integer-part"><strong><?php echo esc_attr($settings['price']); ?></strong></span>
                <?php endif; ?>
                <?php if ( ! empty( $settings['period'] ) ) : ?>
                    <span class="tgx-price-table__period"><strong><?php echo esc_attr($settings['period']); ?></strong></span>
                <?php endif; ?>
             
            </div>

            <?php if ( $settings['title_position'] == 'bottom' ) : ?>
                <?php if ( $settings['heading'] ) : ?>
                    <div class="tgx-price-table__header">
                        <?php if ( ! empty( $settings['heading'] ) ) : ?>
                            <h4 class="tgx-price-table__heading"><?php echo esc_attr($settings['heading']); ?></h4>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>


            <?php if ( ! empty( $settings['features_list'] ) ) : ?>
                <ul class="tgx-price-table__features-list">
                    <?php foreach ( $settings['features_list'] as $item ) : ?>
                        <li class="tgx-repeater-item-<?php echo esc_attr($item['_id']); ?>">
                            <div class="tgx-price-table__feature-inner">
                                <?php if ( ! empty( $item['item_icon'] ) ) : ?>
                                    <i class="<?php echo esc_attr($item['item_icon']); ?>"></i>
                                <?php endif; ?>
                                <?php if ( ! empty( $item['item_text'] ) ) :
                                    echo $item['item_text'];
                                else :
                                    echo '&nbsp;';
                                endif;
                                ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ( ! empty( $settings['button_text'] ) || ! empty( $settings['footer_additional_info'] ) ) : ?>
                <div class="tgx-price-table__footer">
                    <?php if ( ! empty( $settings['button_text'] ) ) : ?>
                        <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                            <?php esc_html_e($settings['button_text'], 'widgetkit-for-elementor'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>