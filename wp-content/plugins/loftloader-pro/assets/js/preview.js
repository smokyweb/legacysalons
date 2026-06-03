/**
* Copyright (c) Loft.Ocean
* http://www.loftocean.com
*/

( function( api, $, preview, parentAPI ) {
	"use strict";
	if ( typeof parentAPI.settings.settings.loftloader_pro_main_switch === 'undefined' ) {
		return '';
	}

	var $loader = $( '#loftloader-wrapper' ), $loaderBG = $( '#loftloader-wrapper .loader-bg' ),
	 	loftloader_barwidth = parentAPI.settings.settings.loftloader_barwidth.value,
		loftloader_barwidth_unit = parentAPI.settings.settings.loftloader_pro_progress_width_unit.value,
		loftloader_rainbow_outer = parentAPI.settings.settings.loftloader_pro_animation_rainbow_outer_color.value,
		loftloader_rainbow_middle = parentAPI.settings.settings.loftloader_pro_animation_rainbow_middle_color.value,
		loftloader_rainbow_inner = parentAPI.settings.settings.loftloader_pro_animation_rainbow_inner_color.value,
		message_text_timer = false, random_message_text_timer = false, $progressBarLoad = $( '#loftloader-wrapper .bar .load' );

	/*
	* @description Update the number when choose progress type bar+number
	* @param int current percentage number 0 - 100
	*/
	function llp_update_progress_count( current, $load ) {
		if ( $load && $load.length ) {
			var $count = $load.next( '.load-count' ),
				container_width = $load.width() * current / 100,
				offset_x = ( container_width > $count.width() ) ? ( container_width - $count.width() ) : 0,
				offset_y = $load.parent().hasClass( 'bottom' ) ? '-100%' : '100%';
			$count.css( 'transform', 'translate(' + offset_x + 'px, ' + offset_y + ')' );
		}
	}
	function llp_preview_update_bar_number_count_position() {
		var $bar = $( '#loftloader-wrapper .bar' );
		if ( $bar.children( '.load-count' ).length && llp_update_progress_count ) {
			llp_update_progress_count( 100, $bar.children( '.load' ) );
		}
	}
	$( window ).resize( function() {
		llp_preview_update_bar_number_count_position();
	} );
	if ( $loader.hasClass( 'loftloader-once' ) ) {
		$loader.addClass( 'loftloader-progress' );
	}
	// Helper functions
	/***** Update style element by id inside <head>, if not exist, create new *****/
	function llp_update_style( id, style ) {
		var $style = $( 'head' ).find( '#' + id );
		$style = $style.length ? $style : $( '<style>', { 'id': id, 'html': '' } ).appendTo( $( 'head' ) );
		$style.html( style );
	}
	/***** Convert hex color to rgba style *****/
	function llp_hex2rgba( hex, opacity ) {
		hex = hex.toLowerCase();
		var r = llp_convert_hex( hex.charAt( 1 ), hex.charAt( 2 ) ),
			g = llp_convert_hex( hex.charAt( 3 ), hex.charAt( 4 ) ),
			b = llp_convert_hex( hex.charAt( 5 ), hex.charAt( 6 ) );
		return 'rgba(' + r + ',' + g + ',' + b + ',' + opacity + ')';
	}
	/***** Calculate each element of rgba *****/
	function llp_convert_hex( first, second ) {
		var hex = {'0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9, 'a': 10, 'b': 11, 'c': 12, 'd': 13, 'e': 14, 'f': 15};
		return hex[ first ] * 16 + hex[ second ];
	}
	/** Update progress bar gradient color **/
	function llpUpdateProgressBarGradientColors() {
		var startColor = api( 'loftloader_pro_progress_bar_gradient_start_color' )(),
			endColor = api( 'loftloader_pro_progress_bar_gradient_end_color' )();
		if ( startColor && endColor ) {
			var style = '#loftloader-wrapper span.bar span.load.gradient-color-enabled {', colors = '(90deg, ' + startColor + ', ' + endColor + ')';
			[ '-webkit-', '-o-', '-moz-', '' ].forEach( function( prefix ) {
				style += ' background-image: ' + prefix + 'linear-gradient' + colors + ';';
			} );
			style += ' }';
			llp_update_style( 'loftloader-pro-progress-bar-gradient-colors', style );
		}
	}
	// Change loader end animation style instantly
	api( 'loftloader_pro_bg_animation', function( value ) {
		value.bind( function( to ) {
			var split_reveal = [ 'split-reveal-v', 'split-reveal-h' ];
			$loader.removeClass( 'end-fade end-up end-down end-split-h end-split-v end-shrink-fade split-reveal-v split-reveal-h split-diagonally end-left end-right end-no-animation' );
			switch ( to ) {
				case 'split-reveal-v':
					$loader.addClass( 'end-split-h split-reveal-v' );
					break;
				case 'split-reveal-h':
					$loader.addClass( 'end-split-v split-reveal-h' );
					break;
				case 'split-diagonally-v':
					$loader.addClass( 'end-split-v split-diagonally' );
					break;
				case 'split-diagonally-h':
					$loader.addClass( 'end-split-h split-diagonally' );
					break;
				default:
					$loader.addClass( 'end-' + to );
			}
		} );
	} );
	// Change loader background opacity instantly
	api( 'loftloader_bgopacity', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-bg-opacity',
				'#loftloader-wrapper .loader-bg { opacity: ' + ( to / 100 ) + '; }'
			);
		});
	} );
	// Change loader background color
	api( 'loftloader_pro_bg_color', function( value ) {
		value.bind( function( to ) {
			var selectors = [
				'#loftloader-wrapper.end-split-h .loader-bg:before',
				'#loftloader-wrapper.end-split-h .loader-bg:after',
				'#loftloader-wrapper.end-split-v .loader-bg:before',
				'#loftloader-wrapper.end-split-v .loader-bg:after',
				'#loftloader-wrapper.end-fade .loader-bg',
				'#loftloader-wrapper.end-up .loader-bg',
				'#loftloader-wrapper.end-down .loader-bg',
				'#loftloader-wrapper.end-left .loader-bg',
				'#loftloader-wrapper.end-right .loader-bg',
				'#loftloader-wrapper.end-no-animation .loader-bg',
				'#loftloader-wrapper.end-shrink-fade .loader-bg:before'
			];
			llp_update_style(
				'loftloader-pro-bg-color',
				selectors.join( ', ' ) + ' { background-color: ' + to + '; }'
			);
		} );
	} );
	// Change loader color instantly (single color loader)
	api( 'loftloader_pro_animation_color', function( value ) {
		value.bind(function( to ) {
			var rgba = llp_hex2rgba( to, 0.5 );
			llp_update_style(
				'loftloader-pro-end-color',
				'#loftloader-wrapper .loader-inner #loader, #loftloader-wrapper.loftloader-ducks #loader span { color: ' + to + '} #loftloader-wrapper.loftloader-crystal #loader span { box-shadow: 0 -15px 0 0 ' + rgba + ', 15px -15px 0 0 ' + rgba + ', 15px 0 0 0 ' + rgba + ', 15px 15px 0 0 ' + rgba + ', 0 15px 0 0 ' + rgba + ', -15px 15px 0 0 ' + rgba + ', -15px 0 0 0 ' + rgba + ', -15px -15px 0 0 ' + rgba + '; }'
			);
		} );
	} );
	// Change loader colors instantly (two colors loader)
	api( 'loftloader_pro_animation_crossing_left_color', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-crossing-left-color',
				'#loftloader-wrapper.loftloader-crossing #loader span:before { background: ' + to + ';}'
			);
		} );
	} );
	api( 'loftloader_pro_animation_crossing_right_color', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-crossing-right-color',
				'#loftloader-wrapper.loftloader-crossing #loader span:after { background: ' + to + ';}'
			);
		} );
	} );
	// Change loader blend mode instantly
	api( 'loftloader_blendmode', function( value ) {
		value.bind( function( to ) {
			$loader
				.removeClass( 'loftloader-blendmode-lighten loftloader-blendmode-darken loftloader-blendmode-none' )
				.addClass( 'loftloader-blendmode-' + to );
		} );
	} );
	// Change loader color instantly (three colors loader)
	api( 'loftloader_pro_animation_rainbow_outer_color', function( value ) {
		value.bind( function( to ) {
			var middle = loftloader_rainbow_middle,
				inner = loftloader_rainbow_inner;
			loftloader_rainbow_outer = to;
			llp_update_style(
				'loftloader-pro-rainbow-color',
				'#loftloader-wrapper.loftloader-rainbow #loader span:before { box-shadow: 0 0 0 10px ' + inner + ', 0 0 0 20px ' + middle + ', 0 0 0 30px ' + to + '; }'
			);
		} );
	} );
	api( 'loftloader_pro_animation_rainbow_middle_color', function( value ) {
		value.bind(function( to ) {
			var outer = loftloader_rainbow_outer,
				inner = loftloader_rainbow_inner;
			loftloader_rainbow_middle = to;
			llp_update_style(
				'loftloader-pro-rainbow-color',
				'#loftloader-wrapper.loftloader-rainbow #loader span:before { box-shadow: 0 0 0 10px ' + inner + ', 0 0 0 20px ' + to + ', 0 0 0 30px ' + outer + '; }'
			);
		} );
	} );
	api( 'loftloader_pro_animation_rainbow_inner_color', function( value ) {
		value.bind( function( to ) {
			var middle = loftloader_rainbow_middle,
				outer = loftloader_rainbow_outer;
			loftloader_rainbow_inner = to;
			llp_update_style(
				'loftloader-pro-rainbow-color',
				'#loftloader-wrapper.loftloader-rainbow #loader span:before { box-shadow: 0 0 0 10px ' + to + ', 0 0 0 20px ' + middle + ', 0 0 0 30px ' + outer + '; }'
			);
		} );
	} );
	// Change loader frame width
	api( 'loftloader_pro_animation_frame_width', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-loader-frame-width',
				'#loftloader-wrapper.loftloader-frame #loader { width: ' + to + 'px; }'
			);
		} );
	} );
	// Change loader frame height
	api( 'loftloader_pro_animation_frame_height', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-loader-frame-height',
				'#loftloader-wrapper.loftloader-frame #loader { height: ' + to + 'px; }'
			);
		} );
	} );
	// Change loader frame border width
	api( 'loftloader_pro_animation_frame_border_width', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-loader-frame-border-width',
				'#loftloader-wrapper.loftloader-frame #loader span:after, #loftloader-wrapper.loftloader-frame #loader span:before { width: ' + to + 'px; }'
			);
			llp_update_style(
				'loftloader-pro-loader-frame-border-height',
				'#loftloader-wrapper.loftloader-frame #loader:after, #loftloader-wrapper.loftloader-frame #loader:before { height: ' + to + 'px; }'
			);
		} );
	} );
	// Toggle loader looping option
	api( 'loftloader_looping', function( value ) {
		value.bind( function( to ) {
			$loader
				.removeClass( 'loftloader-forever loftloader-once' )
				.addClass( 'loftloader-' + to );
			var description = $( '#customize-control-loftloader_looping .customize-control-description', preview );
			if ( description.length ) {
				( to == 'once' ) ? description.slideDown( 'slow', function(){
					$( this ).css( 'display', 'block' );
				} ) : description.slideUp( 'slow' );
			}
			if ( $loader.hasClass( 'loftloader-once' ) ) {
				$loader.addClass( 'loftloader-progress' );
			} else {
				var progress = $( '[data-customize-setting-link=loftloader_progress]:checked', parent.document );
				if ( progress.length ) {
					( progress.val() == 'none' ) ? $loader.removeClass( 'loftloader-progress' ) : $loader.addClass( 'loftloader-progress' );
				}
			}
		}) ;
	} );
	// Change loader image width if needed instantly
	api( 'loftloader_imgwidth', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-img-width',
				'#loftloader-wrapper.loftloader-imgfading #loader img, #loftloader-wrapper.loftloader-imgloading #loader img, #loftloader-wrapper.loftloader-imgrotating #loader img, #loftloader-wrapper.loftloader-imgbouncing #loader img, #loftloader-wrapper.loftloader-imgstatic #loader img { width: ' + to + 'px; }'
			);
		} );
	} );
	// Change loader (imgloading) direction instantly
	api( 'loftloader_loaddirection', function( value ) {
		value.bind( function( to ) {
			$loader
				.removeClass( 'imgloading-horizontal imgloading-vertical' )
				.addClass( 'imgloading-' + to );
		} );
	} );
	// Change loader (imgloading) veritcal direction instantly
	api( 'loftloader_custom_image_loading_vertical_direction', function( value ) {
		value.bind( function( to ) {
			$loader
				.removeClass( 'top-to-bottom' )
				.addClass( to );
		} );
	} );
	// Change loader (imgrotating) direction instantly
	api( 'loftloader_rotatedirection', function( value ) {
		value.bind( function( to ) {
			var direction = { '2d': 'twod', '3d-y': 'threed-y', '3d-x': 'threed-x' };
			$loader
				.removeClass( 'twod threed-y threed-x' )
				.addClass( direction[ to ] );
		} );
	} );
	// Change loader (imgrotating) speed curve instantly
	api( 'loftloader_rotate_curve', function( value ) {
		value.bind( function( to ) {
			to ? $loader.addClass( 'ease-back' ) : $loader.removeClass( 'ease-back' );
		} );
	} );
	// Toggle loader (imgbouncing) rolling option
	api( 'loftloader_bouncerolling', function( value ) {
		value.bind( function( to ) {
			to ? $loader.addClass( 'loftloader-rolling' ) : $loader.removeClass( 'loftloader-rolling' );
		} );
	} );
	// Change loader grogress (bar) position instantly
	api( 'loftloader_barposition', function( value ) {
		value.bind( function( to ) {
			var $progress = $loader.find( 'span.bar' ),
				$next = $progress.next( '.loader-bg' ),
				$wrap = $loader.find( '.loader-inner' ),
				positions = [ 'top', 'bottom' ];
			if ( $progress.length ) {
				$progress.removeClass( 'top bottom' );
				if ( $next.length ) {
					( positions.indexOf( to ) === -1 ) ? $wrap.children( '#loader' ).after( $progress.detach() ) : $progress.addClass( to );
				} else {
					( positions.indexOf( to ) === -1 ) ? $wrap.children( '#loader' ).after( $progress.detach() ) : $wrap.after( $progress.addClass( to ).detach() );
				}
				llp_preview_update_bar_number_count_position();
			}
		} );
	} );
	// Change loader progress (bar) width instantly
	api( 'loftloader_barwidth', function( value ) {
		value.bind( function( to ) {
			if ( typeof loftloader_barwidth_unit !== 'undefined' ) {
				var unit = ( loftloader_barwidth_unit === 'on' ) ? 'px' : 'vw';
				loftloader_barwidth = to;
				to = ( ( loftloader_barwidth_unit !== 'on' ) && ( to > 100 ) ) ? 100 : to;
				llp_update_style( 'loftloader-pro-progress-bar-width', '#loftloader-wrapper span.bar { width: ' + to + unit + '; }') ;
				llp_preview_update_bar_number_count_position();
			}
		} );
	} );
	// Change loader progress (bar) width unit instantly
	api( 'loftloader_pro_progress_width_unit', function( value ) {
		value.bind(function( to ) {
			var unit = to ? 'px' : 'vw', width = loftloader_barwidth;
			loftloader_barwidth_unit = to ? 'on' : 'off';
			width = ( ( unit == 'vw' ) && ( width > 100 ) ) ? 100 : width;
			if ( typeof width !== 'undefined' ) {
				llp_update_style( 'loftloader-pro-progress-bar-width', '#loftloader-wrapper span.bar { width: ' + width + unit + '; }' );
				llp_preview_update_bar_number_count_position();
			}
		} );
	} );
	// Change loader progress (bar) height instantly
	api( 'loftloader_barheight', function( value ) {
		value.bind( function( to ) {
			llp_update_style( 'loftloader-pro-progress-bar-height', '#loftloader-wrapper span.bar { height: ' + to + 'px; }' );
			llp_preview_update_bar_number_count_position();
		} );
	} );
	// Change loader progress color instantly
	api( 'loftloader_pro_progress_color', function( value ) {
		value.bind( function( to ) {
			llp_update_style( 'loftloader-pro-progress-color', '#loftloader-wrapper span.bar, #loftloader-wrapper span.percentage { color: ' + to + '; }' );
		} );
	} );
	// Progress bar gradient colors
	api( 'loftloader_pro_progress_bar_enable_gradient_color', function( value ) {
		value.bind( function( to ) {
			if ( $progressBarLoad.length ) {
				if ( to ) {
					$progressBarLoad.addClass( 'gradient-color-enabled' );
					llpUpdateProgressBarGradientColors();
				} else {
					$progressBarLoad.removeClass( 'gradient-color-enabled' );
				}
			}
		} );
	} );
	api( 'loftloader_pro_progress_bar_gradient_start_color', function( value ) {
		value.bind( function( to ) {
			llpUpdateProgressBarGradientColors();
		} );
	} );
	api( 'loftloader_pro_progress_bar_gradient_end_color', function( value ) {
		value.bind( function( to ) {
			llpUpdateProgressBarGradientColors();
		} );
	} );
	// Change loader (percentage) position instantly
	api( 'loftloader_percentageposition', function( value ) {
		value.bind( function( to ) {
			var $progress = $loader.find( 'span.percentage' );
			if ( $progress.length ) {
				( to === 'middle' ) ? $progress.addClass( 'middle' ) : $progress.removeClass( 'middle' );
			}
		} );
	} );
	// Change loader progress (percentage) layer instantly
	api( 'loftloader_progresslayer', function( value ) {
		value.bind( function( to ) {
			var $progress = $loader.find( 'span.percentage' );
			if ( $progress.length ) {
				( to === 'front' ) ? $progress.addClass( 'front' ) : $progress.removeClass( 'front' );
			}
		} );
	} );
	// Change loader progress (percentage) font size instantly
	api( 'loftloader_percentagesize', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-progress-percentage-fontsize',
				'body #loftloader-wrapper span.percentage, body #loftloader-wrapper span.bar span.load-count { font-size: ' + to + 'px; }'
			);
			llp_preview_update_bar_number_count_position();
		} );
	} );
	// Change loader percentage number font weight instantly
	api( 'loftloader_pro_progress_number_font_weight', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-percentage-number-font-weight',
				'#loftloader-wrapper span.percentage, #loftloader-wrapper span.bar span.load-count { font-weight: ' + to + '; }'
			);
			llp_preview_update_bar_number_count_position();
		} );
	} );
	// Change loader percentage number letter spacing instantly
	api( 'loftloader_pro_progress_number_letter_spacing', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-percentage-number-letter-spacing',
				'#loftloader-wrapper span.percentage, #loftloader-wrapper span.bar span.load-count { letter-spacing: ' + to + '; }'
			);
			llp_preview_update_bar_number_count_position();
		} );
	} );
	// Change loader message text instantly
	api( 'loftloader_pro_message_text', function( value ) {
		value.bind( function( to ) {
			message_text_timer ? clearTimeout( message_text_timer ) : '';
			message_text_timer = setTimeout( function() {
				parent.wp.customize.previewer.refresh();
			}, 500 );
		} );
	} );
	// Change loader random message text instantly
	api( 'loftloader_pro_random_message_text', function( value ) {
		value.bind( function( to ) {
			random_message_text_timer ? clearTimeout( random_message_text_timer ) : '';
			random_message_text_timer = setTimeout( function() {
				parent.wp.customize.previewer.refresh();
			}, 500 );
		} );
	} );
	// Change loader message font size instantly
	api( 'loftloader_pro_message_size', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-message-fontsize',
				'#loftloader-wrapper .loader-message { font-size: ' + to + 'px; }'
			);
		} );
	} );
	// Change loader message color instantly
	api( 'loftloader_pro_message_color', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-message-color',
				'#loftloader-wrapper .loader-message { color: ' + to + '; }'
			);
		} );
	} );
	// Change loader message font weight instantly
	api( 'loftloader_pro_message_font_weight', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-message-font-weight',
				'#loftloader-wrapper .loader-message { font-weight: ' + to + '; }'
			);
		} );
	} );
	// Change loader message letter spacing instantly
	api( 'loftloader_pro_message_letter_spacing', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-message-letter-spacing',
				'#loftloader-wrapper .loader-message { letter-spacing: ' + to + '; }'
			);
		} );
	} );
	// Change loader message letter spacing instantly
	api( 'loftloader_pro_message_line_height', function( value ) {
		value.bind( function( to ) {
			llp_update_style(
				'loftloader-pro-message-line-height',
				'#loftloader-wrapper .loader-message { line-height: ' + to + '; }'
			);
		} );
	} );
	// Smooth Page transition settings
	api( 'loftloader_pro_insite_transition_reverse_bg_animation', function( value ) {
		value.bind( function( to ) {
			if ( $loaderBG.length ) {
				to ? $loaderBG.addClass( 'spt-bg-reverse' ) : $loaderBG.removeClass( 'spt-bg-reverse' );
			}
		} );
	} );
	// Inner Elements Animation
	api( 'loftloader_pro_inner_elements_entrance_animation', function( value ) {
		value.bind( function ( to ) {
			$loader.removeClass( 'inner-enter-fade inner-enter-up' );
			if ( to ) {
				$loader.addClass( to );
			}
		} );
	} );
	api( 'loftloader_pro_inner_elements_exit_animation', function( value ) {
		value.bind( function ( to ) {
			to ? $loader.addClass( 'inner-end-up' ) : $loader.removeClass( 'inner-end-up' );
		} );
	} );
	api( 'loftloader_pro_adaptive_loading_screen_height_on_mobile', function( value ) {
		value.bind( function( to ) {
			if ( $loader.length ) {
				to ? $loader.addClass( 'adaptive-height' ) : $loader.removeClass( 'adaptive-height' );
			}
		} );
	} );
	// Save customize styles
	api( 'loftloader_pro_css_in_file', function( value ) {
		value.bind( function( to ) {
			var description = $( '#customize-control-loftloader_pro_css_in_file .customize-control-description', preview );
			if ( description.length ) {
				( to == 'file' ) ? description.slideDown( 'slow', function() {
					$(this).css('display', 'block');
				} ) : description.slideUp( 'slow' );
			}
		} );
	} );
	api( 'loftloader_pro_custom_css', function( value ) {
		value.bind( function( to ) {
			var id = 'loftloader-custom-inline-style', $style = $( 'head' ).find( '#' + id ), $bodyStyle = $( 'body' ).find( '#' + id );
			$style.length ? $style.remove() : '';
			$bodyStyle = $bodyStyle.length ? $bodyStyle : $( '<style>', { 'id': id, 'html': '' } ).appendTo( $( 'body' ) );
			$bodyStyle.html( to );
		} );
	} );
} ) ( wp.customize, jQuery, parent.document, parent.wp.customize );
