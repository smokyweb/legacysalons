<?php
if ( ! function_exists( 'llp_cutomize_control_active_cb' ) ) {
	/**
	* Customize control active callback function to test whether display current control.
	*    Dependency settings could be more than one, treat as logical AND.
	*    Each dependency setting value is array, so testing will be based on in/not in.
	* @param object WP_Csutomize_Control to test on
	* @return boolean if depenency test not enable return true, otherwise test if current value is in the list given.
	*/
	function llp_customize_control_active_cb( $control ) {
		if ( $control instanceof WP_Customize_Control ) {
			$manager = $control->manager;
			$setting = $control->settings;
			$setting = !empty( $setting['default'] ) ? $setting['default'] : false;

			if ( $setting instanceof LoftLoader_Customize_Setting ) {
				$dependency = $setting->dependency;
				if ( ! empty( $dependency ) ) {
					foreach ( $dependency as $id => $attrs ) {
						if ( ! isset( $attrs['value'] ) ) { // If not provide the test value list, return false
							return false;
						}
						if ( $manager->get_setting( $id ) instanceof WP_Customize_Setting ) {
							// Test operator, potential value: in/not in. The default is in.
							$is_not = !empty( $attrs['operator'] ) && ( 'not in' === strtolower( $attrs['operator'] ) );
							$value 	= $manager->get_setting( $id )->value();
							$values = $attrs['value'];
							if ( ( $is_not && in_array( $value, $values ) ) || ( ! $is_not && ! in_array( $value, $values ) ) ) {
								return false;
							}
						}
					}
				}
				return true;
			}
		}
		return false;
	}
}

class LoftLoader_Customize_Setting extends WP_Customize_Setting {
	public $dependency = array();
	public function json() {
		$json = parent::json();
		if ( in_array( $this->id, array( 'loftloader_pro_hand_pick_pages', 'loftloader_pro_site_wide_exclude_pages', 'loftloader_pro_all_pages_exclude_pages' ) ) ) {
			$json['list'] = array();
			$value = $this->value();
			if ( is_array( $value ) && ( count( $value ) > 0 ) ) {
                $post_types = llp_get_post_types( true, true );
				$query = new WP_Query( array( 'post_type' => array_keys( $post_types ), 'post__in' => $value, 'offset' => 0, 'posts_per_page' => -1, 'post_status' => 'publish' ) );
				while ( $query->have_posts() ) {
					$query->the_post();
					$json['list'][ get_the_ID() ] = get_the_title();
				}
				wp_reset_postdata();
			}
		}
		return empty( $this->dependency ) ? $json : array_merge( $json, array(
			'dependency' => $this->dependency
		) );
	}
	protected function update( $value ) {
		$updated = parent::update( $value );
		$this->save_to_file();
		return $updated;
	}
	/**
	* @description add function to generate custom styles to file when settings changed
	* @since version 1.0.7
	*/
	private function save_to_file() {
		global $llp_defaults;
		$access_type = get_filesystem_method();
		if ( ( 'file' === get_option( 'loftloader_pro_css_in_file', '' ) ) && ( 'direct' === $access_type ) ) {
			update_option( 'loftloader_pro_css_in_file_rand_version', rand() );
			$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );
			/* initialize the API */
			if ( ! WP_Filesystem( $creds ) ) {
				return false;
			}

			$styles = apply_filters( 'loftloader_pro_custom_styles', '', true );

			global $wp_filesystem;
			$upload_dir = wp_upload_dir();
			$dir = $upload_dir['basedir'] . '/loftloader-pro';
			$wp_filesystem->is_dir( $upload_dir['basedir'] . '/loftloader-pro' ) ? '' : wp_mkdir_p( $upload_dir['basedir'] . '/loftloader-pro' );
			$wp_filesystem->put_contents(
				$upload_dir['basedir'] . '/loftloader-pro/custom-styles.css',
				wp_kses( $styles, array( "\'", '\"' ) ),
				FS_CHMOD_FILE // predefined mode settings for WP files
			);
		}
	}
}

