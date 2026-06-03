<script type="text/javascript" data-no-optimize="1"><?php
    $global_enabled = apply_filters( 'loftloader_pro_enabled_session', false );
    $page_enabled = apply_filters( 'loftloader_pro_page_show_once', false );
    $page_id = $page_enabled ? get_queried_object_id() : -1;
    $page_uid = hash( 'md5', $page_id );
    $scope = 'none';
    if ( $page_enabled ) {
        $scope = 'page';
    } else if ( $global_enabled ) {
        $scope = ( 'homepage-once' == llp_get_loader_setting( 'loftloader_pro_show_range', true ) ) ? 'front' : 'site';
    } ?>
    ( function() {
        /**
        * If a given string is valid JSON string
        */
        function isJSONStr( str ) {
            if ( typeof str !== "string" ) {
                return false;
            }
            try {
                JSON.parse( str );
                return true;
            } catch ( error ) {
                return false;
            }
        }
        /**
        * Get current session data from localStorage
        */
        function getSessionData() {
            try {
                var sessionName = 'loftloaderProSessionData', data = sessionStorage.getItem( sessionName );
                if ( ( ! data ) || ( ! isJSONStr( data ) ) ) {
                    data = localStorage.getItem( sessionName );
                }
                return isJSONStr( data ) ? JSON.parse( data ) : {};
            } catch ( msg ) {
                return {};
            }

        }
        /**
        * Update session data to localStorage
        */
        function setSessionData( data ) {
            try {
                var sessionName = 'loftloaderProSessionData', dataStr = '';
                if ( typeof data === 'object' ) {
                    if ( typeof data[ 'loftloaderProSessionCount' ] !== 'undefined' ) {
                        dataStr = data[ 'loftloaderProSessionCount' ] ? JSON.stringify( data ) : '';
                        delete data[ 'loftloaderProSessionCount' ];
                    }
                } else {
                    data = {};
                }
                sessionStorage.setItem( sessionName, JSON.stringify( data ) );
                localStorage.setItem( sessionName, dataStr );
            } catch ( msg ) {}
        }
        /**
        * Get current opened page count
        */
        function getOpenedPageCount() {
            try {
                var data = localStorage.getItem( 'loftloaderProSessionData' ), sessionCountName = 'loftloaderProSessionCount';
                if ( isJSONStr( data ) ) {
                    data = JSON.parse( data );
                   return typeof data[ sessionCountName ] === 'undefined' ? false : data[ sessionCountName ];
               } else {
                return false;
               }
            } catch ( msg ) {
                return false;
            }
        }

        var currentSessionData = getSessionData(), sessionCountName = 'loftloaderProSessionCount', sessionCount = false,
            sessionID = false, htmlClass = document.documentElement.classList, loftloaderCache = {
                'isOncePerSession': "<?php echo ( $global_enabled || $page_enabled ? 'on' : 'off' ); ?>",
                'scope': "<?php echo esc_js( $scope ); ?>",
                'isFront': "<?php echo ( is_front_page() ? 'on' : 'off' ); ?>",
                'uid': "<?php echo $page_uid; ?>"
            };

        if ( 'on' === loftloaderCache.isOncePerSession ) {
            switch ( loftloaderCache.scope ) {
                case 'site':
                    sessionID = 'loftloaderSiteOncePerSession';
                    break;
                case 'front':
                    if ( 'on' === loftloaderCache.isFront ) {
                        sessionID = 'loftloaderFrontOncePerSession';
                    }
                    break;
                case 'page':
                    sessionID = loftloaderCache.uid;
                    break;
            }
            if ( sessionID ) {
                if ( currentSessionData[ sessionID ] && ( 'done' == currentSessionData[ sessionID ] ) ) {
                    var styles = [ 'loftloader-page-smooth-transition-bg', 'loftloader-pro-disable-scrolling', 'loftloader-pro-always-show-scrollbar' ];
                    styles.forEach( function ( s ) {
                        if ( document.getElementById( s ) ) {
                            document.getElementById( s ).remove();
                        }
                    } );
                    htmlClass.add( 'loftloader-pro-hide' );

                    document.documentElement.dataset['loftloaderPro'] = 'hide';
                    var style = document.createElement( 'style' );
                    style.id = 'loftloader-pro-disable-loader-by-session';
                    style.innerText = 'html[data-loftloader-pro=hide] #loftloader-wrapper { display: none !important; }';
                    document.head.appendChild( style );
                } else {
                    currentSessionData[ sessionID ] = 'done';
                    htmlClass.remove( 'loftloader-pro-hide' );
                }
                sessionCount = getOpenedPageCount() || 0;
                sessionCount = isNaN( sessionCount ) || ( 0 > sessionCount ) ? 0 : sessionCount;
                currentSessionData[ sessionCountName ] = sessionCount + 1;
                setSessionData( currentSessionData );

                window.onpagehide = function( e ) {
                    currentSessionData = getSessionData();
                    sessionCount = getOpenedPageCount() || 0;
                    sessionCount = isNaN( sessionCount ) || ( 0 > sessionCount ) ? 1 : sessionCount;
                    currentSessionData[ sessionCountName ] = ( 2 > sessionCount ) ? 0 : sessionCount - 1;
                    setSessionData( currentSessionData );
                };
            }
        }
    } ) ();
</script>
