/**
* Copyright (c) 2011-2018 Alexander Dickson @alexdickson
* Licensed under the MIT licenses.
* http://alexanderdickson.com
*
* Modified by Cain
* Modification Date 20190513
*/
;( function ( factory ) {
	"use strict";
	if ( typeof define === 'function' && define.amd ) {
		// AMD. Register as an anonymous module.
		define( ['jquery'], factory );
	} else if ( typeof exports === 'object' ) {
		// CommonJS / nodejs module
		module.exports = factory( require( 'jquery' ) );
	} else {
		// Browser globals
		factory( jQuery );
	}
} ( function ( $ ) {
	// Namespace all events.
	var eventNamespace = 'waitForMedia', detectElement = 'all', waitForVideo = 20000, hasYoutube = false, hasVimeo = false,
		detectAutoplayVideo = loftloaderProWaitForMediaSettings && loftloaderProWaitForMediaSettings.detectAutoplayVideo;
	if ( loftloaderProWaitForMediaSettings && ( typeof loftloaderProWaitForMediaSettings.detectElement !== 'undefined' ) ) {
		detectElement = loftloaderProWaitForMediaSettings.detectElement;
	}

	// CSS properties which contain references to images.
	$.loftloaderProWaitForMedia = {
		hasImageProperties: [
			'backgroundImage',
			'listStyleImage',
			'borderImage',
			'borderCornerImage',
			'cursor'
		],
		hasImageAttributes: [ 'srcset' ],
		hasIframeVideos: [
			/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?'"]*).*/,
			/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/
		]
	};

	// Custom selector to find all `img` elements with a valid `src` attribute.
	$.expr[':']['has-src'] = function ( obj ) {
		// Ensure we are dealing with an `img` element with a valid
		// `src` attribute.
		return $( obj ).is( 'img[src][src!=""]' );
	};

	// Custom selector to find images which are not already cached by the
	// browser.
	$.expr[':'].uncached = function ( obj ) {
		// Ensure we are dealing with an `img` element with a valid
		// `src` attribute.
		if ( ! $( obj ).is( ':has-src' ) ) {
			return false;
		}

		return ! obj.complete;
	};

	$.fn.loftloaderProWaitForMedia = function () {
		var $self = $( this ).first(), hasAutoplayVideo = false;
		var detectAll = ( 'all' === detectElement );
		var done = false, interval = false, deferred = $.Deferred(),
			step = 5, previousPercentage = 5, currentPercentage = 5;

		var finishedCallback, eachCallback,  waitForAll;
		// Handle options object (if passed).
		if ( $.isPlainObject( arguments[0] ) ) {
			waitForAll = arguments[0].waitForAll;
			eachCallback = arguments[0].each;
			finishedCallback = arguments[0].finished;
		}
		// Handle missing callbacks.
		finishedCallback = finishedCallback || $.noop;
		eachCallback = eachCallback || $.noop;
		// Convert waitForAll to Boolean
		waitForAll = ! ! waitForAll;

		eachCallback.call( $self, currentPercentage );
		interval = setInterval( function() {
			if ( 95 > currentPercentage ) {
				if ( detectAll ) {
					currentPercentage += step;
					previousPercentage = currentPercentage;
					eachCallback.call( $self, currentPercentage );
				} else if ( ( parseInt( currentPercentage, 10 ) - parseInt( previousPercentage, 10 ) ) >= step ) {
					previousPercentage = currentPercentage;
					eachCallback.call( $self, currentPercentage );
				}
			} else {
				clearInterval( interval );
				interval = false;
			}
		}, 700 );

		function finished() {
			if ( ! done ) {
				done = true;
				finishedCallback.call( $self, previousPercentage );
			}
			if ( interval ) {
				clearInterval( interval );
				interval = false;
			}
		}

		function isIfrmaeAutoplay( video ) {
			if ( video && $( video ).length ) {
				var $video = $( video ), allows = $video.attr( 'allow' ), url = $video.attr( 'src' );
				return url && ( -1 !== url.indexOf( 'autoplay=1' ) ) && ( ! allows || ( -1 !== allows.indexOf( 'autoplay;' ) ) );
			}
			return false;
		}
		function clearTimer( media ) {
			if ( media.timer ) {
				clearTimeout( media.timer );
				media.timer = false;
			}
		}
		function videoTimeup( media ) {
			if ( ! media.done ) {
				mediaLoaded( media.src );
				media.done = true;
			}
		}
		function videoLoaded( media ) {
			if ( ! media.done ) {
				mediaLoaded( media.src );
				clearTimer( media );
				media.done = true;
			}
		}

		function setIframeID( video ) {
			if ( ! video.id ) {
				var randID = 'lofltoader-pro-iframe-' + Math.round( Math.random() * 100000 );
				while ( $( '#' + randID ).length ) {
					randID = 'lofltoader-pro-iframe-' + Math.round( Math.random() * 100000 );
				}
				$( video ).attr( 'id', randID );
			}
		}
		function loadVideoAPI() {
			var scripts = $( '<div>' );
			if ( hasYoutube && ! ( 'YT' in window ) ) {
				scripts.append( $( '<script>', { 'src': '//www.youtube.com/iframe_api', 'type': 'text/javascript' } ) );
			}
			if ( hasVimeo && ! ( 'Vimeo' in window ) ) {
				scripts.append( $( '<script>', { 'src': '//player.vimeo.com/api/player.js', 'type': 'text/javascript' } ) );
			}
			if ( scripts.children().length ) {
				$( 'head' ).append( scripts.children() );
			}
		}

		$( window ).on( 'load', function( e ) {
			if ( ! hasAutoplayVideo ) {
 				finished();
			}
		} );
		if ( ! detectAll ) {
			var allMediaLength = 0, allMediaLoaded = 0, medias = [], allMedia = [], existSRC = [];
			var detectVideo = ( -1 !== [ 'video', 'media' ].indexOf( detectElement ) );
			var detectImage = ( -1 !== [ 'image', 'media' ].indexOf( detectElement ) );
			// CSS properties which may contain an image.
			var hasImgProperties = $.loftloaderProWaitForMedia.hasImageProperties || [];
			// Element attributes which may contain an image.
			var hasImageAttributes = $.loftloaderProWaitForMedia.hasImageAttributes || [];
			// To match `url()` references.
			// Spec: http://www.w3.org/TR/CSS2/syndata.html#value-def-uri
			var matchUrl = /url\(\s*(['"]?)(.*?)\1\s*\)/g;

			if ( waitForAll ) {
				// Get all elements (including the original), as any one of
				// them could have a background image.
				$self = $self.find( '*' ).addBack();
				$self.not( $( '#loftloader-wrapper' ).find( '*' ).addBack() ).each( function () {
					var element = $( this );

					if ( detectImage ) {
						if ( element.is( 'img:has-src' ) && ! element.is( '[srcset]' ) ) {
							if ( -1 === existSRC.indexOf( element.attr( 'src' ) ) ) {
								allMedia.push( {
									src: element.attr( 'src' ),
									element: element[0]
								} );
								existSRC.push( element.attr( 'src' ) );
							}
						}

						$.each( hasImgProperties, function( i, property ) {
							var propertyValue = element.css( property );
							var match;

							// If it doesn't contain this property, skip.
							if ( ! propertyValue ) {
								return true;
							}

							// Get all url() of this element.
							while ( match = matchUrl.exec( propertyValue ) ) {
								if ( -1 === existSRC.indexOf( match[2] ) ) {
									allMedia.push( {
										src: match[2],
										element: element[0]
									} );
									existSRC.push( match[2] );
								}
							}
						} );

						$.each( hasImageAttributes, function ( i, attribute ) {
							var attributeValue = element.attr( attribute );
							var attributeValues;

							// If it doesn't contain this property, skip.
							if ( ! attributeValue ) {
								return true;
							}
							if ( -1 === existSRC.indexOf( element.attr( 'src' ) ) ) {
								allMedia.push( {
									src: element.attr( 'src' ),
									srcset: element.attr( 'srcset' ),
									element: element[0]
								} );
								existSRC.push( element.attr( 'src' ) )
							}
						} );
					}

					if ( detectVideo ) {
						if ( element.is( 'iframe' ) ) {
							var iframeSrc = element.attr( 'src' );
							var hasVideos = $.loftloaderProWaitForMedia.hasIframeVideos || [];
							var isAutoplay = false;
							if ( -1 === existSRC.indexOf( iframeSrc ) ) {
								$.each( hasVideos, function ( i, regex ) {
									if ( regex.exec( iframeSrc ) ) {
										medias.push( iframeSrc );
										isAutoplay = isIfrmaeAutoplay( element[0] );
										allMedia.push( {
											src: iframeSrc,
											isMedia: true,
											type: 'iframe',
											element: element[0],
											autoplay: isAutoplay,
											from: ( 1 === i ) ? 'vimeo' : 'youtube'
										} );
										existSRC.push( iframeSrc );
										if ( detectAutoplayVideo && isAutoplay ) {
											hasAutoplayVideo = true;
											if ( 0 === i ) {
												setIframeID( element[0] );
												hasYoutube = true;
											} else {
												hasVimeo = true;
											}
										}
										return false;
									}
								} );
							}
						} else if ( element.is( 'video' ) && ( element.attr( 'src' ) || element.find( '[src!=""]' ).length ) ) {
							var videoSrc = element.attr( 'src' ) ? element.attr( 'src' ) : element.find( '[src!=""]' ).attr( 'src' );
							if ( -1 === existSRC.indexOf( videoSrc ) ) {
								medias.push( videoSrc );
								allMedia.push( {
									src: videoSrc,
									isMedia: true,
									type: 'video',
									element: element[0]
								} );
								existSRC.push( videoSrc );
							}
						}
					}
				} );
			}

			if ( allMedia && allMedia.length ) {
				allMedia = allMedia.filter( function( media ) {
					return media['src'] && (  typeof media['src'] !== 'undefined' );
				} );
			}

			allMediaLength = allMedia.length;
			// If no images found, don't bother.
			if ( 0 === allMediaLength ) {
				finished();
			}
			loadVideoAPI();

			function mediaLoaded( src ) {
				allMediaLoaded ++;
				currentPercentage = Math.floor( allMediaLoaded / allMediaLength * 100 );
				if ( allMediaLoaded === allMediaLength ) {
					finished();
				}
			}

			// Add to detected images to list
			function addToList( src, el ) {
				if ( -1 === existSRC.indexOf( src ) ) {
					var image = new Image();
					$( image ).one( 'load  error', function( e ) { mediaLoaded( src ); } );
					image.src = src;

					allMediaLength ++;
					allMedia.push( { src: src, element: el } );
					existSRC.push( src );
				}
			}

			$.each( allMedia, function ( i, media ) {
				var events = 'load  error';
				if ( media.isMedia ) {
					if ( 'video' == media.type ) {
						var video = document.createElement( 'video' );
						$( video ).one( 'loadeddata error', function( e ) {
							mediaLoaded( media.src );
						} );
						video.src = media.src;
					} else {
						$( media.element ).one( 'load', function( e ) {
							if ( ( 'youtube' == media.from ) && media.autoplay && detectAutoplayVideo && ( 'YT' in window ) && YT.Player ) {
								new YT.Player( $( media.element ).attr( 'id' ), {
									events: {
										onStateChange: function( e ) { videoLoaded( media ); }
									}
								} );
								media.timer = setTimeout( function() { videoTimeup( media ); }, waitForVideo );
							} else if ( ( 'vimeo' == media.from ) && media.autoplay && detectAutoplayVideo && ( 'Vimeo' in window ) && Vimeo.Player ) {
								new Vimeo.Player( media.element ).play().then( function() {
									videoLoaded( media );
						        }, function() {
									videoLoaded( media );
								} );
								media.timer = setTimeout( function() { videoTimeup( media ); }, waitForVideo );
							} else {
								mediaLoaded( media.src );
							}
						} ).one( 'error', function( e ) {
							mediaLoaded( media.src );
						} );
					}
				} else {
					var image = new Image();

					// Handle the image loading and error with the same callback.
					$( image ).one( events, function( e ) {
						mediaLoaded( media.src );
					} );

					if ( media.srcset ) {
						image.srcset = media.srcset;
					}
					image.src = media.src;
				}
			} );
		}
	};
} ) );