// LoftLoader base section class, changed the json function to modify the customize action text
class LoftLoader_Customize_Section extends WP_Customize_Section {
	public function json() {
		$array = parent::json();
		$array['customizeAction'] = esc_html__( 'Setting', 'loftloader-pro' );
		return $array;
	}
}
// LoftLoader main switch section class, add a checkbox to control the loader
class LoftLoader_Customize_Switch_Section extends LoftLoader_Customize_Section {
	public $type = 'loftloader_switch';
	/**
	* render function for LoftLoader Switch section
	*/
	protected function render() {
		$switch = $this->manager->get_setting( 'loftloader_pro_main_switch' )->value();
		$classes = 'accordion-section control-section control-section-' . $this->type; ?>
		<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="accordion-section control-section control-section-<?php echo esc_attr( $this->type ); ?>">
			<h3 class="accordion-section-title" tabindex="0">
				<?php echo esc_html( $this->title ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Press return or enter to open this section', 'loftloader-pro' ); ?></span>
				<input type="checkbox" name="loftloader-pro-main-switch" data-customize-setting-link="loftloader_pro_main_switch" value="<?php echo esc_attr( $switch ); ?>" <?php checked( $switch, 'on' ); ?> />
			</h3>
			<ul class="accordion-section-content">
				<li class="customize-section-description-container">
					<div class="customize-section-title">
						<button class="customize-section-back" tabindex="-1">
							<span class="screen-reader-text"><?php esc_html_e( 'Back', 'loftloader-pro' ); ?></span>
						</button>
						<h3>
							<span class="customize-action"><?php esc_html_e( 'Setting', 'loftloader-pro' ); ?></span><?php echo esc_html( $this->title ); ?>
						</h3>
					</div>
					<?php
						if ( ! empty( $this->description ) ) : ?>
							<div class="description customize-section-description"><?php echo wp_kses_post( $this->description ); ?></div> <?php
						endif;
					?>
				</li>
			</ul>
		</li> <?php
	}
}
// LoftLoader base customize control class: add class properties as displaying dependency.
class LoftLoader_Customize_Control extends WP_Customize_Control {
	public $description_above 	= true;
	public $hide 				= false;
	public $input_class 		= '';
	public $input_style  		= '';
	public $after_text 			= '%';
	public $placeholder 		= '';
	public $search_attrs 		= array();

	public function search_attrs() {
		if ( ! empty( $this->search_attrs ) && is_array( $this->search_attrs ) ) {
			foreach( $this->search_attrs as $name => $val ) {
				printf( ' %1$s="%2$s"', $name, esc_attr( $val ) );
			}
		}
	}

