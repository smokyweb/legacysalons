/**
* Copyright (c) Loft.Ocean
* http://www.loftocean.com
*/

( function( api, $ ) {
	"use strict";
	$( 'head' ).append( $( '<style>', { 'id': 'loftloader-pro-hide-site-title', 'text': '.site-title { opacity:  0; }' } ) );
	// Main Switch section
	api.LoftLoaderSwitchSection = api.Section.extend( {
		initialize: function () {
			return api.Section.prototype.initialize.apply( this, arguments );
		},
		ready: function() {
			var checked = this.container.find( 'input[name=loftloader-pro-main-switch]' ).is( ':checked' );
			$( '#customize-theme-controls' ).attr( 'class', 'loftloader-controls-wrapper' );
			checked ? '' : $( '#customize-theme-controls' ).addClass( 'loftloader-settings-disabled' );
		},
		attachEvents: function () {
			var container = this.container;
			container.on( 'change', 'input[name=loftloader-pro-main-switch]', function( e ) {
				var checked = $( this ).is( ':checked' ),
					controls_wrap = $( '#customize-theme-controls' );
				api( 'loftloader_pro_main_switch' )( checked );
				checked ? controls_wrap.removeClass( 'loftloader-settings-disabled' ) : controls_wrap.addClass( 'loftloader-settings-disabled' );
			} );
		}
	} );
	$.extend( api.sectionConstructor, { loftloader_switch: api.LoftLoaderSwitchSection } );

	// Slider control
	api.controlConstructor.slider = api.Control.extend( {
		ready: function() {
			var elem = this.container.find( '.loader-ui-slider' ),
				input = this.container.find( 'input[data-customize-setting-link]' );
			elem.slider( {
				'range': 'min',
				'min': elem.data( 'min' ),
				'max': elem.data( 'max' ),
				'value': elem.data( 'value' ),
				'step': elem.data( 'step' ),
				'slide': function( event, ui ) {
					input.val( ui.value ).trigger( 'change' );
				}
			} );
		}
	} );

	// posts query
	api.controlConstructor.loftocean_pro_query_posts = api.Control.extend( {
		list: '',
		searchList: '',
		ready: function() {
			this.initValue();
			this.addEvents();
		},
		initValue: function() {
			var id = this.id, settings = api.settings.settings[ id ] ? api.settings.settings[ id ] : false;
			if ( settings.list ) {
				var self = this;
				this.list = this.container.find( '.selected-list' );
				this.searchList = this.container.find( '.search-results' );
				$.each( settings.list, function( pid, title ) {
					self.addItem( { 'pid': pid, 'title': title } );
				} );
			}
		},
		addItem: function( item ) {
			this.list.append(
				$( '<li>', { 'class': 'list-item', 'html': item.title, 'data-post-id': item.pid } ).append(
					$( '<a>', { 'href': '#', 'class': 'remove-item', 'text': 'x' } )
				)
			);
		},
		showSearchItems: function( items ) {
			var searchList = this.searchList, $input = this.container.find( 'input[data-customize-setting-link]' ),
				currentValue = $input.val();
			currentValue = ( '' != currentValue ) ? currentValue.split( ',' ) : [];
			searchList.html( '' );
			$.each( items, function( pid, title ) {
				if ( -1 === currentValue.indexOf( pid ) ) {
					searchList.append(
						$( '<li>', { 'class': 'search-result-item', 'html': title, 'data-post-id': pid } )
					);
				}
			} );
			if ( ! searchList.children().length ) {
				searchList.append( $( '<li>', { 'class': 'nothing-found', 'text': 'Nothing found' } ) );
				this.container.find( '.clear-search-results' ).addClass( 'hide' );
			} else {
				this.container.find( '.clear-search-results' ).removeClass( 'hide' );
			}
		},
		addEvents: function () {
			var timer = false, self = this, $list = this.container.find( '.selected-list' ),
				$input = this.container.find( 'input[data-customize-setting-link]' );
			this.container.on( 'click', '.search-results .search-result-item', function( e ) {
				e.preventDefault();
				var $item = $( this ), pid = $item.data( 'post-id' ), title = $item.html(), currentValue = $input.val();
				currentValue = ( '' != currentValue ) ? currentValue.split( ',' ) : [];
				currentValue.push( pid );
				$input.val( currentValue.join( ',' ) ).trigger( 'change' );
				self.addItem( { 'pid': pid, 'title': title } );
				$item.remove();
			} )
			.on( 'click', '.selected-list .remove-item', function( e ) {
				e.preventDefault();
				var $current = $( this ).parent(), value = [], $items = $( this ).parents( '.selected-list' ).children().not( $current );
				$items.each( function() {
					value.push( $( this ).data( 'post-id' ) );
				} );
				$input.val( value.join( ',' ) ).trigger( 'change' );
				$current.remove();
			} )
			.on( 'click', '.clear-search-results', function( e ) {
				e.preventDefault();
				self.container.find( 'input.search-posts' ).val( '' );
				self.searchList.html( '' );
				$( this ).addClass( 'hide' );
			} )
			.on( 'keyup', 'input.search-posts', function( e ) {
				e.preventDefault();
				var searchKey = $( this ).val(), data = { 'action': loftloaderProCustomize.ajax.action }, postType = $( this ).data( 'post-type' );
				if ( '' == searchKey ) {
					self.searchList.html( '' );
					return false;
				}
				if ( postType ) {
					data.post_type = postType;
				}
				if ( 13 != e.keyCode ) {
					if ( timer ) {
						clearTimeout( timer );
						timer = false;
					}
					timer = setTimeout( function() {
						data.title_sesrch = searchKey;
						$.post( loftloaderProCustomize.ajax.url, data )
						.done( function( response ) {
							if ( response && response.success && response.data ) {
								self.showSearchItems( response.data );
							} else {
								self.searchList.html( '' ).append( $( '<li>', { 'class': 'nothing-found', 'text': 'Nothing found' } ) );
							}
						} )
						.fail( function() {
							self.searchList.html( '' ).append( $( '<li>', { 'class': 'nothing-found', 'text': 'Nothing found' } ) );
						} );
					}, 450 );
				}
			} );
		}
	} );

	// Margins
	api.controlConstructor.loftloader_margins = api.Control.extend( {
		ready: function() { 
			this.addEvents();
		},
		addEvents: function () {
			var $list = this.container.find( '.loftloader-margin input[type="number"]' ),
				$input = this.container.find( 'input[data-customize-setting-link]' );
			$list.on( 'change', function( e ) {
				e.preventDefault();

				$input.val( $list.eq( 0 ).val() + ',' + $list.eq( 1 ).val() + ',' + $list.eq( 2 ).val() + ',' + $list.eq( 3 ).val() ).trigger( 'change' );
			} );
		}
	} );

	api.bind( 'loftloader.message.position', function() {
		var progress_type = $( 'input[data-customize-setting-link=loftloader_progress]:checked' ).val(),
			percentage_position = $( 'input[data-customize-setting-link=loftloader_percentageposition]:checked' ).val(),
			bar_position = $( 'input[data-customize-setting-link=loftloader_barposition]:checked' ).val(),
			$message_position = $( 'input[data-customize-setting-link=loftloader_pro_message_position]' ),
			$middle = $message_position.filter( '[value=middle]' );
		if ( ( ( 'bar' === progress_type ) && ( 'middle' === bar_position ) )
			|| ( ( 'number' === progress_type ) && ( 'below' === percentage_position ) ) ) {
			$middle.parent().css( 'display', '' );
		} else {
			if ( $message_position.filter( '[value=middle]:checked' ).length ) {
				$message_position.filter( '[value=bottom]' ).prop( 'checked', true ).trigger( 'change' );
				$middle.prop( 'checked', false );
			}
			$middle.parent().css( 'display', 'none' );
		}
	} );

	/**
	* Get customize setting value by id
	* @param string setting id
	* @return string setting value
	*/
	function getSettingValue( id ) {
		if ( id in api.settings.settings ) {
			var settings = api.get(), setting = settings[id];
			return ( setting === true ) ? 'on' : setting;
		}
	}
	/**
	* Get the customize control's first setting name
	* @param object customize control
	* @return mix customize setting id string if exists, otherwise boolean false
	*/
	function getControlSettingId( control ) {
		var control_settings = control.settings, keys = Object.keys( control_settings ),
			first_key = ( 'default' in control_settings )  ? 'default' : ( keys.length ? keys[0] : false );
		return first_key ? control_settings[ first_key ] : false;
	}
	/**
	* Generate the dependency object for wp.customize.setting.controls
	*/
	function generateDependency() {
		var settings = api.settings.settings, dependency = {}, controls = api.settings.controls;
		$.each( controls, function( id, control ) {
			var setting = getControlSettingId( control );
			if ( setting && settings[setting] && settings[setting].dependency ) {
				$.each( settings[setting].dependency, function( pid, dep ) {
					var element = { 'control': ( api.control( id ) || control ), 'dependency': settings[setting].dependency };
					if ( pid in dependency ) {
						dependency[pid].push( element );
					} else {
						dependency[pid] = [element];
						api( pid ).bind( function( to ){
							api.trigger( 'loftloader.setting.change', pid );
						} );
					}
				} );
			}
		} );
		api.LoftLoaderDependency = dependency;
	}
	/**
	* To deal with the event of setting changed
	*	This will decide to display the controls related or not
	*/
	api.bind( 'loftloader.setting.change', function( id ) {
		if ( id in api.LoftLoaderDependency ) { // If current setting id is in the dependency list
			$.each( api.LoftLoaderDependency[ id ], function( index, item ) {
				var $control = item.control.container, pass = true;
				$.each( item.dependency, function( pid, attr ) { // Check if all dependency are passed
					var operator = attr.operator || 'in', value = getSettingValue( pid );

					if ( ( ( 'in' == operator ) && ( -1 === attr.value.indexOf( value ) ) )
						|| ( ( 'not in' == operator ) && ( -1 !== attr.value.indexOf( value ) ) ) ) {
						pass = false;
						return false;
					}
				} );
				// Show control if passed
				pass ? $control.show() : $control.hide();
			} );
		}
	} );

	api( 'loftloader_pro_load_time', function ( setting ) {
	    setting.validate = function ( value ) {
	        var code = '', notification, newValue = parseInt( value * 10, 10 ),
				maxSetting = api( 'loftloader_pro_max_load_time' ), maxLoadTime = maxSetting();
			setting.notifications.remove( 'nan' );
			setting.notifications.remove( 'nain' );
			setting.notifications.remove( 'too-large' );

			if ( isNaN( newValue ) || ( '' === value ) || ( typeof value === 'undefined' ) ) {
				code = 'nan';
				notification = new wp.customize.Notification( code, { message: loftloaderProCustomize.i18nText.nan } );
	            setting.notifications.add( code, notification );
			}
			else if ( newValue < 0 ) {
				code = 'nain';
				notification = new wp.customize.Notification( code, { message: loftloaderProCustomize.i18nText.nain } );
				setting.notifications.add( code, notification );
			} else if ( ( newValue > 0 ) && ( maxLoadTime > 0 ) ) {
				if ( ( newValue / 10 ) > maxLoadTime ) {
					code = 'too-large'
					notification = new wp.customize.Notification( code, { message: loftloaderProCustomize.i18nText.minTooLarge.replace( '%d', maxLoadTime ) } );
					setting.notifications.add( code, notification );
				}
			}
			if ( maxSetting ) {
				var notifications = maxSetting.notifications;
				notifications.remove( 'too-small' );
				if ( 'too-large' == code ) {
					code = 'too-small'
					notification = new wp.customize.Notification( code, { message: loftloaderProCustomize.i18nText.maxTooSmall.replace( '%d', value ) } );
					notifications.add( code, notification );
				}
			}
	        return value;
	    };
	} );
	api( 'loftloader_pro_max_load_time', function ( setting ) {
	    setting.validate = function ( value ) {
	        var code = '', notification, newValue = parseInt( value * 10, 10 ),
				minSetting = api( 'loftloader_pro_load_time' ), minLoadTime = minSetting();
			setting.notifications.remove( 'nan' );
			setting.notifications.remove( 'nain' );
			setting.notifications.remove( 'too-small' );

			if ( isNaN( newValue ) || ( '' === value ) || ( typeof value === 'undefined' ) ) {
				code = 'nan';
				notification = new wp.customize.Notification( code, { message: loftloaderProCustomize.i18nText.nan } );
	            setting.notifications.add( code, notification );
			}
			else if ( newValue < 0 ) {
				code = 'nain';
				notification = new wp.customize.Notification( code, { message: loftloaderProCustomize.i18nText.nain } );
				setting.notifications.add( code, notification );
			} else if ( ( newValue > 0 ) && ( minLoadTime > 0 ) ) {
				if ( ( newValue / 10 ) < minLoadTime ) {
					code = 'too-small'
					notification = new wp.customize.Notification( code, { message: loftloaderProCustomize.i18nText.maxTooSmall.replace( '%d', minLoadTime ) } );
					setting.notifications.add( code, notification );
				}
			}
			if ( minSetting ) {
				var notifications = minSetting.notifications;
				notifications.remove( 'too-large' );
				if ( 'too-small' == code ) {
					code = 'too-large'
					notification = new wp.customize.Notification( code, { message: loftloaderProCustomize.i18nText.minTooLarge.replace( '%d', value ) } );
					notifications.add( code, notification );
				}
			}
	        return value;
	    };
	} );

	// Register event handler for hide controls/description
	api.bind( 'ready', function( e ) {
		var current_url = document.createElement( 'a' ), current_search;
		current_url.href = api.previewer.previewUrl();
		current_search = api.utils.parseQueryString( current_url.search.substr( 1 ) );
		generateDependency();
		api.previewer.unbind( 'url' ).bind( 'url', function( url ) {
			var previewer = this, onUrlChange, urlChanged = false, urlParser;
			urlParser = document.createElement( 'a' );
			urlParser.href = url;
			urlParser.search = $.param( { 'plugin': 'loftloader' } );
			url = urlParser.href;
			previewer.scroll = 0;
			onUrlChange = function() {
				urlChanged = true;
			};
			previewer.previewUrl.bind( onUrlChange );
			previewer.previewUrl.set( url );
			previewer.previewUrl.unbind( onUrlChange );
			if ( ! urlChanged ) {
				previewer.refresh();
			}
		} );
		if( ! current_search['plugin'] ) {
			current_search['plugin'] = 'loftloader';
			current_url.search = $.param( current_search );
			api.previewer.previewUrl.set( current_url.href );
		}

		// Change the site title in string "You are customizing ..."
		if ( loftloaderProCustomize && loftloaderProCustomize.i18nText && loftloaderProCustomize.i18nText.pluginName ) {
			$( '.site-title' ).text( loftloaderProCustomize.i18nText.pluginName );
		}
		$( '#loftloader-pro-hide-site-title' ).remove();

		api.trigger( 'loftloader.message.position' );

		var llp_radios = [ 'loftloader_bgfilltype', 'loftloader_progress', 'loftloader_animation' ];
		function llp_toggle_controls( container, id ) {
			if ( llp_radios.indexOf( id ) !== -1 ) {
				var val = container.find( 'input[data-customize-setting-link]:checked' ).val(),
					wrap = container.parents('ul').first();
				( val === 'none' ) ? wrap.addClass( 'loftloader-control-disabled' ) : wrap.removeClass( 'loftloader-control-disabled' )
			}
		}
		$.each( llp_radios, function( i, v ) {
			var $radio = $( 'input[data-customize-setting-link=' + v + ']:checked' );
			if ( $radio.length ) {
				llp_toggle_controls( $radio.parents('li.customize-control' ).first(), v );
			}
		} );

		$( 'body' ).on( 'change', 'input[name=loftloader_pro_barwidth_unit]', function( e ) {
			api( 'loftloader_pro_progress_width_unit' )( $( this ).is( ':checked' ) );
		} )
		.on( 'change', 'input.loftlader-pro-checkbox', function( e ) {
			var $element = $( this ).siblings( 'input' );
			if ( $element.length && $element.attr( 'data-customize-setting-link' ) ) {
				api( $element.attr( 'data-customize-setting-link' ) )( $( this ).is( ':checked' ) );
			}
		} )
		.on( 'change', 'input[type=number]', function( e ) {
			var $input = $( this ), changed = false,
				min = $input.attr( 'min' ) ? parseInt( $input.attr( 'min' ), 10 ) : 1,
				val = $input.val() ? parseInt( $input.val(), 10 ) : 0,
				max = $input.attr( 'max' ) ? parseInt( $input.attr( 'max' ), 10 ) : false;
			if ( min && ( val < min ) ) {
				val = min;
				changed = true;
			}
			if( max && ( val > max ) ) {
				val = max;
				changed = true;
			}
			if ( changed ) {
				$input.val( val ) .trigger( 'change' );
			}
		} )
		.on( 'click', '.customize-more-toggle', function( e ) {
			e.preventDefault();
			var self = $( this ),
				description = $( this ).siblings( '.customize-control-description' );

			if ( description.length ) {
				self.hasClass( 'expanded' ) ? description.slideUp( 'slow' ) : description.slideDown( 'slow', function(){ $(this).css( 'display', 'block' ); } );
				self.toggleClass( 'expanded' );
			}
		} )
		.on( 'change', 'input[type=radio]', function( e ) {
			var id = $( this ).attr( 'data-customize-setting-link' );
			if ( llp_radios.indexOf( id ) !== -1 ) {
				llp_toggle_controls( $( this ).parents( 'li.customize-control' ).first(), id );
			}
			switch ( id ) {
				case 'loftloader_progress':
				case 'loftloader_barposition':
				case 'loftloader_percentageposition':
					api.trigger( 'loftloader.message.position' );
					break;
			}
		} )
		.on('click', '.loftloader-pro-any-page-generate', function( e ) {
			e.preventDefault();
			var shortcode = api.loftloader_pro_generate_parameters();
			$( this )
				.siblings( '.loftloader-pro-any-page-shortcode' )
					.attr( 'rows', 40 )
					.val( '[loftloader ' + shortcode + ']' )
					.select( );
		} );
	} );
} ) ( wp.customize, jQuery );
