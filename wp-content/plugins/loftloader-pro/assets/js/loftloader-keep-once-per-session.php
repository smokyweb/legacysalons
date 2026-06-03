<script type="text/javascript" data-no-optimize="1">
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

        var currentSessionData = getSessionData(), sessionCountName = 'loftloaderProSessionCount', sessionCount = false;

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
    } ) ();
</script>