	public function render_content() {
		switch ( $this->type ) {
			case 'loftloader-any-page':
				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
				endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
				endif; ?>
				<input type="button" <?php $this->link(); ?> class="button button-primary loftloader-pro-any-page-generate" value="<?php esc_attr_e( 'Generate', 'loftloader-pro' ); ?>" />
				<br/><br/>
				<textarea class="loftloader-pro-any-page-shortcode" cols="35" rows="4"></textarea>
				<div class="customize-control-notifications-container"></div> <?php
				break;
			case 'loftloader_post_types':
				$types = llp_get_post_types();
				$values = $this->value();
				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
				endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
				endif; ?>
				<select <?php $this->link(); ?> multiple>
					<?php foreach ( $types as $pt => $label ) : ?>
						<option value="<?php echo esc_attr( $pt ); ?>"<?php if ( in_array( $pt, $values ) ) : ?> selected<?php endif; ?>>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<div class="customize-control-notifications-container"></div> <?php
				break;
			case 'loftocean_pro_query_posts':
				$this->search_attrs = array_merge( $this->search_attrs, array(
					'class' => 'search-posts',
					'placeholder' => esc_html__( 'Type keyword to search ...', 'loftloader-pro' ),
					'style' => 'width: 100%;'
				) );
				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
				endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
				endif;?>
				<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $this->value() ) ); ?>" />
				<ul class="selected-list"></ul>
				<input type="text" <?php $this->search_attrs(); ?> />
				<a class="button clear-search-results hide" href="#"><?php esc_html_e( 'Clear Search Results', 'loftloader-pro' ); ?></a>
				<ul class="search-results"></ul>
				<div class="customize-control-notifications-container"></div> <?php
				break;
			case 'loftloader_page_post':
				$name = $this->id;
				$pages = get_pages();
				$posts = get_posts( array( 'posts_per_page' => -1 ) );
				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
				endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
				endif;?>
				<select <?php $this->link(); ?> multiple>
					<optgroup label="<?php esc_html_e( 'Pages', 'loftloader-pro' ); ?>">
					<?php foreach ( $pages as $p ) : ?>
						<option value="<?php echo esc_attr( $p->ID ); ?>"<?php if ( in_array( $p->ID, $this->value() ) ) : ?> selected<?php endif; ?>>
							<?php echo esc_html( $p->post_title ); ?>
						</option>
					<?php endforeach; ?>
					</optgroup>
					<optgroup label="<?php esc_html_e( 'Posts', 'loftloader-pro' ); ?>">
					<?php foreach ( $posts as $p ) : ?>
						<option value="<?php echo esc_attr( $p->ID ); ?>"<?php if ( in_array( $p->ID, $this->value() ) ) : ?> selected<?php endif; ?>>
							<?php echo esc_html( $p->post_title ); ?>
						</option>
					<?php endforeach; ?>
					</optgroup>
				</select>
				<div class="customize-control-notifications-container"></div> <?php
				break;
			case 'multiple':
				if ( empty( $this->choices ) || ! is_array( $this->choices ) ) {
					return ;
				}
				$size = min( 10, count( $this->choices ) );
				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
				endif;
				if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
				endif;?>
				<select <?php $this->link(); ?> multiple size="<?php echo esc_attr( $size ); ?>">
					<?php foreach ( $this->choices as $val => $lbl ) : ?>
						<option value="<?php echo esc_attr( $val ); ?>"<?php if ( in_array( $val, $this->value() ) ) : ?> selected<?php endif; ?>>
							<?php echo esc_html( $lbl ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<div class="customize-control-notifications-container"></div> <?php
				break;
			case 'radio':
				if ( empty( $this->choices ) ) {
					return;
				}

				$description = '';
				if ( ! empty( $this->description ) && ! $this->description_above ) {
					$description = sprintf(
						'<span class="description customize-control-description"%s>%s</span>',
						( $this->hide && ( $this->hide === $this->value() ) ) ? ' style="display: none;"' : '',
						$this->description
					);
					$this->description = '';
				}
				parent::render_content();
				echo wp_kses_post( $description );
				break;
			case 'slider':
				if ( empty( $this->input_attrs ) ) {
					return;
				}

				$val = llp_sanitize_float( $this->value() );
				echo '<label class="amount opacity">';
				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
				endif; ?>
				<span class="<?php echo esc_attr( $this->input_class ); ?>">
					<input readonly="readonly" type="text" <?php $this->link(); ?> value="<?php echo esc_attr( $val ); ?>"<?php if ( ! empty( $this->input_style ) ) :?> style="<?php echo esc_attr( $this->input_style ); ?>"<?php endif; ?>>
					<?php echo esc_html( $this->after_text ); ?>
				</span> <?php
				echo '</label>'; ?>
				<div class="ui-slider loader-ui-slider" data-value="<?php echo esc_attr( $val ); ?>" <?php $this->input_attrs(); ?>></div>
				<div class="customize-control-notifications-container"></div> <?php
				break;
			case 'text': ?>
				<label> <?php
					if ( ! empty( $this->label ) ) : ?>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
					endif;
					if ( ! empty( $this->description ) && $this->description_above ) : ?>
						<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
					endif; ?>
					<input
						type="text"
						<?php $this->input_attrs(); ?>
						value="<?php echo esc_attr( $this->value() ); ?>"
						<?php $this->link(); ?>
						<?php if ( ! empty( $this->placeholder ) ) : ?> placeholder="<?php echo esc_attr( $this->placeholder ); ?>"<?php endif; ?>
					/> <?php
					if ( ! empty( $this->description ) && ! $this->description_above ) : ?>
						<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
					endif; ?>
				</label> <?php
				break;
			case 'number':
				$unit = $this->manager->get_setting( 'loftloader_pro_progress_width_unit' )->value(); ?>
				<label> <?php
					if ( ! empty( $this->label ) ) : ?>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
					endif;
					if ( ! empty( $this->description ) ) : ?>
						<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
					endif; ?>
					<span class="barwidth">
						<input type="<?php echo esc_attr( $this->type ); ?>" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
						<span class="barwidth-unit">
							<input type="checkbox" name="loftloader_pro_barwidth_unit" value="percentage" <?php checked( $unit, 'on' ); ?>>
							<span></span>
						</span>
					</span>
				</label> <?php
				break;
			case 'number-only': ?>
				<label> <?php
					if ( ! empty( $this->label ) ) : ?>
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
					endif;
					if ( ! empty( $this->description ) && $this->description_above ) : ?>
						<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
					endif; ?>
					<input type="number" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
					&nbsp; &nbsp;
					<?php echo esc_html( $this->after_text ) . '<br/>';
					if ( ! empty( $this->description ) && ! $this->description_above ) : ?>
						<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
					endif; ?>
				</label>
				<div class="customize-control-notifications-container"></div><?php
				break;
			case 'check': ?>
				<label> <?php
				if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
				endif;
				if ( ! empty( $this->description ) && $this->description_above ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
				endif; ?>
					<input
						class="loftlader-pro-checkbox"
						type="checkbox"
						value="<?php echo esc_attr( $this->value() ); ?>"
						name="<?php echo esc_attr( $this->id ); ?>"
						<?php checked( 'on', $this->value() ); ?>
					/>
					<input style="display:none;" type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> <?php checked( 'on', $this->value() ); ?> /> <?php
				if ( ! empty( $this->description ) && ! $this->description_above ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
				endif; ?>
				</label> <?php
				break;
			case 'textarea':
				if ( ! empty( $this->label ) ) : ?>
					<label><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span></label> <?php
				endif;
				if ( ! empty( $this->description ) && $this->description_above ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
				<?php endif; ?>
				<textarea
					<?php $this->input_attrs(); ?>
					<?php if ( ! empty( $this->placeholder ) ) : ?> placeholder="<?php echo esc_attr( $this->placeholder ); ?>"<?php endif; ?>
					<?php $this->link(); ?>>
					<?php echo esc_textarea( $this->value() ); ?>
				</textarea> <?php
				if ( ! empty( $this->description ) && ! $this->description_above ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
				endif;
				break;
			case 'description':
				if ( empty( $this->description ) ) {
					return ;
				} ?>
				<span class="description-text"><?php echo wp_kses_post( $this->description ); ?></span> <?php
				break;
			case 'loftloader_margins':
				$values = array( '', '', '', '' );
				$current_values = $this->value();
				if ( is_array( $current_values ) && ( count( $current_values ) === 4 ) ) {
					foreach( $current_values as $i => $v ) {
						if ( is_numeric( $v ) ) {
							$values[ $i ] = $v;
						}
					}
				}
				if ( ! empty( $this->label ) ) : ?><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><?php endif; ?>
                <div id="loftloader_option_percentage_margin">
                	<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $values ) ); ?>">
                    <ul class="loftloader-margin">
                        <li>
                            <input type="number" data-setting="top" min="-99999" placeholder="" value="<?php echo $values[ 0 ]; ?>">
                            <label class="loftloader-label"><?php esc_html_e( 'Top', 'loftloader-pro' ); ?></label>
                        </li>

                        <li>
                            <input type="number" data-setting="right" min="-99999" placeholder="" value="<?php echo $values[ 1 ]; ?>">
                            <label class="loftloader-label"><?php esc_html_e( 'Right', 'loftloader-pro' ); ?></label>
                        </li>

                        <li>
                            <input type="number" data-setting="top" min="-99999" placeholder="" value="<?php echo $values[ 2 ]; ?>">
                            <label class="loftloader-label"><?php esc_html_e( 'Bottom', 'loftloader-pro' ); ?></label>
                        </li>

                        <li>
                            <input type="number" data-setting="top" min="-99999" placeholder="" value="<?php echo $values[ 3 ]; ?>">
                            <label class="loftloader-label"><?php esc_html_e( 'Left', 'loftloader-pro' ); ?></label>
                        </li>
                    </ul>
                </div><?php
                break;
			default:
				parent::render_content();
		}
	}
}
// Add new radio type control class, show the input horizontally.
class LoftLoader_Customize_Horizontal_Radio_Control extends LoftLoader_Customize_Control {
	public $wrap_id 			= '';
	public $show_label 			= false;
	public $description_above 	= true;
	public $hide 				= false;
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}

