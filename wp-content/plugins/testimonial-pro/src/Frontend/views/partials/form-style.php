<?php
/**
 * Testimonial form styles.
 *
 * @package    Testimonial_Pro
 * @subpackage Testimonial_Pro/Frontend
 */

$label_position                 = isset( $form_data['label_position'] ) ? $form_data['label_position'] : '';
$testimonial_form_width         = isset( $form_data['testimonial_form_width']['top'] ) ? $form_data['testimonial_form_width']['top'] : '680';
$label_color                    = isset( $form_data['label_color'] ) ? $form_data['label_color'] : '';
$record_button_color            = isset( $form_data['record_button_color'] ) ? $form_data['record_button_color'] : array(
	'color'            => '#ffffff',
	'hover-color'      => '#ffffff',
	'background'       => '#005bdf',
	'hover-background' => '#005bdf',
);
$testimonial_form_rating_styles = isset( $form_data['testimonial_form_rating_styles']['all'] ) ? $form_data['testimonial_form_rating_styles'] : array(
	'all'              => '16',
	'color'            => '#d4d4d4',
	'background_color' => '#f3bb00',
	'hover-color'      => '#de7202',
);

$form_input_field_styles   = isset( $form_data['form_input_field_styles'] ) ? $form_data['form_input_field_styles'] : array(
	'all'              => '1',
	'style'            => 'solid',
	'color'            => '#e3e3e3',
	'background_color' => '#FFFFFF',
	'radius'           => '0',
	'unit'             => '%',
);
$submit_button_color       = isset( $form_data['submit_button_color']['color'] ) ? $form_data['submit_button_color'] : array(
	'color'            => '#ffffff',
	'hover-color'      => '#ffffff',
	'background'       => '#005BDF',
	'hover-background' => '#005BDF',
);
$record_button_color       = isset( $form_data['record_button_color'] ) ? $form_data['record_button_color'] : array(
	'color'            => '#ffffff',
	'hover-color'      => '#ffffff',
	'background'       => '#005bdf',
	'hover-background' => '#005bdf',
);
$form_background_color     = isset( $form_data['form_background_color'] ) ? $form_data['form_background_color'] : '#FFFFFF';
$testimonial_form_border   = isset( $form_data['testimonial_form_border']['style'] ) ? $form_data['testimonial_form_border'] : array(
	'all'    => '0',
	'style'  => 'solid',
	'color'  => '#444444',
	'radius' => '6',
	'unit'   => '%',
);
$form_alignment            = isset( $form_data['form_alignment'] ) ? $form_data['form_alignment'] : 'center';
$form_inner_padding        = isset( $form_data['form_inner_padding']['top'] ) ? $form_data['form_inner_padding'] : array(
	'top'    => '32',
	'right'  => '32',
	'bottom' => '32',
	'left'   => '32',
);
$text_before_content_field = isset( $testimonial['before'] ) ? $testimonial['before'] : '';
$text_before_title_field   = isset( $testimonial_title['before'] ) ? $testimonial_title['before'] : '';

// Testimonial From box shadow style.
$form_shadow_type = isset( $form_data['form_shadow_type'] ) ? $form_data['form_shadow_type'] : 'none';
if ( 'none' !== $form_shadow_type ) {
	$box_shadow_properties = isset( $form_data['testimonial_form_box_shadow_properties'] ) ? $form_data['testimonial_form_box_shadow_properties'] : array(
		'horizontal' => '0',
		'vertical'   => '0',
		'blur'       => '6',
		'spread'     => '0',
		'color'      => 'rgba(10,10,10,0.1)',
	);
	$box_shadow_css        = '';
	$box_shadow_css       .= ( $box_shadow_properties['vertical'] >= 0 ) ? $box_shadow_properties['vertical'] . 'px ' : '0 ';
	$box_shadow_css       .= ( $box_shadow_properties['horizontal'] >= 0 ) ? $box_shadow_properties['horizontal'] . 'px ' : '0 ';
	$box_shadow_css       .= ( $box_shadow_properties['blur'] >= 0 ) ? $box_shadow_properties['blur'] . 'px ' : '10px ';
	$box_shadow_css       .= ( $box_shadow_properties['spread'] >= 0 ) ? $box_shadow_properties['spread'] . 'px ' : '0 ';
	$box_shadow_css       .= ( '' !== $box_shadow_properties['color'] ) ? $box_shadow_properties['color'] . ' ' : 'rgba(0,0,0,0.3) ';
	$box_shadow_css       .= ( 'outset' !== $form_shadow_type ) ? $form_shadow_type : '';
	$form_style           .= ' #testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-testimonial-form-container {
        box-shadow:' . $box_shadow_css . ';
    }';
}

