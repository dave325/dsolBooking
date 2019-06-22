<?php
/*
 * Taxonomy checkbox list field
 */
function tb_taxonomy_settings_field($settings, $value) {
    $terms_fields = array();
    $value_arr = $value;

    if (!is_array($value_arr)) {
        $value_arr = array_map('trim', explode(',', $value_arr));
    }

    if (!empty($settings['taxonomy'])) {
        $terms = get_terms($settings['taxonomy'], 'orderby=count&hide_empty=0');

        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $terms_fields[] = sprintf(
                        '<label><input onclick="changeCategory(this);" id="%s" class="tb-check-taxonomy %s" type="checkbox" name="%s" value="%s" %s/>%s</label>', $settings['param_name'] . '-' . $term->slug, $settings['param_name'] . ' ' . $settings['type'], $settings['param_name'], $term->term_id, checked(in_array($term->term_id, $value_arr), true, false), $term->name
                );
            }
        }
    }

    return '<div class="tb-taxonomy-block">'
            . '<input type="hidden" name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-checkboxes ' . $settings['param_name'] . ' ' . $settings['type'] . '_field" value="' . $value . '" />'
            . '<div class="tb-taxonomy-terms">'
            . implode($terms_fields)
            . '</div>'
            . '</div>';
}
vc_add_extra_field('tb_taxonomy', 'tb_taxonomy_settings_field');

/*
 * Hidden field
 */

function tb_hidden_settings_field($settings, $value){
   return '<div class="tb_hidden_block">'
             .'<input name="'.$settings['param_name']
             .'" class="wpb_vc_param_value wpb-textinput '
             .$settings['param_name'].' '.$settings['type'].'_field" type="hidden" value="'
             .$value.'"/>'
         .'</div>';
}
vc_add_extra_field('tb_hidden', 'tb_hidden_settings_field');
