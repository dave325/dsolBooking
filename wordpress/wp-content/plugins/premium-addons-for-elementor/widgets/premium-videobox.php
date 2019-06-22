<?php
namespace Elementor;

use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Videobox extends Widget_Base {
    
    public function get_name() {
        return 'premium-addon-video-box';
    }

    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Video Box';
	}

    public function get_icon() {
        return 'pa-video-box';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }
    
    public function get_script_depends() {
        return [
            'premium-addons-js'
        ];
    }

    // Adding the controls fields for Premium Video Box
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        $this->start_controls_section('premium_video_box_general_settings',
            [
                'label'         => esc_html__('Video Box', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_video_box_video_type',
            [
                'label'         => esc_html__('Video Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'youtube',
                'options'       => [
                    'youtube'       => esc_html__('Youtube', 'premium-addons-for-elementor'),
                    'vimeo'         => esc_html__('Vimeo', 'premium-addons-for-elementor'),
                    'self'          => esc_html__('Self Hosted', 'premium-addons-for-elementor'),
                ]
            ]
        );
        
        $this->add_control('premium_video_box_video_id_embed_selection',
            [
                'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HIDDEN,
                'default'       => 'id',
                'options'       => [
                    'id'    => esc_html__('ID', 'premium-addons-for-elementor'),
                    'embed' => esc_html__('Embed URL', 'premium-addons-for-elementor'),
                    ],
                'condition'     => [
                    'premium_video_box_video_type!' => 'self',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_video_id', 
            [
                'label'         => esc_html__('Video ID', 'premium-addons-for-elementor'),
                'description'   => esc_html__('Enter the numbers and letters after the equal sign which located in your YouTube video link or after the slash sign in your Vimeo video link. For example, z1hQgVpfTKU', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HIDDEN,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'premium_video_box_video_type!' => 'self',
                    'premium_video_box_video_id_embed_selection' => 'id',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_video_embed', 
            [
                'label'         => esc_html__('Embed URL', 'premium-addons-for-elementor'),
                'description'   => esc_html__('Enter your YouTube/Vimeo video link. For example, https://www.youtube.com/embed/z1hQgVpfTKU', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::HIDDEN,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'premium_video_box_video_type!' => 'self',
                    'premium_video_box_video_id_embed_selection' => 'embed',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_link', 
            [
                'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'condition'     => [
                    'premium_video_box_video_type!' => 'self',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_self_hosted',
            [
                'label'         => esc_html__('URL', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [
                    'active' => true,
                    'categories' => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ],
                ],
                'media_type' => 'video',
                'condition'     => [
                    'premium_video_box_video_type' => 'self',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_related_video',
            [
                'label'         => esc_html__('Show Related Videos', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => esc_html__('Enable/Disable related videos after the video'),
                'default'       => 'yes',
                'condition'     => [
                    'premium_video_box_video_type' => 'youtube',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_mute',
            [
                'label'         => esc_html__('Mute', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => esc_html__('This will play the video muted')
            ]
        );
        
        $this->add_control('premium_video_box_loop',
            [
                'label'         => esc_html__('Loop', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('premium_video_box_image_switcher',
            [
                'label'         => esc_html__('Overlay', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_video_box_image_settings', 
            [
                'label'         => esc_html__('Overlay', 'premium-addons-for-elementor'),
                'condition'     => [
                    'premium_video_box_image_switcher'  => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_video_box_image',
            [
                'label'         => esc_html__('Image', 'premium-addons-for-elementor'),
                'description'   => esc_html__('Choose an image for the video box', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'	=> Utils::get_placeholder_image_src()
                ],
                'label_block'   => true,
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_video_box_play_icon_settings', 
            [
                'label'         => esc_html__('Play Icon', 'premium-addons-for-elementor')
            ]
        );
        
        $this->add_control('premium_video_box_play_icon_switcher',
            [
                'label'         => esc_html__('Play Icon', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );
        
        $this->add_control('premium_video_box_icon_hor_position', 
            [
                'label'         => esc_html__('Horizontal Position (%)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'label_block'   => true,
                'default'       => [
                    'size' => 50,
                ],
                'condition'     => [
                    'premium_video_box_play_icon_switcher'  => 'yes',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-play-icon-container' => 'left: {{SIZE}}%;',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_icon_ver_position', 
            [
                'label'         => esc_html__('Vertical Position (%)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'label_block'   => true,
                'default'       => [
                    'size' => 50,
                ],
                'condition'     => [
                    'premium_video_box_play_icon_switcher'  => 'yes',
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-play-icon-container' => 'top: {{SIZE}}%;',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_video_box_description_text_section', 
            [
                'label'         => esc_html__('Description', 'premium-addons-for-elementor'),
            ]
        );
        
        $this->add_control('premium_video_box_video_text_switcher',
            [
                'label'         => esc_html__('Video Text', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('premium_video_box_description_text', 
            [
                'label'         => esc_html__('Text', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::TEXTAREA,
                'dynamic'       => [ 'active' => true ],
                'default'       => __('Play Video','premium-addons-for-elementor'),
                'condition'     => [
                    'premium_video_box_video_text_switcher' => 'yes'
                ],
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]
        );
        
        $this->add_control('premium_video_box_description_ver_position', 
            [
                'label'         => esc_html__('Vertical Position (%)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'label_block'   => true,
                'default'       => [
                    'size' => 60,
                ],
                'condition'     => [
                    'premium_video_box_video_text_switcher' => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-description-container' => 'top: {{SIZE}}%;',
                ]
            ]
        );
        
         $this->add_control('premium_video_box_description_hor_position', 
            [
                'label'         => esc_html__('Horizontal Position (%)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'label_block'   => true,
                'default'       => [
                    'size' => 50,
                    ],
                'condition'     => [
                    'premium_video_box_video_text_switcher' => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-description-container' => 'left: {{SIZE}}%;',
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_video_box_text_style_section', 
            [
                'label'         => esc_html__('Video Box','premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'image_border',        
                'selector'      => '{{WRAPPER}} .premium-video-box-image-container, {{WRAPPER}} .premium-video-box-video-container',
            ]
        );
        
        //Border Radius Properties sepearated for responsive issues
        $this->add_responsive_control('premium_video_box_image_border_radius', 
            [
                'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-image-container, {{WRAPPER}} .premium-video-box-video-container'  => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-top-right-radius: {{SIZE}}{{UNIT}}; border-bottom-left-radius: {{SIZE}}{{UNIT}}; border-bottom-right-radius: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'          => 'box_shadow',
                'selector'      => '{{WRAPPER}} .premium-video-box-image-container',
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_video_box_icon_style', 
            [
                'label'         => esc_html__('Play Icon','premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_video_box_play_icon_switcher'  => 'yes',
                ],
            ]
        );
        
        $this->add_control('premium_video_box_play_icon_color', 
            [
                'label'         => esc_html__('Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-play-icon'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_play_icon_color_hover', 
            [
                'label'         => esc_html__('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-play-icon-container:hover .premium-video-box-play-icon'  => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_play_icon_size',
            [
                'label'         => esc_html__('Size', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 30,
                ],
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-play-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'premium_video_box_play_icon_background_color',
                'types'         => ['classic', 'gradient'],
                'selector'      => '{{WRAPPER}} .premium-video-box-play-icon-container',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'icon_border',   
                'selector'      => '{{WRAPPER}} .premium-video-box-play-icon-container',
            ]
        );
    
        $this->add_control('premium_video_box_icon_border_radius', 
            [
                'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'default'       => [
                    'unit'  => 'px',
                    'size'  => 100,
                ],
                'size_units'    => ['px', '%', 'em'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-play-icon-container'  => 'border-radius: {{SIZE}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_video_box_icon_padding',
            [
                'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'default'       => [
                    'top'   => 40,
                    'right' => 40,
                    'bottom'=> 40,
                    'left'  => 40,
                    'unit'  => 'px'
                ],
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-play-icon ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_icon_hover_animation',
            [
                'label'         => esc_html__('Hover Animation', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => esc_html__('Hover animation works only when you set a background color or image for play icon', 'premium-addons-for-elementor'),
                'default'       => 'yes',
            ]
        );
        
        $this->add_responsive_control('premium_video_box_icon_padding_hover',
            [
                'label'         => esc_html__('Hover Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-play-icon:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition'     => [
                    'premium_video_box_icon_hover_animation'    => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
       
        $this->start_controls_section('premium_video_box_text_style', 
            [
                'label'         => esc_html__('Video Text', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_video_box_video_text_switcher' => 'yes'
                ]
            ]
        );
        
        $this->add_control('premium_video_box_text_color',
            [
                'label'         => esc_html__('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-text'   => 'color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_control('premium_video_box_text_color_hover',
            [
                'label'         => esc_html__('Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-description-container:hover .premium-video-box-text'   => 'color: {{VALUE}};',
                ]
            ]
        );
       
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'text_typography',
                'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                'selector'      => '{{WRAPPER}} .premium-video-box-text',
            ]
        );
        
        $this->add_control('premium_video_box_text_background_color',
            [
                'label'         => esc_html__('Background Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-description-container'   => 'background-color: {{VALUE}};',
                ]
            ]
        );
        
        $this->add_responsive_control('premium_video_box_text_padding',
            [
                'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-video-box-description-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    ],
                ]
            );
        
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'         => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'          => 'premium_text_shadow',
                'selector'      => '.premium-video-box-text'
            ]
        );
        
        $this->end_controls_section();
    }

    protected function render() {
        
        $settings = $this->get_settings_for_display();
        
        $id = $this->get_id();
        
        $params = $this->get_vidoe_params();
        
        $image = $this->get_video_thumbnail( $params['id'] );
        
        $link = $params['link'];
        
        $this->add_inline_editing_attributes('premium_video_box_description_text');
        
        $video_type = $settings['premium_video_box_video_type'];
        
        $hosted_url = !empty( $settings['premium_video_box_self_hosted']['url'] ) ? $settings['premium_video_box_self_hosted']['url'] : '' ;
        
        $related = $settings['premium_video_box_related_video'];
        
        $mute = $settings['premium_video_box_mute'];
        
        $loop = $settings['premium_video_box_loop'];
        
        $options = '?rel=';
        $options .= 'yes' == $related ? '1' : '0';
        $options .= 'youtube' == $video_type ? '&mute=' : '&muted=';
        $options .= 'yes' == $mute ? '1' : '0';
        $options .= '&loop=';
        $options .= 'yes' == $loop ? '1' : '0';
        
        if ( $loop ) {
            $options .= '&playlist=' . $params['id'];
        }
        
        $video_params = 'controls ';
        if( $mute ) {
            $video_params .= 'muted ';
        }
        if( $loop ) {
            $video_params .= 'loop ';
        }
        
        $this->add_render_attribute('container', [
                'id'    => 'premium-video-box-container-' . $id,
                'class' => 'premium-video-box-container',
                'data-overlay'  => 'yes' === $settings['premium_video_box_image_switcher'] ? 'true' : 'false',
                'data-type'     => $video_type
            ]
        );
        
        $this->add_render_attribute('video_container', [
                'class' => 'premium-video-box-video-container',
            ]
        );
        
        
        if ( 'self' !== $video_type ) {
            $this->add_render_attribute('video_container', [
                    'data-src'  => $link . $options
                ]
            );
        }
        
    ?>

    <div <?php echo $this->get_render_attribute_string('container'); ?>>
        <div <?php echo $this->get_render_attribute_string('video_container'); ?>>
            <?php if ( $video_type  === 'self') : ?>
                <video src="<?php echo esc_url( $hosted_url ); ?>" <?php echo $video_params; ?>></video>
            <?php endif; ?>
        </div>
            <div class="premium-video-box-image-container" style="background-image: url('<?php echo esc_url( $image ); ?>');">
        </div>
        <?php if( $settings['premium_video_box_play_icon_switcher'] == 'yes' ) : ?>
            <div class="premium-video-box-play-icon-container">
                <i class="premium-video-box-play-icon fa fa-play fa-lg"></i>
            </div>
        <?php endif; ?>
        <?php if( $settings['premium_video_box_video_text_switcher'] == 'yes' && !empty( $settings['premium_video_box_description_text'] ) ) : ?>
            <div class="premium-video-box-description-container">
                <p class="premium-video-box-text"><span <?php echo $this->get_render_attribute_string('premium_video_box_description_text'); ?>><?php echo $settings['premium_video_box_description_text']; ?></span></p>
            </div>
        <?php endif; ?>
    </div>

    <?php
    }
    
    private function get_video_thumbnail( $id = '' ) {
        
        $settings = $this->get_settings_for_display();
        
        $type = $settings['premium_video_box_video_type'];
        
        $thumbnail = $settings['premium_video_box_image_switcher'];
        
        $thumbnail_src = $settings['premium_video_box_image']['url'];
        
        if ( 'yes' !== $thumbnail ) {
            if ('youtube' === $type ) {
                $thumbnail_src = sprintf('https://i.ytimg.com/vi/%s/maxresdefault.jpg', $id );
            } elseif ('vimeo' === $type ) {
                $vimeo = unserialize( file_get_contents( "https://vimeo.com/api/v2/video/$id.php" ) );
				$thumbnail_src = str_replace( '_640', '_840', $vimeo[0]['thumbnail_large'] );
            } else {
                $thumbnail_src = 'transparent';
            }
        }
        
        return $thumbnail_src;
        
    }
    
    private function get_vidoe_params() {
        $settings = $this->get_settings_for_display();
        
        $type = $settings['premium_video_box_video_type'];
        
        $identifier = $settings['premium_video_box_video_id_embed_selection'];
        
        $id = $settings['premium_video_box_video_id'];
        
        $embed = $settings['premium_video_box_video_embed'];
        
        $link = $settings['premium_video_box_link'];
        
        if ( ! empty( $link ) ) {
            
            if ( 'youtube' === $type ) {
                $video_id = substr($link, strpos( $link, 'v=' ) + 2 );
                $link = sprintf('https://www.youtube.com/embed/%s', $video_id );
            } elseif ( 'vimeo' === $type ) {
                $video_id = substr($link, strpos( $link, '.com/' ) + 5 );
                $link = sprintf('https://player.vimeo.com/video/%s', $video_id );
            }
            $id = $video_id;
        } elseif ( ! empty( $id ) || ! empty ( $embed ) ) {
            
            if( 'id' === $identifier ) {
                $link = 'youtube' === $type ? sprintf('https://www.youtube.com/embed/%s', $id ) : sprintf('https://player.vimeo.com/video/%s', $id );
            } else {
                $link = $embed;
            }
            
        }
        
        return [ 
            'link' => $link,
            'id'    => $id 
        ];
        
    }
    
}