// Styles when Label position is Left.
if ( 'left' === $label_position ) {
	$form_style .= '#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field {
        display: flex;
        align-items: center;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field:first-child{
        margin-top: 22px
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form.sp-tpro-fronted-form .sp-tpro-form-field .sp-testimonial-label-section {
        width: 190px;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .sp-testimonial-input-field-img,
    #testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .sp-testimonial-input-field {
        width: ' . esc_attr( $testimonial_form_width ) . 'px;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .tpro_client_before {
        position: absolute;
        top: -20px;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .tpro_client_after {
        position: absolute;
        bottom: -20px;
        left: 0;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .sp-testimonial-input-field .sp-maximum_length{
        position: absolute;
        right: 0;
        top: -22px;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .tpro_client_after.rating {
        position: absolute;
        top: 15px;
        left: 0;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field.sp-tpro-form-content{
        align-items: start;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field.sp-tpro-form-content{
        align-items: start;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field.sp-tpro-form-content .sp-testimonial-label-section{
        margin-top: 8px;
    }#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-testimonial-input-field:has(.tpro-social-profile-wrapper .tpro_client_after) {
		margin-bottom: 15px;
	}';
}

// styles when label position is top.
if ( 'top' === $label_position ) {
	$form_selector = '#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form';
	if ( empty( $text_before_content_field ) ) {
		$form_style .= $form_selector . ' .sp-tpro-form-field.sp-tpro-form-content .sp-testimonial-input-field .sp-maximum_length';
	}
	if ( empty( $text_before_title_field ) ) {
		$form_style .= ', ' . $form_selector . ' .sp-tpro-form-field.sp-tpro-form-title .sp-testimonial-input-field .sp-maximum_length';
	}
	$form_style .= ' {
        position: absolute;
        right: 0;
        top: -28px;
    }
    #testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .tpro-social-profile-wrapper,
    #testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .chosen-container,
    #testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .sp-testimonial-input-field input,
    #testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field textarea{
        max-width: ' . esc_attr( $testimonial_form_width ) . 'px;
    }';
}

$form_style .= '#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form {
    display: flex;
    justify-content: ' . esc_attr( $form_alignment ) . ';
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-testimonial-form-container {
    padding: ' . esc_attr( $form_inner_padding['top'] ) . 'px ' . esc_attr( $form_inner_padding['right'] ) . 'px ' . esc_attr( $form_inner_padding['bottom'] ) . 'px ' . esc_attr( $form_inner_padding['left'] ) . 'px;
    border: ' . esc_attr( $testimonial_form_border['all'] ) . 'px ' . esc_attr( $testimonial_form_border['style'] ) . ' ' . esc_attr( $testimonial_form_border['color'] ) . ';
    border-radius: ' . esc_attr( $testimonial_form_border['radius'] ) . 'px;
    background-color: ' . esc_attr( $form_background_color ) . ';
    width: ' . esc_attr( $testimonial_form_width ) . 'px;
    max-width: 100%;
}

#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .tpro-social-profile-wrapper .tpro-social-profile-item,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .chosen-container-single .chosen-single,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field:not(.tpro-category-list-field,.tpro-social-profiles-field) .sp-testimonial-input-field:not(.sp-tpro-form-submit-button) input,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field textarea,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .chosen-container-multi .chosen-choices{
    border: ' . esc_attr( $form_input_field_styles['all'] ) . 'px ' . esc_attr( $form_input_field_styles['style'] ) . ' ' . esc_attr( $form_input_field_styles['color'] ) . ';
    border-radius: ' . esc_attr( $form_input_field_styles['radius'] ) . 'px;
    background-color: ' . esc_attr( $form_input_field_styles['background_color'] ) . ';
    box-sizing: border-box;
    max-width: 100% !important;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field label{
        font-size: 16px;
        color: ' . esc_attr( $label_color ) . ';
    }
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form #tpro_modal_btn {
    color: ' . esc_attr( $record_button_color['color'] ) . ';
    background: ' . esc_attr( $record_button_color['background'] ) . ';
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form #tpro_modal_btn:hover{
    color: ' . esc_attr( $record_button_color['hover-color'] ) . ';
    background: ' . esc_attr( $record_button_color['hover-background'] ) . ';
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-submit-button input[type=\'submit\']{
    color: ' . esc_attr( $submit_button_color['color'] ) . ';
    background: ' . esc_attr( $submit_button_color['background'] ) . ';
    padding: 15px 30px;
    font-size: 14px;
    transition: all 0.25s;
    text-decoration: none;
    line-height: 1;
    border-radius: 4px;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field label {
    font-weight: 500;
};

#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-submit-button input[type=\'submit\']:hover{
    color: ' . esc_attr( $submit_button_color['hover-color'] ) . ';
    background: ' . esc_attr( $submit_button_color['hover-background'] ) . ';
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .tpro_client_after.tpro-after-rating {
	top:  ' . esc_attr( (int) $testimonial_form_rating_styles['all'] ) . 'px;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating:not(:checked)>label{
    color: ' . esc_attr( $testimonial_form_rating_styles['color'] ) . ';
    font-size: ' . esc_attr( $testimonial_form_rating_styles['all'] ) . 'px;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating>input:checked~label {
    color: ' . esc_attr( $testimonial_form_rating_styles['background_color'] ) . ';
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating:not(:checked)>label:hover,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating:not(:checked)>label:hover~label {
    color: ' . esc_attr( $testimonial_form_rating_styles['hover-color'] ) . ';
}';
if ( 'top' === $label_position ) {
	$form_style .= '#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field.tpro-star-rating-field{
		margin-bottom: ' . esc_attr( (int) $testimonial_form_rating_styles['all'] + 24 ) . 'px;
	}
	#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field.tpro-star-rating-field:has(.tpro-after-rating){
        margin-bottom: ' . esc_attr( (int) $testimonial_form_rating_styles['all'] + 34 ) . 'px;
    }';
}