		$name = '_customize-radio-' . $this->id;
		if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
		endif;
		if ( ! empty( $this->description ) && $this->description_above ) : ?>
			<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
		endif; ?>
		<div id="<?php if ( empty($this->wrap_id ) ) : ?>loftloader_option_bg<?php else : echo esc_attr( $this->wrap_id ); endif; ?>"> <?php
		foreach ( $this->choices as $value => $attrs ) :
			$attr = '';
			$input_class = ! empty( $attrs['class'] ) ? $attrs['class'] : $value;
			if ( ! empty( $attrs['attr'] ) ) {
				foreach ( (array) $attrs['attr'] as $attr_name => $attr_value ) {
					$attr .= ' ' . $attr_name . '="' . $attr_value . '"';
				}
			}
			$item_id = empty( $attrs['id'] ) ? sanitize_title( $this->id . '-' . $value ) : $attrs['id']; ?>
			<label for="<?php echo esc_attr( $item_id ); ?>" title="<?php echo esc_attr( $attrs['label'] ); ?>">
				<input
					class="loftloader-radiobtn <?php echo wp_kses_post( $input_class ); ?>"
					id="<?php echo esc_attr( $item_id ); ?>"
					type="radio"
					value="<?php echo esc_attr( $value ); ?>"
					name="<?php echo esc_attr( $name ); ?>"
					<?php $this->link(); ?>
					<?php checked( $this->value(), $value ); ?>
					<?php echo wp_kses_post( $attr ); ?>
				/>
				<span>
					<?php if ( $this->show_label ) {
						echo esc_html( $attrs['label'] );
					} ?>
				</span>
			</label> <?php
		endforeach;
		echo '</div>';
		if ( ! empty( $this->description ) && !$this->description_above ) : ?>
			<span class="description customize-control-description"<?php if ( $this->hide && ( $this->hide === $this->value() ) ) : ?> style="display: none;"<?php endif; ?>>
				<?php echo wp_kses_post( $this->description ); ?>
			</span> <?php
		endif; ?>
		<div class="customize-control-notifications-container"></div> <?php
	}
}
// Add new radio type control class for loader animation choices.
class LoftLoader_Customize_Animation_Types_Control extends WP_Customize_Control {
	public function render_content() {
		if ( empty( $this->choices ) ){
			return;
		}

		if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
		endif;
		echo '<button class="customize-more-toggle" aria-expanded="false"><span class="screen-reader-text">' . esc_html__( 'More info', 'loftloader-pro' ) . '</span></button>';
		if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description" style="display: none;"><?php echo wp_kses_post( $this->description ); ?></span> <?php
		endif; ?>
		<div id="loftloader_option_animation"><?php
		$name = '_customize-radio-' . $this->id;
		foreach ( $this->choices as $value => $attrs ) :
			$attr = '';
			if ( ! empty( $attrs['attr'] ) ) {
				foreach ( (array) $attrs['attr'] as $attr_name => $attr_value ) {
					$attr .= ' ' . $attr_name . '="' . $attr_value . '"';
				}
			}
			$item_id = sanitize_title( $this->id . '-' . $value ); ?>
			<label for="<?php echo esc_attr( $item_id ); ?>" title="<?php echo esc_attr( $attrs['label'] ); ?>">
				<input
					id="<?php echo esc_attr( $item_id ); ?>"
					class="loftloader-radiobtn <?php echo esc_attr( $value ); ?>"
					type="radio"
					value="<?php echo esc_attr( $value ); ?>"
					name="<?php echo esc_attr( $name ); ?>"
					<?php $this->link(); ?>
					<?php checked( $this->value(), $value ); ?>
					<?php echo wp_kses_post( $attr ); ?>
				/>
				<span></span>
			</label> <?php
		endforeach; ?>
		</div> <?php
	}
}
// Add new number type control class with text after the element.
class LoftLoader_Customize_Number_Text_Control extends LoftLoader_Customize_Control {
	public $after_text 			= '';
	public $input_class 		= '';
	public $input_wrap_class 	= '';
	public function render_content() { ?>
		<label> <?php
			if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span> <?php
			endif;
			if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span> <?php
			endif; ?>
			<span class="<?php echo esc_attr( $this->input_wrap_class ); ?>">
				<input
					class="<?php echo esc_attr( $this->input_class ); ?>"
					type="<?php echo esc_attr( $this->type ); ?>"
					<?php $this->input_attrs(); ?>
					value="<?php echo esc_attr( $this->value() ); ?>"
					<?php $this->link(); ?>
				/>
				<?php if ( ! empty( $this->after_text ) ) {
					echo esc_html( $this->after_text );
				} ?>
			</span>
		</label> <?php
	}
}

/**
* Plugin customize config base class
*	Each config class will extend this base class
*		1. Action to register customize setting, panel, section and control
*		3. Filter to add custom styles based on theme settings for frontend
*
* @since 1.1.9
*/
class LoftLoader_Pro_Customize_Base {
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_customize_elements' ) );
	}
	public function register_customize_elements( $wp_customize ) { }
}
