<script type="text/javascript" data-no-optimize="1">
var loftloaderProProgressInit = <?php echo intval( llp_get_loader_setting( 'loftloader_pro_insite_transition_initial_percentage' ) ); ?>, init = 0, percentageStyles = '', LoftLoaderProGlobalSessionStorage = {
    getItem: function( name ) {
        try {
            return sessionStorage.getItem( name );
        } catch( msg ) {
            return false;
        }
    }
};
function loftloaderProInsertStyle( styleID, styleContent ) {
    var style = document.createElement( 'style' );
    style.id = styleID;
    style.innerText = styleContent;
    document.head.appendChild( style );
}
if ( LoftLoaderProGlobalSessionStorage.getItem( 'loftloader-pro-smooth-transition' ) && ( 'on' === LoftLoaderProGlobalSessionStorage.getItem( 'loftloader-pro-smooth-transition' ) ) ) {
    var onceStyles = '', initPercentage = loftloaderProProgressInit;
    init = loftloaderProProgressInit / 100; <?php
    $loader_class = apply_filters( 'loftloader_pro_loader_classes', array() );
    $is_once = in_array( 'loftloader-once', $loader_class );
    $is_once_type = array_intersect( $loader_class, array( 'loftloader-imgloading', 'loftloader-rainbow', 'loftloader-circlefilling', 'loftloader-waterfilling', 'loftloader-petals' ) );
    if ( $is_once && $is_once_type ) {
        if ( in_array( 'loftloader-imgloading', $loader_class ) ) {
            if ( in_array( 'imgloading-horizontal', $loader_class ) ) { ?>
                loftloaderProInsertStyle( 'loftloader_pro_once_imgloading', '#loftloader-wrapper.loftloader-imgloading.imgloading-horizontal #loader .imgloading-container { width: ' + initPercentage + '%; }' ); <?php
            } else { ?>
                loftloaderProInsertStyle( 'loftloader_pro_once_imgloading', '#loftloader-wrapper.loftloader-imgloading.imgloading-vertical #loader .imgloading-container { height: ' + initPercentage + '%; }' ); <?php
            }
        } else if ( in_array( 'loftloader-rainbow', $loader_class ) ) { ?>
            var deg = initPercentage * 1.8 - 180;
            loftloaderProInsertStyle(
                'loftloader_pro_once_rainbow',
                '#loftloader-wrapper.loftloader-rainbow #loader span { -webkit-transform: rotate(' + deg + 'deg); transform: rotate(' + deg + 'deg); }'
            ); <?php
        } else if ( in_array( 'loftloader-circlefilling', $loader_class ) ) { ?>
            var scaleY = initPercentage / 100;
            loftloaderProInsertStyle(
                'loftloader_pro_once_circlefilling',
                '#loftloader-wrapper.loftloader-circlefilling #loader span { -webkit-transform: scaleY(' + scaleY + '); transform: scaleY(' + scaleY + '); }'
            ); <?php
        } else if ( in_array( 'loftloader-waterfilling', $loader_class ) ) { ?>
            var scaleY = initPercentage / 100, transY = initPercentage - 100;
            loftloaderProInsertStyle(
                'loftloader_pro_once_waterfilling',
                '#loftloader-wrapper.loftloader-waterfilling #loader:before { transform: scaleY(' + scaleY + '); } #loftloader-wrapper.loftloader-waterfilling #loader span {-webkit-transform: translateY(' + transY + '%); transform: translateY(' + transY + '%); }'
            ); <?php
        } else if ( in_array( 'loftloader-petals', $loader_class ) ) { ?>
            var petals = {
                petal0: '{box-shadow: 0 -15px 0 -15px transparent, 10.5px -10.5px 0 -15px transparent, 15px 0 0 -15px transparent, 10.5px 10.5px 0 -15px transparent, 0 15px 0 -15px transparent, -10.5px 10.5px 0 -15px transparent, -15px 0 0 -15px transparent, -10.5px -10.5px 0 -15px transparent;}',
                petal1: '{box-shadow: 0 -25px 0 -15px currentColor, 10.5px -10.5px 0 -15px transparent, 15px 0 0 -15px transparent, 10.5px 10.5px 0 -15px transparent, 0 15px 0 -15px transparent, -10.5px 10.5px 0 -15px transparent, -15px 0 0 -15px transparent, -10.5px -10.5px 0 -15px transparent;}',
                petal2: '{box-shadow: 0 -25px 0 -15px currentColor, 17.5px -17.5px 0 -15px currentColor, 15px 0 0 -15px transparent, 10.5px 10.5px 0 -15px transparent, 0 15px 0 -15px transparent, -10.5px 10.5px 0 -15px transparent, -15px 0 0 -15px transparent, -10.5px -10.5px 0 -15px transparent;}',
                petal3: '{box-shadow: 0 -25px 0 -15px currentColor, 17.5px -17.5px 0 -15px currentColor, 25px 0 0 -15px currentColor, 10.5px 10.5px 0 -15px transparent, 0 15px 0 -15px transparent, -10.5px 10.5px 0 -15px transparent, -15px 0 0 -15px transparent, -10.5px -10.5px 0 -15px transparent;}',
                petal4: '{box-shadow: 0 -25px 0 -15px currentColor, 17.5px -17.5px 0 -15px currentColor, 25px 0 0 -15px currentColor, 17.5px 17.5px 0 -15px currentColor, 0 15px 0 -15px transparent, -10.5px 10.5px 0 -15px transparent, -15px 0 0 -15px transparent, -10.5px -10.5px 0 -15px transparent;}',
                petal5: '{box-shadow: 0 -25px 0 -15px currentColor, 17.5px -17.5px 0 -15px currentColor, 25px 0 0 -15px currentColor, 17.5px 17.5px 0 -15px currentColor, 0 25px 0 -15px currentColor, -10.5px 10.5px 0 -15px transparent, -15px 0 0 -15px transparent, -10.5px -10.5px 0 -15px transparent;}',
                petal6: '{box-shadow: 0 -25px 0 -15px currentColor, 17.5px -17.5px 0 -15px currentColor, 25px 0 0 -15px currentColor, 17.5px 17.5px 0 -15px currentColor, 0 25px 0 -15px currentColor, -17.5px 17.5px 0 -15px currentColor, -15px 0 0 -15px transparent, -10.5px -10.5px 0 -15px transparent;}',
                petal7: '{box-shadow: 0 -25px 0 -15px currentColor, 17.5px -17.5px 0 -15px currentColor, 25px 0 0 -15px currentColor, 17.5px 17.5px 0 -15px currentColor, 0 25px 0 -15px currentColor, -17.5px 17.5px 0 -15px currentColor, -25px 0 0 -15px currentColor, -10.5px -10.5px 0 -15px transparent;}',
                petal8: '{box-shadow: 0 -25px 0 -15px currentColor, 17.5px -17.5px 0 -15px currentColor, 25px 0 0 -15px currentColor, 17.5px 17.5px 0 -15px currentColor, 0 25px 0 -15px currentColor, -17.5px 17.5px 0 -15px currentColor, -25px 0 0 -15px currentColor, -17.5px -17.5px 0 -15px currentColor;}'
            }, style = '', nums = [88, 75, 63, 50, 38, 25, 13], steps = { 88: 'petal7', 75: 'petal6', 63: 'petal5', 50: 'petal4', 38: 'petal3', 25: 'petal2', 13: 'petal1' };
            for ( let value of nums ) {
                if ( initPercentage >= value ) {
                    style = petals[ steps[ value ] ];
                    break;
                }
            }
            style = ( initPercentage === 0 ) ? petals['petal0'] : ( ( initPercentage > 98 ) ? petals['petal8'] : style );
            loftloaderProInsertStyle( 'loftloader_pro_once_petals', '#loftloader-wrapper.loftloader-petals #loader span' + style ); <?php
        }
    } ?>
}
percentageStyles = '#loftloader-wrapper span.percentage:after, #loftloader-wrapper .load-count:after { content: "' + Math.ceil( init * 100 ) + '%"; }';
percentageStyles += ' #loftloader-wrapper .load-count { width: ' + ( init * 100 ) + '%; }';
loftloaderProInsertStyle( 'loftloader-pro-progress-bar-style', '#loftloader-wrapper span.bar span.load { transform: scaleX(' + init + '); }' );
loftloaderProInsertStyle( 'loftloader-pro-progress-percentage-style', percentageStyles );
</script>
