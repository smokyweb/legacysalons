<style type="text/css">
  	:root{
	  	--tsvg_popup_po_ob_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo esc_html( htmlspecialchars( $tsvg_style_options->TotalSoft_GV_1_28 ) ); ?>;
	  	--tsvg_popup_po_se_t_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo filter_var( esc_html( $tsvg_style_options->TotalSoft_GV_1_29 ), FILTER_VALIDATE_INT ); ?>;
	  	--tsvg_popup_po_ci_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo esc_html( htmlspecialchars( $tsvg_style_options->TotalSoft_GV_1_30 ) ); ?>;
	  	--tsvg_popup_po_ci_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo filter_var( esc_html( $tsvg_style_options->TotalSoft_GV_1_31 ), FILTER_VALIDATE_INT ); ?>px;
	  	--tsvg_popup_pso_i_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo filter_var( esc_html( $tsvg_style_options->TotalSoft_GV_1_33 ), FILTER_VALIDATE_INT ); ?>px;
	  	--tsvg_popup_pso_i_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo esc_html( htmlspecialchars( $tsvg_style_options->TotalSoft_GV_1_34 ) ); ?>;
	  	--tsvg_popup_pso_ib_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo esc_html( htmlspecialchars( $tsvg_style_options->TotalSoft_GV_1_35 ) ); ?>;
	  	--tsvg_popup_pso_b_w<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo filter_var( esc_html( $tsvg_style_options->TotalSoft_GV_1_39 ), FILTER_VALIDATE_INT ); ?>px;
	  	--tsvg_popup_pso_br_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo esc_html( htmlspecialchars( $tsvg_style_options->TotalSoft_GV_2_01 ) ); ?>;
	  	--tsvg_popup_pso_b_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo esc_html( htmlspecialchars( $tsvg_style_options->TotalSoft_GV_2_02 ) ); ?>;
	  	--tsvg_popup_pso_sh_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo filter_var( esc_html( $tsvg_style_options->TotalSoft_GV_2_03 ), FILTER_VALIDATE_INT ); ?>px;
	  	--tsvg_popup_pso_sh_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>:<?php echo esc_html( htmlspecialchars( $tsvg_style_options->TotalSoft_GV_2_04 ) ); ?>;
	}
	.tsvg-elastic-lg-actions<?php echo esc_attr( $tsvg_shortcode_id ); ?> {
		width: 100px;
		height: calc(100% - 100px);
		display: flex;
		flex-direction: column;
		flex-wrap: nowrap;
		justify-content: center;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-actions-prev<?php echo esc_attr( $tsvg_shortcode_id ); ?>{
		align-items: flex-end;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>  .tsvg-elastic-lg-actions-next<?php echo esc_attr( $tsvg_shortcode_id ); ?>{
		align-items: flex-start;
	}
	.tsvg-elastic-lg-actions .tsvg-elastic-lg-next:hover,
	.tsvg-elastic-lg-actions .tsvg-elastic-lg-prev:hover {
		color: #FFF;
	}
	.tsvg-elastic-lg-actions .tsvg-elastic-lg-prev:after {
		content: "\e094";
	}
	@-webkit-keyframes tsvg-elastic-lg-right-end {
		0% {
			left: 0;
		}
		50% {
			left: -30px;
		}
		100% {
			left: 0;
		}
	}
	@-moz-keyframes tsvg-elastic-lg-right-end {
		0% {
			left: 0;
		}
		50% {
			left: -30px;
		}
		100% {
			left: 0;
		}
	}
	@-ms-keyframes tsvg-elastic-lg-right-end {
		0% {
			left: 0;
		}
		50% {
			left: -30px;
		}
		100% {
			left: 0;
		}
	}
	@keyframes tsvg-elastic-lg-right-end {
		0% {
			left: 0;
		}
		50% {
			left: -30px;
		}
		100% {
			left: 0;
		}
	}
	@-webkit-keyframes tsvg-elastic-lg-left-end {
		0% {
			left: 0;
		}
		50% {
			left: 30px;
		}
		100% {
			left: 0;
		}
	}
	@-moz-keyframes tsvg-elastic-lg-left-end {
		0% {
			left: 0;
		}
		50% {
			left: 30px;
		}
		100% {
			left: 0;
		}
	}
	@-ms-keyframes tsvg-elastic-lg-left-end {
		0% {
			left: 0;
		}
		50% {
			left: 30px;
		}
		100% {
			left: 0;
		}
	}
	@keyframes tsvg-elastic-lg-left-end {
		0% {
			left: 0;
		}
		50% {
			left: 30px;
		}
		100% {
			left: 0;
		}
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-right-end .tsvg-elastic-lg-object {
		-webkit-animation: tsvg-elastic-lg-right-end 0.3s;
		-o-animation: tsvg-elastic-lg-right-end 0.3s;
		animation: tsvg-elastic-lg-right-end 0.3s;
		position: relative;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-left-end .tsvg-elastic-lg-object {
		-webkit-animation: tsvg-elastic-lg-left-end 0.3s;
		-o-animation: tsvg-elastic-lg-left-end 0.3s;
		animation: tsvg-elastic-lg-left-end 0.3s;
		position: relative;
	}
	.tsvg-elastic-lg-toolbar {
		background-color: rgba(0, 0, 0, 0.45);
		width: 100%;
		height: 100px;
		padding: 0px 30px;
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		align-content: center;
		align-items: center;
		justify-content: space-between;
	}
	#tsvg-elastic-lg-counter<?php echo esc_attr( $tsvg_shortcode_id ); ?> {
		color: var(--tsvg_popup_po_ci_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		font-size: var(--tsvg_popup_po_ci_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		font-family: var(--tsvg_vto_ff_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		width: 130px;
		line-height: 1.2;
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		justify-content: flex-start;
		align-items: center;
	}
	.tsvg-elastic-lg-toolbar-title<?php echo esc_attr( $tsvg_shortcode_id ); ?>{
		font-size: var(--tsvg_vto_fs_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		font-family: var(--tsvg_vto_ff_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		color: var(--tsvg_vto_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		line-height: 1.2;
		width: calc(100% - 130px - calc(2 * var(--tsvg_popup_po_ci_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>)));
		max-width: calc(100% - 130px - calc(2 * var(--tsvg_popup_po_ci_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>)));
		word-break: break-word;
		text-overflow: ellipsis;
		overflow: hidden;
		white-space: nowrap;
		margin: auto !important;
		text-align: center;
		max-height: 100%;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-toolbar-icon {
		color: var(--tsvg_popup_po_ci_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		cursor: pointer;
		font-size: var(--tsvg_popup_po_ci_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		height: 100%;
		text-align: center;
		width: var(--tsvg_popup_po_ci_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		text-decoration: none !important;
		outline: medium none;
		-webkit-transition: color 0.2s linear;
		-moz-transition: color 0.2s linear;
		-o-transition: color 0.2s linear;
		transition: color 0.2s linear;
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		align-items: center;
		justify-content: center;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-toolbar-icon.tsvg-elastic-lg-slideshow {
		margin-left: calc(130px - calc(2 * var(--tsvg_popup_po_ci_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>)));
	}
	.tsvg-elastic-lg-toolbar .tsvg-elastic-lg-close:after {
		content: "\e070";
	}
	.tsvg-elastic-lg-toolbar .tsvg-elastic-lg-download:after {
		content: "\e0f2";
	}
	.tsvg-elastic-lg-toolbar,
	.tsvg-elastic-lg-prev,
	.tsvg-elastic-lg-next {
		opacity: 1;
		-webkit-transition: -webkit-transform 0.35s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.35s cubic-bezier(0, 0, 0.25, 1) 0s, color 0.2s linear;
		-moz-transition: -moz-transform 0.35s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.35s cubic-bezier(0, 0, 0.25, 1) 0s, color 0.2s linear;
		-o-transition: -o-transform 0.35s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.35s cubic-bezier(0, 0, 0.25, 1) 0s, color 0.2s linear;
		transition: transform 0.35s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.35s cubic-bezier(0, 0, 0.25, 1) 0s, color 0.2s linear;
	}
	.tsvg-elastic-lg-hide-items .tsvg-elastic-lg-prev {
		opacity: 0;
		-webkit-transform: translate3d(-10px, 0, 0);
		transform: translate3d(-10px, 0, 0);
	}
	.tsvg-elastic-lg-hide-items .tsvg-elastic-lg-next {
		opacity: 0;
		-webkit-transform: translate3d(10px, 0, 0);
		transform: translate3d(10px, 0, 0);
	}
	.tsvg-elastic-lg-hide-items .tsvg-elastic-lg-toolbar {
		opacity: 0;
		-webkit-transform: translate3d(0, -10px, 0);
		transform: translate3d(0, -10px, 0);
	}
	body:not(.tsvg-elastic-lg-from-hash) .tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-start-zoom .tsvg-elastic-lg-object {
		-webkit-transform: scale3d(0.5, 0.5, 0.5);
		transform: scale3d(0.5, 0.5, 0.5);
		opacity: 0;
		-webkit-transition: -webkit-transform 250ms cubic-bezier(0, 0, 0.25, 1) 0s, opacity 250ms cubic-bezier(0, 0, 0.25, 1) !important;
		-moz-transition: -moz-transform 250ms cubic-bezier(0, 0, 0.25, 1) 0s, opacity 250ms cubic-bezier(0, 0, 0.25, 1) !important;
		-o-transition: -o-transform 250ms cubic-bezier(0, 0, 0.25, 1) 0s, opacity 250ms cubic-bezier(0, 0, 0.25, 1) !important;
		transition: transform 250ms cubic-bezier(0, 0, 0.25, 1) 0s, opacity 250ms cubic-bezier(0, 0, 0.25, 1) !important;
		-webkit-transform-origin: 50% 50%;
		-moz-transform-origin: 50% 50%;
		-ms-transform-origin: 50% 50%;
		transform-origin: 50% 50%;
	}
	body:not(.tsvg-elastic-lg-from-hash) .tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-start-zoom .tsvg-elastic-lg-item.tsvg-elastic-lg-complete .tsvg-elastic-lg-object {
		-webkit-transform: scale3d(1, 1, 1);
		transform: scale3d(1, 1, 1);
		opacity: 1;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb-outer {
		background-color: #0D0A0A;
		bottom: 0;
		position: absolute;
		width: 100%;
		z-index: 1080;
		max-height: 350px;
		-webkit-transform: translate3d(0, 100%, 0);
		transform: translate3d(0, 100%, 0);
		-webkit-transition: -webkit-transform 0.25s cubic-bezier(0, 0, 0.25, 1) 0s;
		-moz-transition: -moz-transform 0.25s cubic-bezier(0, 0, 0.25, 1) 0s;
		-o-transition: -o-transform 0.25s cubic-bezier(0, 0, 0.25, 1) 0s;
		transition: transform 0.25s cubic-bezier(0, 0, 0.25, 1) 0s;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb-outer.tsvg-elastic-lg-grab .tsvg-elastic-lg-thumb-item {
		cursor: -webkit-grab;
		cursor: -moz-grab;
		cursor: -o-grab;
		cursor: -ms-grab;
		cursor: grab;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb-outer.tsvg-elastic-lg-grabbing .tsvg-elastic-lg-thumb-item {
		cursor: move;
		cursor: -webkit-grabbing;
		cursor: -moz-grabbing;
		cursor: -o-grabbing;
		cursor: -ms-grabbing;
		cursor: grabbing;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb-outer.tsvg-elastic-lg-dragging .tsvg-elastic-lg-thumb {
		-webkit-transition-duration: 0s !important;
		transition-duration: 0s !important;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-thumb-open .tsvg-elastic-lg-thumb-outer {
		-webkit-transform: translate3d(0, 0%, 0);
		transform: translate3d(0, 0%, 0);
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb {
		padding: 10px 0;
		height: 100%;
		margin-bottom: -5px;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb-item {
		border-radius: 5px;
		cursor: pointer;
		float: left;
		overflow: hidden;
		height: 100%;
		border: 2px solid #FFF;
		border-radius: 4px;
		margin-bottom: 5px;
	}
	@media (min-width: 1025px) {
		.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb-item {
			-webkit-transition: border-color 0.25s ease;
			-o-transition: border-color 0.25s ease;
			transition: border-color 0.25s ease;
		}
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb-item.active,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb-item:hover {
		border-color: #a90707;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-thumb-item img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-has-thumb .tsvg-elastic-lg-item {
		padding-bottom: 120px;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-can-toggle .tsvg-elastic-lg-item {
		padding-bottom: 0;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-toogle-thumb {
		background-color: #0D0A0A;
		border-radius: 2px 2px 0 0;
		color: #999;
		cursor: pointer;
		font-size: 24px;
		height: 39px;
		line-height: 27px;
		padding: 5px 0;
		position: absolute;
		right: 20px;
		text-align: center;
		top: -39px;
		width: 50px;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-toogle-thumb:after {
		content: "\e1ff";
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-toogle-thumb:hover {
		color: #FFF;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-video-cont {
		display: flex;
		align-content: center;
		justify-content: center;
		align-items: center;
		max-height: 100%;
		width: 70%;
		max-width: 70% !important;
		border: var(--tsvg_popup_pso_b_w<?php echo esc_attr( $tsvg_shortcode_id ); ?>) var(--tsvg_popup_pso_br_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>) var(--tsvg_popup_pso_b_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		-webkit-box-shadow: 0px 0px var(--tsvg_popup_pso_sh_<?php echo esc_attr( $tsvg_shortcode_id ); ?>) var(--tsvg_popup_pso_sh_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-video .tsvg-elastic-lg-object {
		display: inline-block;
		position: absolute;
		top: 0;
		left: 0;
		width: 100% !important;
		height: 100% !important;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-video .tsvg-elastic-lg-video-play {
		width: 84px;
		height: 59px;
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translateY(-50%) translateX(-50%);
		-webkit-transform: translateY(-50%) translateX(-50%);
		-ms-transform: translateY(-50%) translateX(-50%);
		-moz-transform: translateY(-50%) translateX(-50%);
		-o-transform: translateY(-50%) translateX(-50%);
		z-index: 1080;
		cursor: pointer;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-video-object {
		width: 100% !important;
		height: 100% !important;
		position: absolute;
		top: 0;
		left: 0;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-has-video .tsvg-elastic-lg-video-object {
		visibility: hidden;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-has-video.tsvg-elastic-lg-video-playing .tsvg-elastic-lg-object,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-has-video.tsvg-elastic-lg-video-playing .tsvg-elastic-lg-video-play {
		display: none;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-has-video.tsvg-elastic-lg-video-playing .tsvg-elastic-lg-video-object {
		visibility: visible;
	}
	iframe.tsvg-elastic-lg-video-object{
		background:var(--tsvg_popup_po_ob_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?> );
	}
	.tsvg-elastic-lg-progress-bar {
		background-color: #333;
		height: 5px;
		left: 0;
		position: absolute;
		top: 0;
		width: 100%;
		z-index: 1083;
		opacity: 0;
		-webkit-transition: opacity 0.08s ease 0s;
		-moz-transition: opacity 0.08s ease 0s;
		-o-transition: opacity 0.08s ease 0s;
		transition: opacity 0.08s ease 0s;
	}
	.tsvg-elastic-lg-progress-bar .tsvg-elastic-lg-progress {
		background-color: #a90707;
		height: 5px;
		width: 0;
	}
	.tsvg-elastic-lg-progress-bar.tsvg-elastic-lg-start .tsvg-elastic-lg-progress {
		width: 100%;
	}
	.tsvg-elastic-lg-show-autoplay .tsvg-elastic-lg-progress-bar {
		opacity: 1;
	}
	.tsvg-elastic-lg-autoplay-button:after {
		content: "\e01d";
	}
	.tsvg-elastic-lg-show-autoplay .tsvg-elastic-lg-autoplay-button:after {
		content: "\e01a";
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-css3.tsvg-elastic-lg-zoom-dragging .tsvg-elastic-lg-item.tsvg-elastic-lg-complete.tsvg-elastic-lg-zoomable .tsvg-elastic-lg-img-wrap,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-css3.tsvg-elastic-lg-zoom-dragging .tsvg-elastic-lg-item.tsvg-elastic-lg-complete.tsvg-elastic-lg-zoomable .tsvg-elastic-lg-image {
		-webkit-transition-duration: 0s;
		transition-duration: 0s;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-item.tsvg-elastic-lg-complete.tsvg-elastic-lg-zoomable .tsvg-elastic-lg-img-wrap {
		-webkit-transition: left 0.3s cubic-bezier(0, 0, 0.25, 1) 0s, top 0.3s cubic-bezier(0, 0, 0.25, 1) 0s;
		-moz-transition: left 0.3s cubic-bezier(0, 0, 0.25, 1) 0s, top 0.3s cubic-bezier(0, 0, 0.25, 1) 0s;
		-o-transition: left 0.3s cubic-bezier(0, 0, 0.25, 1) 0s, top 0.3s cubic-bezier(0, 0, 0.25, 1) 0s;
		transition: left 0.3s cubic-bezier(0, 0, 0.25, 1) 0s, top 0.3s cubic-bezier(0, 0, 0.25, 1) 0s;
		-webkit-transform: translate3d(0, 0, 0);
		transform: translate3d(0, 0, 0);
		-webkit-backface-visibility: hidden;
		-moz-backface-visibility: hidden;
		backface-visibility: hidden;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-item.tsvg-elastic-lg-complete.tsvg-elastic-lg-zoomable .tsvg-elastic-lg-image {
		-webkit-transform: scale3d(1, 1, 1);
		transform: scale3d(1, 1, 1);
		-webkit-transition: -webkit-transform 0.3s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.15s !important;
		-moz-transition: -moz-transform 0.3s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.15s !important;
		-o-transition: -o-transform 0.3s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.15s !important;
		transition: transform 0.3s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.15s !important;
		-webkit-transform-origin: 0 0;
		-moz-transform-origin: 0 0;
		-ms-transform-origin: 0 0;
		transform-origin: 0 0;
		-webkit-backface-visibility: hidden;
		-moz-backface-visibility: hidden;
		backface-visibility: hidden;
	}
	#tsvg-elastic-lg-zoom-in:after {
		content: "\e311";
	}
	#tsvg-elastic-lg-actual-size {
		font-size: 20px;
	}
	#tsvg-elastic-lg-actual-size:after {
		content: "\e033";
	}
	#tsvg-elastic-lg-zoom-out {
		opacity: 0.5;
		pointer-events: none;
	}
	#tsvg-elastic-lg-zoom-out:after {
		content: "\e312";
	}
	.tsvg-elastic-lg-zoomed #tsvg-elastic-lg-zoom-out {
		opacity: 1;
		pointer-events: auto;
	}
	.tsvg-elastic-lg-fullscreen:after {
		content: "\e20c";
	}
	.tsvg-elastic-lg-fullscreen-on .tsvg-elastic-lg-fullscreen:after {
		content: "\e20d";
	}
	.tsvg-elastic-group:before,
	.tsvg-elastic-group:after {
		display: table;
		content: "";
		line-height: 0;
	}
	.tsvg-elastic-group:after {
		clear: both;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> {
		width: 100%;
		height: 100%;
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999999999999;
		opacity: 0;
		text-align: left !important;
		-webkit-transition: opacity 0.15s ease 0s;
		-o-transition: opacity 0.15s ease 0s;
		transition: opacity 0.15s ease 0s;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> * {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-visible {
		opacity: 1;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-css3 .tsvg-elastic-lg-item.tsvg-elastic-lg-prev-slide,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-css3 .tsvg-elastic-lg-item.tsvg-elastic-lg-next-slide,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-css3 .tsvg-elastic-lg-item.tsvg-elastic-lg-current {
		-webkit-transition-duration: inherit !important;
		transition-duration: inherit !important;
		-webkit-transition-timing-function: inherit !important;
		transition-timing-function: inherit !important;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-css3.tsvg-elastic-lg-dragging .tsvg-elastic-lg-item.tsvg-elastic-lg-prev-slide,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-css3.tsvg-elastic-lg-dragging .tsvg-elastic-lg-item.tsvg-elastic-lg-next-slide,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-css3.tsvg-elastic-lg-dragging .tsvg-elastic-lg-item.tsvg-elastic-lg-current {
		-webkit-transition-duration: 0s !important;
		transition-duration: 0s !important;
		opacity: 1;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-grab img.tsvg-elastic-lg-object {
		cursor: -webkit-grab;
		cursor: -moz-grab;
		cursor: -o-grab;
		cursor: -ms-grab;
		cursor: grab;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-grabbing img.tsvg-elastic-lg-object {
		cursor: move;
		cursor: -webkit-grabbing;
		cursor: -moz-grabbing;
		cursor: -o-grabbing;
		cursor: -ms-grabbing;
		cursor: grabbing;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg {
		height: 100%;
		width: 100%;
		position: relative;
		overflow: hidden;
		margin-left: auto;
		margin-right: auto;
		max-width: 100%;
		max-height: 100%;
		display: flex;
    	flex-direction: row;
    	flex-wrap: wrap;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-inner {
		width: 100%;
		height: 100%;
		white-space: nowrap;
		display: flex;
		height: calc(100% - 100px);
		width: calc(100% - 200px);
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-item {
		background: url("<?php echo esc_url(plugins_url( '../img/loading.gif', __FILE__ )); ?>") no-repeat scroll center center transparent;
		display: none !important;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-css .tsvg-elastic-lg-current {
		display: inline-block !important;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-current {
		display: flex !important;
		flex-direction: row;
		flex-wrap: nowrap;
		align-content: center;
		justify-content: center;
		align-items: center;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-item,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-img-wrap {
		/* display: inline-block; */
		text-align: center;
		/* position: absolute; */
		width: 100%;
		height: 100%;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-item:before,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-img-wrap:before {
		content: "";
		display: inline-block;
		height: 50%;
		width: 1px;
		margin-right: -1px;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-img-wrap {
		position: absolute;
		padding: 0 5px;
		left: 0;
		right: 0;
		top: 0;
		bottom: 0;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-item.tsvg-elastic-lg-complete {
		background-image: none;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-item.tsvg-elastic-lg-current {
		/* z-index: 1060; */
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-image {
		display: inline-block;
		vertical-align: middle;
		max-width: 100%;
		max-height: 100%;
		width: auto !important;
		height: auto !important;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-show-after-load .tsvg-elastic-lg-item .tsvg-elastic-lg-object,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-show-after-load .tsvg-elastic-lg-item .tsvg-elastic-lg-video-play {
		opacity: 0;
		-webkit-transition: opacity 0.15s ease 0s;
		-o-transition: opacity 0.15s ease 0s;
		transition: opacity 0.15s ease 0s;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-show-after-load .tsvg-elastic-lg-item.tsvg-elastic-lg-complete .tsvg-elastic-lg-object,
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-show-after-load .tsvg-elastic-lg-item.tsvg-elastic-lg-complete .tsvg-elastic-lg-video-play {
		opacity: 1;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-empty-html {
		display: none;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>.tsvg-elastic-lg-hide-download #tsvg-elastic-lg-download {
		display: none;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-no-trans .tsvg-elastic-lg-prev-slide,
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-no-trans .tsvg-elastic-lg-next-slide,
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-no-trans .tsvg-elastic-lg-current {
		-webkit-transition: none 0s ease 0s !important;
		-moz-transition: none 0s ease 0s !important;
		-o-transition: none 0s ease 0s !important;
		transition: none 0s ease 0s !important;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-use-css3 .tsvg-elastic-lg-item {
		-webkit-backface-visibility: hidden;
		-moz-backface-visibility: hidden;
		backface-visibility: hidden;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-use-left .tsvg-elastic-lg-item {
		-webkit-backface-visibility: hidden;
		-moz-backface-visibility: hidden;
		backface-visibility: hidden;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-fade .tsvg-elastic-lg-item {
		opacity: 0;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-fade .tsvg-elastic-lg-item.tsvg-elastic-lg-current {
		opacity: 1;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-fade .tsvg-elastic-lg-item.tsvg-elastic-lg-prev-slide,
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-fade .tsvg-elastic-lg-item.tsvg-elastic-lg-next-slide,
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-fade .tsvg-elastic-lg-item.tsvg-elastic-lg-current {
		-webkit-transition: opacity 0.1s ease 0s;
		-moz-transition: opacity 0.1s ease 0s;
		-o-transition: opacity 0.1s ease 0s;
		transition: opacity 0.1s ease 0s;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-css3 .tsvg-elastic-lg-item {
		opacity: 0;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-css3 .tsvg-elastic-lg-item.tsvg-elastic-lg-prev-slide {
		-webkit-transform: translate3d(-100%, 0, 0);
		transform: translate3d(-100%, 0, 0);
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-css3 .tsvg-elastic-lg-item.tsvg-elastic-lg-next-slide {
		-webkit-transform: translate3d(100%, 0, 0);
		transform: translate3d(100%, 0, 0);
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-css3 .tsvg-elastic-lg-item.tsvg-elastic-lg-current {
		-webkit-transform: translate3d(0, 0, 0);
		transform: translate3d(0, 0, 0);
		opacity: 1;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-css3 .tsvg-elastic-lg-item.tsvg-elastic-lg-prev-slide,
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-css3 .tsvg-elastic-lg-item.tsvg-elastic-lg-next-slide,
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-css3 .tsvg-elastic-lg-item.tsvg-elastic-lg-current {
		-webkit-transition: -webkit-transform 1s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.1s ease 0s;
		-moz-transition: -moz-transform 1s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.1s ease 0s;
		-o-transition: -o-transform 1s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.1s ease 0s;
		transition: transform 1s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.1s ease 0s;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-left .tsvg-elastic-lg-item {
		opacity: 0;
		position: absolute;
		left: 0;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-left .tsvg-elastic-lg-item.tsvg-elastic-lg-prev-slide {
		left: -100%;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-left .tsvg-elastic-lg-item.tsvg-elastic-lg-next-slide {
		left: 100%;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-left .tsvg-elastic-lg-item.tsvg-elastic-lg-current {
		left: 0;
		opacity: 1;
	}
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-left .tsvg-elastic-lg-item.tsvg-elastic-lg-prev-slide,
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-left .tsvg-elastic-lg-item.tsvg-elastic-lg-next-slide,
	.tsvg-elastic-lg-css3.tsvg-elastic-lg-slide.tsvg-elastic-lg-use-left .tsvg-elastic-lg-item.tsvg-elastic-lg-current {
		-webkit-transition: left 1s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.1s ease 0s;
		-moz-transition: left 1s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.1s ease 0s;
		-o-transition: left 1s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.1s ease 0s;
		transition: left 1s cubic-bezier(0, 0, 0.25, 1) 0s, opacity 0.1s ease 0s;
	}
	.tsvg-elastic-lg-backdrop<?php echo esc_attr( $tsvg_shortcode_id ); ?> {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		z-index: 1040;
		background-color: var(--tsvg_popup_po_ob_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		-webkit-transition: opacity 0.15s ease 0s;
		-moz-transition: opacity 0.15s ease 0s;
		-o-transition: opacity 0.15s ease 0s;
		transition: opacity 0.15s ease 0s;
		z-index: 999999999999999;
	}
	.tsvg-elastic-lg-toolbar .tsvg-elastic-lg-close:after {
		display: none !important;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-actions .tsvg-elastic-lg-next,.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>  .tsvg-elastic-lg-actions .tsvg-elastic-lg-prev {
		background-color: var(--tsvg_popup_pso_ib_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		border-radius: 2px;
		color: var(--tsvg_popup_pso_i_c_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		cursor: pointer;
		display: block;
		font-size: var(--tsvg_popup_pso_i_s_<?php echo esc_attr( $tsvg_shortcode_id ); ?>);
		perspective: 800px;
		z-index: 1080;
	}
	.tsvg-elastic-lg-actions .tsvg-elastic-lg-prev:after {
		display: none;
	}
	.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-video {
    	position: relative;
		width: 100%;
		height: 0;
		padding-bottom: 56.25%;
	}
	@media screen and (	max-width: 1200px) {
		.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-video-cont {
			width: 80%;
			max-width: 80% !important;
		}
	}
	@media screen and (	max-width: 820px) {
		.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg-video-cont {
			width: 100%;
			max-width: 100% !important;
		}
		.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> .tsvg-elastic-lg{
			align-content: flex-start;
		}
		.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>  .tsvg-elastic-lg-inner {
			height: calc(100% - 200px);
			width: 100%;
			padding: 0 30px;
		}
		.tsvg-elastic-lg-actions<?php echo esc_attr( $tsvg_shortcode_id ); ?> {
			width: 100px;
			height: 100px;
			position: absolute;
			bottom :0;
		}
		.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>  .tsvg-elastic-lg-actions-next<?php echo esc_attr( $tsvg_shortcode_id ); ?> {
    		align-items: flex-end;
			right: 30px;
		}
		.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>  .tsvg-elastic-lg-actions-prev<?php echo esc_attr( $tsvg_shortcode_id ); ?> {
    		align-items: flex-start;
			left: 30px;
		}
	}
</style>
<script type="text/javascript">
	let tsvgElasticInit<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = false,
    	tsvgElasticWidth<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = 0,
		<?php if ( $tsvg_edit === 'true' ) { ?>
            tsvgElasticResizeWidth,
            tsvgElasticForAdmin,
        <?php } ?>
		tsvgElasticTimeout<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>,
        tsvgElasticDoneResizing<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>,
        tsvgElasticInstance<?php echo esc_attr( $tsvg_shortcode_id ); ?> = false,
        tsvgElasticCheck<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = function (){
            if( typeof(jQuery) != "undefined" && jQuery != null && 
                typeof(ResizeSensor) != "undefined" && ResizeSensor != null &&
                document.readyState === 'complete'
            ){
                return true;
            }
            return false;
        },
		tsvgElasticCreate<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = function () {
            if(tsvgElasticCheck<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>() === true){
                if(!tsvgElasticInit<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>){
					tsvgElasticDoneResizing<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = function() {
                        let tsvgElasticColumnCount = getComputedStyle(document.documentElement).getPropertyValue('--tsvg_g_img_w_<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>');
                        if (tsvgElasticWidth<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> == 0 || tsvgElasticWidth<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> == "0") {
                            tsvgElasticWidth<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = jQuery('#tsvg-section-<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>').width();
                        }
                        if(450 >= tsvgElasticWidth<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>){
                            tsvgElasticColumnCount = 1;
                        }else if ( 550 >= tsvgElasticWidth<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>){
                            tsvgElasticColumnCount = tsvgElasticColumnCount == 1 || tsvgElasticColumnCount == "1" ? 1 : 2;
                        }
                        document.documentElement.style.setProperty('--tsvg_general_columns_<?php echo esc_attr( $tsvg_shortcode_id ); ?>', tsvgElasticColumnCount);
                    };
					new ResizeSensor(jQuery('#tsvg-section-<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>'), function(event){
                        if( tsvgElasticWidth<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> !== event.width ){
                            tsvgElasticWidth<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = event.width;
                            clearTimeout(tsvgElasticTimeout<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>);
                            tsvgElasticTimeout<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = setTimeout(tsvgElasticDoneResizing<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>, 100);
                        }
                    });
					(function ($, window, document, undefined) {
						let tsvgElasticSlideDuration<?php echo esc_attr( $tsvg_shortcode_id ); ?> = parseInt(window.getComputedStyle(document.documentElement).getPropertyValue('--tsvg_popup_po_se_t_<?php echo esc_attr( $tsvg_shortcode_id ); ?>')),
							tsvgElasticCloseIcon<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-sldelIcType'),
							tsvgElasticLeftIcon<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-slicLeftType'),
							tsvgElasticRightIcon<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-slicRightType'),
							tsvgElasticLoopCheck<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-loop') == 'true',
							tsvgElasticDefaults<?php echo esc_attr( $tsvg_shortcode_id ); ?> = {
								mode: 'lg-slide',
								cssEasing: 'ease',
								easing: 'linear',
								speed: tsvgElasticSlideDuration<?php echo esc_attr( $tsvg_shortcode_id ); ?> * 100,
								addClass: '',
								startClass: 'tsvg-elastic-lg-start-zoom',
								backdropDuration: 150,
								hideBarsDelay: 20000,
								closable: true,
								loop: tsvgElasticLoopCheck<?php echo esc_attr( $tsvg_shortcode_id ); ?>,
								escKey: true,
								keyPress: true,
								controls: true,
								slideEndAnimatoin: true,
								mousewheel: true,
								preload: 1,
								showAfterLoad: true,
								selector: '',
								selectWithin: '',
								nextHtml: '',
								prevHtml: '',
								index: false,
								iframeMaxWidth: '100%',
								download: false,
								counter: true,
								prependCounterTo: '.tsvg-elastic-lg-toolbar<?php echo esc_attr( $tsvg_shortcode_id ); ?>',
								swipeThreshold: 50,
								enableSwipe: true,
								enableDrag: true,
								dynamic: false,
								dynamicEl: [],
								title: ''
							};
						'use strict';
						let tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?> = function (element, options) {
							this.el = element;
							this.$el = $(element);
							this.s = $.extend({}, tsvgElasticDefaults<?php echo esc_attr( $tsvg_shortcode_id ); ?>, options);
							this.modules = {};
							this.lGalleryOn = false;
							this.lgBusy = false;
							this.hideBartimeout = false;
							this.isTouch = ('ontouchstart' in document.documentElement);
							if (this.s.selector === 'this') {
								this.$items = this.$el;
							} else if (this.s.selector !== '') {
								if (this.s.selectWithin) {
									this.$items = $(this.s.selectWithin).find(this.s.selector);
								} else {
									this.$items = this.$el.find($(this.s.selector));
								}
							} else {
								this.$items = this.$el.children();
							}
							this.$slide = '';
							this.$outer = '';
							this.init();
							return this;
						}
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.init = function () {
							
							let _this = this;
							if (_this.s.preload > _this.$items.length) {
								_this.s.preload = _this.$items.length;
							}
							_this.$items.on('click.lgcustom', function (event) {
								try {
									event.preventDefault();
								} catch (er) {
									event.returnValue = false;
								}
								if (jQuery(event.target).parent().prop("tagName") == 'A') {
									let link = jQuery(event.target).parent().attr('href');
									if (jQuery(event.target).parent().attr('target') == '_blank') {
										window.open(link);
									} else {
										window.location.assign(link)
									}
									return false;
								}
								_this.$el.trigger('onBeforeOpen.lg');
								_this.index = _this.s.index || _this.$items.index(this);
								if (!$('body').hasClass('tsvg-elastic-lg-on')) {
									_this.build(_this.index);
									$('body').addClass('tsvg-elastic-lg-on');
								}
							});
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.build = function (index) {
							jQuery('html').css({ 'scrollbar-width': 'none' });
							let _this = this;
							_this.structure();
							$.each($.fn.lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>.modules, function (key) {
								_this.modules[key] = new $.fn.lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>.modules[key](_this.el);
							});
							_this.slide(index, false, false);
							if (_this.s.keyPress) {
								_this.keyPress();
							}
							if (_this.$items.length > 1) {
								_this.arrow();
								setTimeout(function () {
									_this.enableDrag();
									_this.enableSwipe();
								}, 50);
								if (_this.s.mousewheel) {
									_this.mousewheel();
								}
							}
							_this.counter();
							_this.closeGallery();
							_this.$el.trigger('onAfterOpen.lg');
							_this.$outer.on('mousemove.lg click.lg touchstart.lg', function () {
								_this.$outer.removeClass('tsvg-elastic-lg-hide-items');
								clearTimeout(_this.hideBartimeout);
								_this.hideBartimeout = setTimeout(function () {
									_this.$outer.addClass('tsvg-elastic-lg-hide-items');
								}, _this.s.hideBarsDelay);
							});
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.structure = function () {
							let list = '',
							 	leftControl = '',
							 	rightControl = '',
							 	i = 0,
							 	template,
								title = '',
								_this = this;
							this.s.speed = parseInt(window.getComputedStyle(document.documentElement).getPropertyValue('--tsvg_popup_po_se_t_<?php echo esc_attr( $tsvg_shortcode_id ); ?>'));
							tsvgElasticLoopCheck<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-loop') == 'true';
							this.s.loop = tsvgElasticLoopCheck<?php echo esc_attr( $tsvg_shortcode_id ); ?>;
							tsvgElasticCloseIcon<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-sldelIcType');
							tsvgElasticLeftIcon<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-slicLeftType');
							tsvgElasticRightIcon<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-slicRightType');
							$('body').append('<div class="tsvg-elastic-lg-backdrop tsvg-elastic-lg-backdrop<?php echo esc_attr( $tsvg_shortcode_id ); ?>"></div>');
							$('.tsvg-elastic-lg-backdrop<?php echo esc_attr( $tsvg_shortcode_id ); ?>').css('transition-duration', this.s.backdropDuration + 'ms');
							title = '<h4 class="tsvg-elastic-lg-toolbar-title tsvg-elastic-lg-toolbar-title<?php echo esc_attr( $tsvg_shortcode_id ); ?>">' + this.$items[this.index].innerText + '</h4>';
							for (i = 0; this.$items.length > i; i++) {
								list += '<div class="tsvg-elastic-lg-item"></div>';
							}
							if (this.s.controls && this.$items.length > 1) {
								leftControl = '<div class="tsvg-elastic-lg-actions tsvg-elastic-lg-actions<?php echo esc_attr( $tsvg_shortcode_id ); ?> tsvg-elastic-lg-actions-prev<?php echo esc_attr( $tsvg_shortcode_id ); ?>">' + '<i class="tsvg-elastic-lg-prev tsvg-elastic-lg-prev<?php echo esc_attr( $tsvg_shortcode_id ); ?> ' + tsvgElasticLeftIcon<?php echo esc_attr( $tsvg_shortcode_id ); ?> + '">' + this.s.prevHtml + '</i>'  + '</div>';
								rightControl = '<div class="tsvg-elastic-lg-actions tsvg-elastic-lg-actions<?php echo esc_attr( $tsvg_shortcode_id ); ?> tsvg-elastic-lg-actions-next<?php echo esc_attr( $tsvg_shortcode_id ); ?>">' + '<i class="tsvg-elastic-lg-next tsvg-elastic-lg-next<?php echo esc_attr( $tsvg_shortcode_id ); ?> ' + tsvgElasticRightIcon<?php echo esc_attr( $tsvg_shortcode_id ); ?> + '">' + this.s.nextHtml + '</i>' + '</div>';
							}
							template = '<div class="tsvg-elastic-lg-outer tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?> ' + this.s.addClass + ' ' + this.s.startClass + '"  data-show="yes">' + '<div class="tsvg-elastic-lg">' + '<div class="tsvg-elastic-lg-toolbar tsvg-elastic-lg-toolbar<?php echo esc_attr( $tsvg_shortcode_id ); ?> tsvg-elastic-group">' + title + '</div>' + leftControl + '<div class="tsvg-elastic-lg-inner">' + list + '</div>' + rightControl + '</div>' + '</div>';
							$('body').append(template);
							this.$outer = $('.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>');
							this.$slide = this.$outer.find('.tsvg-elastic-lg-item');
							this.$outer.addClass('lg-use-css3');
							$(window).on('resize.lg orientationchange.lg', function () {
								setTimeout(function () {}, 100);
							});
							this.$slide.eq(this.index).addClass('tsvg-elastic-lg-current');
							if (this.doCss()) {
								this.$outer.addClass('tsvg-elastic-lg-css3');
							} else {
								this.$outer.addClass('tsvg-elastic-lg-css');
								this.s.speed = 0;
							}
							this.$outer.addClass(this.s.mode);
							if (this.s.enableDrag && this.$items.length > 1) {
								this.$outer.addClass('lg-grab');
							}
							if (this.s.showAfterLoad) {
								this.$outer.addClass('tsvg-elastic-lg-show-after-load');
							}
							if (this.doCss()) {
								let $inner = this.$outer.find('.tsvg-elastic-lg-inner');
								$inner.css('transition-timing-function', this.s.cssEasing);
								$inner.css('transition-duration', this.s.speed + 'ms');
							}
							$('.tsvg-elastic-lg-backdrop<?php echo esc_attr( $tsvg_shortcode_id ); ?>').addClass('in');
							setTimeout(function () {
								_this.$outer.addClass('tsvg-elastic-lg-visible');
							}, this.s.backdropDuration);
							this.prevScrollTop = $(window).scrollTop();
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.doCss = function () {
							let support = function () {
								let transition = ['transition', 'MozTransition', 'WebkitTransition', 'OTransition', 'msTransition', 'KhtmlTransition'],
									root = document.documentElement,
									i = 0;
								for (i = 0;transition.length > i; i++) {
									if (transition[i] in root.style) {
										return true;
									}
								}
							};
							if (support()) {
								return true;
							}
							return false;
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.isVideo = function (src, index) {
							let html = this.$items.eq(index).attr('data-html');
							if (!src && html) {
								return { html5: true };
							}
							let tsvg_autoplay = jQuery('.tsvg-main-content-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-autoplay');
							if (src.indexOf('youtube.com/shorts/') > -1 ) {
								src = src.replace("shorts", "embed")
							}
							let youtube = src.match(/\/\/(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=|embed\/)?([a-z0-9\-\_\%]+)/i);
								vimeo = src.match(/\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i),
								dailymotion = src.match(/\/\/(?:www\.)?dai.ly\/([0-9a-z\-_]+)/i),
								vk = src.match(/\/\/(?:www\.)?(?:vk\.com|vkontakte\.ru)\/(?:video_ext\.php\?)(.*)/i),
								wistia = src,
								mp4 = src.match(/.mp4/);
							if (youtube) {
								return { youtube: youtube };
							} else if (vimeo) {
								return { vimeo: vimeo };
							} else if (dailymotion) {
								return { dailymotion: dailymotion };
							} else if (vk) {
								return { vk: vk };
							} else if (mp4) {
								return { mp4: src };
							} else if (wistia) {
								return { wistia: wistia };
							}	
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.counter = function () {
							if (this.s.counter) {
								$(this.s.prependCounterTo).prepend('<div id="tsvg-elastic-lg-counter<?php echo esc_attr( $tsvg_shortcode_id ); ?>"><span id="tsvg-elastic-lg-counter<?php echo esc_attr( $tsvg_shortcode_id ); ?>-current">' + (parseInt(this.index, 10) + 1) + '</span> / <span id="tsvg-elastic-lg-counter<?php echo esc_attr( $tsvg_shortcode_id ); ?>-all">' + this.$items.length + '</span></div>');
							}
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.preload = function (index) {
							let i = 1, j = 1;
							for (i = 1; this.s.preload >= i; i++) {
								if (i >= this.$items.length - index) {
									break;
								}
								this.loadContent(index + i, false, 0);
							}
							for (j = 1; this.s.preload >= j; j++) {
								if ( 0 > index - j) {
									break;
								}
								this.loadContent(index - j, false, 0);
							}
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.loadContent = function (index, rec, delay) {
							let _this = this,
							 	_hasPoster = false,
							 	_$img,
							 	_src,
							 	_poster,
							 	_title,
							 	_srcset,
							 	_sizes,
							 	_html;
							let getResponsiveSrc = function (srcItms) {
								let rsWidth = [], rsSrc = [];
								for (let i = 0; srcItms.length > i; i++) {
									let __src = srcItms[i].split(' ');
									if (__src[0] === '') {
										__src.splice(0, 1);
									}
									rsSrc.push(__src[0]);
									rsWidth.push(__src[1]);
								}
								let wWidth = $(window).width();
								for (let j = 0; rsWidth.length > j; j++) {
									if (parseInt(rsWidth[j], 10) > wWidth) {
										_src = rsSrc[j];
										break;
									}
								}
							};
							if (_this.$items.eq(index).attr('data-poster')) {
								_hasPoster = true;
								_poster = _this.$items.eq(index).attr('data-poster');
							}
							if (_this.$items.eq(index).attr('data-title')) {
								_hasTitle = true;
								_title = _this.$items.eq(index).attr('data-title');
							}
							_html = _this.$items.eq(index).attr('data-html');
							_src = _this.$items.eq(index).attr('href') || _this.$items.eq(index).attr('data-src');
							if (_this.$items.eq(index).attr('data-responsive')) {
								let srcItms = _this.$items.eq(index).attr('data-responsive').split(',');
								getResponsiveSrc(srcItms);
							}
							_srcset = _this.$items.eq(index).attr('data-srcset');
							_sizes = _this.$items.eq(index).attr('data-sizes');
							let iframe = false;
							if (_this.$items.eq(index).attr('data-iframe') === 'true') {
								iframe = true;
							}
							let _isVideo = _this.isVideo(_src, index);
							if (!_this.$slide.eq(index).hasClass('tsvg-elastic-lg-loaded')) {
								if (iframe) {
									_this.$slide.eq(index).prepend('<div class="tsvg-elastic-lg-video-cont" style="max-width:' + _this.s.iframeMaxWidth + '"><div class="tsvg-elastic-lg-video tsvg-elastic-lg-video<?php echo esc_attr( $tsvg_shortcode_id ); ?>"><iframe class="tsvg-elastic-lg-object" frameborder="0" src="' + _src + '"  allowfullscreen="true"></iframe></div></div>');
								}
								else if (_isVideo) {
									_this.$slide.eq(index).prepend('<div class="tsvg-elastic-lg-video-cont "><div class="tsvg-elastic-lg-video tsvg-elastic-lg-video<?php echo esc_attr( $tsvg_shortcode_id ); ?>"></div></div>');
									_this.$el.trigger('hasVideo.lg', [index, _src, _html]);
								} else {
									_this.$slide.eq(index).prepend('<div class="tsvg-elastic-lg-img-wrap"><img class="tsvg-elastic-lg-object tsvg-elastic-lg-image" src="' + _src + '" /></div>');
								}
								_this.$el.trigger('onAferAppendSlide.lg', [index]);
								_$img = _this.$slide.eq(index).find('.tsvg-elastic-lg-object');
								if (_sizes) {
									_$img.attr('sizes', _sizes);
								}
								if (_srcset) {
									_$img.attr('srcset', _srcset);
									try {
										picturefill({ elements: [_$img[0]] });
									} catch (e) {
										console.error('Make sure you have included Picturefill version 2');
									}
								}
								_this.$slide.eq(index).addClass('tsvg-elastic-lg-loaded');
							}
							_this.$slide.eq(index).find('.tsvg-elastic-lg-object').on('load.lg error.lg', function () {
								let _speed = 0;
								if (delay && !$('body').hasClass('lg-from-hash')) {
									_speed = delay;
								}
								setTimeout(function () {
									_this.$slide.eq(index).addClass('tsvg-elastic-lg-complete');
									_this.$el.trigger('onSlideItemLoad.lg', [index, delay || 0]);
								}, _speed);
							});
							if (_isVideo && _isVideo.html5 && !_hasPoster) {
								_this.$slide.eq(index).addClass('tsvg-elastic-lg-complete');
							}
							if (rec === true) {
								if (!_this.$slide.eq(index).hasClass('tsvg-elastic-lg-complete')) {
									_this.$slide.eq(index).find('.tsvg-elastic-lg-object').on('load.lg error.lg', function () {
										_this.preload(index);
									});
								} else {
									_this.preload(index);
								}
							}
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.slide = function (index, fromTouch, fromThumb) {
							let _prevIndex = this.$outer.find('.tsvg-elastic-lg-current').index(),
								_this = this;
							if (_this.lGalleryOn && (_prevIndex === index)) {
								return;
							}
							let _length = this.$slide.length,
							 	_time = _this.lGalleryOn ? this.s.speed : 0,
							 	_next = false,
							 	_prev = false;
							if (!_this.lgBusy) {
								if (this.s.download) {
									let _src = _this.$items.eq(index).attr('data-download-url') !== 'false' && (_this.$items.eq(index).attr('data-download-url') || _this.$items.eq(index).attr('href') || _this.$items.eq(index).attr('data-src'));
									if (_src) {
										$('#tsvg-elastic-lg-download').attr('href', _src);
										_this.$outer.removeClass('tsvg-lg-hide-download');
									} else {
										_this.$outer.addClass('tsvg-lg-hide-download');
									}
								}
								this.$el.trigger('onBeforeSlide.lg.tm', [_prevIndex, index, fromTouch, fromThumb]);
								_this.lgBusy = true;
								clearTimeout(_this.hideBartimeout);
								if (!fromTouch) {
									_this.$outer.addClass('lg-no-trans');
									this.$slide.removeClass('tsvg-elastic-lg-prev-slide tsvg-elastic-lg-next-slide');
									if (_prevIndex > index) {
										_prev = true;
										if ((index === 0) && (_prevIndex === _length - 1) && !fromThumb) {
											_prev = false;
											_next = true;
										}
									} else if (index > _prevIndex) {
										_next = true;
										if ((index === _length - 1) && (_prevIndex === 0) && !fromThumb) {
											_prev = true;
											_next = false;
										}
									}
									if (_prev) {
										this.$slide.eq(index).addClass('tsvg-elastic-lg-prev-slide');
										this.$slide.eq(_prevIndex).addClass('tsvg-elastic-lg-next-slide');
									} else if (_next) {
										this.$slide.eq(index).addClass('tsvg-elastic-lg-next-slide');
										this.$slide.eq(_prevIndex).addClass('tsvg-elastic-lg-prev-slide');
									}
									setTimeout(function () {
										_this.$slide.removeClass('tsvg-elastic-lg-current');
										_this.$slide.eq(index).addClass('tsvg-elastic-lg-current');
										_this.$outer.removeClass('lg-no-trans');
									}, 50);
								} else {
									let touchPrev = index - 1;
									let touchNext = index + 1;
									if ((index === 0) && (_prevIndex === _length - 1)) {
										touchNext = 0;
										touchPrev = _length - 1;
									} else if ((index === _length - 1) && (_prevIndex === 0)) {
										touchNext = 0;
										touchPrev = _length - 1;
									}
									this.$slide.removeClass('tsvg-elastic-lg-prev-slide tsvg-elastic-lg-current tsvg-elastic-lg-next-slide');
									_this.$slide.eq(touchPrev).addClass('tsvg-elastic-lg-prev-slide');
									_this.$slide.eq(touchNext).addClass('tsvg-elastic-lg-next-slide');
									_this.$slide.eq(index).addClass('tsvg-elastic-lg-current');
								}
								if (_this.lGalleryOn) {
									setTimeout(function () {
										_this.loadContent(index, true, 0);
									}, this.s.speed + 50);
									setTimeout(function () {
										_this.lgBusy = false;
										_this.$el.trigger('onAfterSlide.lg', [_prevIndex, index, fromTouch, fromThumb]);
									}, this.s.speed);
								} else {
									_this.loadContent(index, true, _this.s.backdropDuration);
									_this.lgBusy = false;
									_this.$el.trigger('onAfterSlide.lg', [_prevIndex, index, fromTouch, fromThumb]);
								}
								_this.lGalleryOn = true;
								if (this.s.counter) {
									$('#tsvg-elastic-lg-counter<?php echo esc_attr( $tsvg_shortcode_id ); ?>-current').text(index + 1);
								}
							}
							let title = this.$items[index].innerText;
							jQuery('.tsvg-elastic-lg-toolbar').find('h4').html(title);
							jQuery('.tsvg-elastic-lg-toolbar').find('h4').animate({ 'opacity': '1' }, 500);
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.goToNextSlide = function (fromTouch) {
							let _this = this,
								tsvg_index;
							if (!_this.lgBusy) {
								jQuery('html').css({ 'cursor': 'default' });
								if (_this.$slide.length > (_this.index + 1)) {
									_this.index++;
									_this.$el.trigger('onBeforeNextSlide.lg', [_this.index]);
									_this.slide(_this.index, fromTouch, false);
									tsvg_index = _this.index; 
								} else {
									if (_this.s.loop) {
										_this.index = 0;
										_this.$el.trigger('onBeforeNextSlide.lg', [_this.index]);
										_this.slide(_this.index, fromTouch, false);
									} else if (_this.s.slideEndAnimatoin) {
										_this.$outer.addClass('tsvg-elastic-lg-right-end');
										setTimeout(function () {
											_this.$outer.removeClass('tsvg-elastic-lg-right-end');
										}, 400);
									}
									tsvg_index = _this.index; 
								}
							}
							let title = fromTouch ? jQuery('.tsvg-elastic-lg-current').children('div').attr('data-title') : jQuery('ul.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?> li.tsvg-elastic-block-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').eq(tsvg_index).attr('data-title');
							jQuery('.tsvg-elastic-lg-toolbar').find('h4').html(title);
							jQuery('.tsvg-elastic-lg-toolbar').find('h4').animate({ 'opacity': '1' }, 500);
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.goToPrevSlide = function (fromTouch) {
							let _this = this,
								tsvg_index;
							if (!_this.lgBusy) {
								if (_this.index > 0) {
									_this.index--;
									_this.$el.trigger('onBeforePrevSlide.lg', [_this.index, fromTouch]);
									_this.slide(_this.index, fromTouch, false);
									jQuery('html').css({ 'cursor': 'default' });
									tsvg_index = _this.index; 
								} else {
									if (_this.s.loop) {
										_this.index = _this.$items.length - 1;
										_this.$el.trigger('onBeforePrevSlide.lg', [_this.index, fromTouch]);
										_this.slide(_this.index, fromTouch, false);
									} else if (_this.s.slideEndAnimatoin) {
										_this.$outer.addClass('tsvg-elastic-lg-left-end');
										setTimeout(function () {
											_this.$outer.removeClass('tsvg-elastic-lg-left-end');
										}, 400);
									}
									tsvg_index = _this.index; 
								}
							}
							jQuery('.tsvg-elastic-lg-toolbar').find('h4').css('opacity', '0');
							let title = fromTouch ? jQuery('.tsvg-elastic-lg-current').children('div').attr('data-title') : jQuery('ul.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?> li.tsvg-elastic-block-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').eq(tsvg_index).attr('data-title');
							jQuery('.tsvg-elastic-lg-toolbar').find('h4').html(title);
							jQuery('.tsvg-elastic-lg-toolbar').find('h4').animate({ 'opacity': '1' }, 500);
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.keyPress = function () {
							let _this = this;
							if (this.$items.length > 1) {
								$(window).on('keyup.lg', function (e) {
									if (_this.$items.length > 1) {
										if (e.keyCode === 37) {
											e.preventDefault();
											_this.goToPrevSlide();
										}
										if (e.keyCode === 39) {
											e.preventDefault();
											_this.goToNextSlide();
										}
									}
								});
							}
							$(window).on('keydown.lg', function (e) {
								if (_this.s.escKey === true && e.keyCode === 27) {
									e.preventDefault();
									if (!_this.$outer.hasClass('tsvg-elastic-lg-thumb-open')) {
										_this.destroy();
									} else {
										_this.$outer.removeClass('tsvg-elastic-lg-thumb-open');
									}
								}
							});
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.arrow = function () {
							let _this = this;
							this.$outer.find('.tsvg-elastic-lg-prev<?php echo esc_attr( $tsvg_shortcode_id ); ?>').on('click.lg', function () {
								_this.goToPrevSlide();
							});
							this.$outer.find('.tsvg-elastic-lg-next<?php echo esc_attr( $tsvg_shortcode_id ); ?>').on('click.lg', function () {
								_this.goToNextSlide();
							});
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.setTranslate = function ($el, xValue, yValue) {
							$el.css({ transform: 'translate3d(' + (xValue) + 'px, ' + yValue + 'px, 0px)' });
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.touchMove = function (startCoords, endCoords) {
							let distance = endCoords - startCoords;
							if (Math.abs(distance) > 15) {
								this.$outer.addClass('lg-dragging');
								this.setTranslate(this.$slide.eq(this.index), distance, 0);
								this.setTranslate($('.tsvg-elastic-lg-prev-slide'), -this.$slide.eq(this.index).width() + distance, 0);
								this.setTranslate($('.tsvg-elastic-lg-next-slide'), this.$slide.eq(this.index).width() + distance, 0);
							}
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.touchEnd = function (distance) {
							let _this = this;
							if (_this.s.mode !== 'lg-slide') {
								_this.$outer.addClass('lg-slide');
							}
							this.$slide.not('.tsvg-elastic-lg-current, .tsvg-elastic-lg-prev-slide, .tsvg-elastic-lg-next-slide').css('opacity', '0');
							setTimeout(function () {
								_this.$outer.removeClass('lg-dragging');
								if ((0 > distance) && (Math.abs(distance) > _this.s.swipeThreshold)) {
									_this.goToNextSlide(true);
								} else if ((distance > 0) && (Math.abs(distance) > _this.s.swipeThreshold)) {
									_this.goToPrevSlide(true);
								} else if (5 > Math.abs(distance)) {
									_this.$el.trigger('onSlideClick.lg');
								}
								_this.$slide.removeAttr('style');
							});
							setTimeout(function () {
								if (!_this.$outer.hasClass('lg-dragging') && _this.s.mode !== 'lg-slide') {
									_this.$outer.removeClass('lg-slide');
								}
							}, _this.s.speed + 100);
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.enableSwipe = function () {
							let _this = this;
							let startCoords = 0;
							let endCoords = 0;
							let isMoved = false;
							if (_this.s.enableSwipe && _this.isTouch && _this.doCss()) {
								_this.$slide.on('touchstart.lg', function (e) {
									if (!_this.$outer.hasClass('tsvg-elastic-lg-zoomed') && !_this.lgBusy) {
										e.preventDefault();
										_this.manageSwipeClass();
										startCoords = e.originalEvent.targetTouches[0].pageX;
									}
								});
								_this.$slide.on('touchmove.lg', function (e) {
									if (!_this.$outer.hasClass('tsvg-elastic-lg-zoomed')) {
										e.preventDefault();
										endCoords = e.originalEvent.targetTouches[0].pageX;
										_this.touchMove(startCoords, endCoords);
										isMoved = true;
									}
								});
								_this.$slide.on('touchend.lg', function () {
									if (!_this.$outer.hasClass('tsvg-elastic-lg-zoomed')) {
										if (isMoved) {
											isMoved = false;
											_this.touchEnd(endCoords - startCoords);
										}
										else {
											_this.$el.trigger('onSlideClick.lg');
										}
									}
								});
							}
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.enableDrag = function () {
							let _this = this;
							let startCoords = 0;
							let endCoords = 0;
							let isDraging = false;
							let isMoved = false;
							if (_this.s.enableDrag && !_this.isTouch && _this.doCss()) {
								_this.$slide.on('mousedown.lg', function (e) {
									if (!_this.$outer.hasClass('tsvg-elastic-lg-zoomed')) {
										if ($(e.target).hasClass('tsvg-elastic-lg-object') || $(e.target).hasClass('tsvg-elastic-lg-video-play')) {
											e.preventDefault();
											if (!_this.lgBusy) {
												_this.manageSwipeClass();
												startCoords = e.pageX;
												isDraging = true;
												_this.$outer.scrollLeft += 1;
												_this.$outer.scrollLeft -= 1;
												_this.$outer.removeClass('lg-grab').addClass('lg-grabbing');
												_this.$el.trigger('onDragstart.lg');
											}
										}
									}
								});
								$(window).on('mousemove.lg', function (e) {
									if (isDraging) {
										isMoved = true;
										endCoords = e.pageX;
										_this.touchMove(startCoords, endCoords);
										_this.$el.trigger('onDragmove.lg');
									}
								});
								$(window).on('mouseup.lg', function (e) {
									if (isMoved) {
										isMoved = false;
										_this.touchEnd(endCoords - startCoords);
										_this.$el.trigger('onDragend.lg');
									}
									else if ($(e.target).hasClass('tsvg-elastic-lg-object') || $(e.target).hasClass('tsvg-elastic-lg-video-play')) {
										_this.$el.trigger('onSlideClick.lg');
									}
									if (isDraging) {
										isDraging = false;
										_this.$outer.removeClass('lg-grabbing').addClass('lg-grab');
									}
								});
							}
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.manageSwipeClass = function () {
							let touchNext = this.index + 1;
							let touchPrev = this.index - 1;
							let length = this.$slide.length;
							if (this.s.loop) {
								if (this.index === 0) {
									touchPrev = length - 1;
								} else if (this.index === length - 1) {
									touchNext = 0;
								}
							}
							this.$slide.removeClass('tsvg-elastic-lg-next-slide tsvg-elastic-lg-prev-slide');
							if (touchPrev > -1) {
								this.$slide.eq(touchPrev).addClass('tsvg-elastic-lg-prev-slide');
							}
							this.$slide.eq(touchNext).addClass('tsvg-elastic-lg-next-slide');
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.mousewheel = function () {
							let _this = this;
							_this.$outer.on('mousewheel.lg', function (e) {
								if (!e.deltaY) {
									return;
								}
								if (e.deltaY > 0) {
									_this.goToPrevSlide();
								} else {
									_this.goToNextSlide();
								}
								e.preventDefault();
							});
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.closeGallery = function () {
							let _this = this,
							 	mousedown = false,
							 	closeIcon = '<i class="' + tsvgElasticCloseIcon<?php echo esc_attr( $tsvg_shortcode_id ); ?> + ' tsvg-elastic-lg-close tsvg-elastic-lg-close<?php echo esc_attr( $tsvg_shortcode_id ); ?> tsvg-elastic-lg-toolbar-icon"></i>';
							this.$outer.find('.tsvg-elastic-lg-toolbar<?php echo esc_attr( $tsvg_shortcode_id ); ?>').append(closeIcon);
							this.$outer.find('.tsvg-elastic-lg-close<?php echo esc_attr( $tsvg_shortcode_id ); ?>').on('click.lg', function () {
								_this.destroy();
							});
							if (_this.s.closable) {
								_this.$outer.on('mousedown.lg', function (e) {
									mousedown = $(e.target).is('.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>') || $(e.target).is('.tsvg-elastic-lg-item ') || $(e.target).is('.tsvg-elastic-lg-img-wrap');
								});
								_this.$outer.on('mouseup.lg', function (e) {
									if ($(e.target).is('.tsvg-elastic-lg-outer<?php echo esc_attr( $tsvg_shortcode_id ); ?>') || $(e.target).is('.tsvg-elastic-lg-item ') || $(e.target).is('.tsvg-elastic-lg-img-wrap') && mousedown) {
										if (!_this.$outer.hasClass('lg-dragging')) {
											_this.destroy();
										}
									}
								});
							}
						};
						tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.destroy = function (d) {
							jQuery('html').css({ 'scrollbar-width': '' });
							let _this = this;
							if (!d) {
								_this.$el.trigger('onBeforeClose.lg');
							}
							$(window).scrollTop(_this.prevScrollTop);
							if (d) {
								this.$items.off('click.lg click.lgcustom');
								$.removeData(_this.el, 'lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>');
							}
							this.$el.off('.lg.tm');
							$.each($.fn.lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>.modules, function (key) {
								if (_this.modules[key]) {
									_this.modules[key].destroy();
								}
							});
							this.lGalleryOn = false;
							clearTimeout(_this.hideBartimeout);
							this.hideBartimeout = false;
							$(window).off('.tsvg-elastic-lg');
							$('body').removeClass('tsvg-elastic-lg-on tsvg-elastic-lg-from-hash');
							if (_this.$outer) {
								_this.$outer.removeClass('tsvg-elastic-lg-visible');
							}
							$('.tsvg-elastic-lg-backdrop<?php echo esc_attr( $tsvg_shortcode_id ); ?>').removeClass('in');
							setTimeout(function () {
								if (_this.$outer) {
									_this.$outer.remove();
								}
								$('.tsvg-elastic-lg-backdrop<?php echo esc_attr( $tsvg_shortcode_id ); ?>').remove();
								if (!d) {
									_this.$el.trigger('onCloseAfter.lg');
								}
							}, _this.s.backdropDuration + 50);
						};
						$.fn.lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?> = function (options) {
							return this.each(function () {
								if (!$.data(this, 'lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>')) {
									$.data(this, 'lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>', new tsvgElasticPlugin<?php echo esc_attr( $tsvg_shortcode_id ); ?>(this, options));
								} else {
									try {
										$(this).data('lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>').init();
									} catch (err) {
										console.error('lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?> has not initiated properly');
									}
								}
							});
						};
						$.fn.lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>.modules = {};
						let tsvgElasticCheckAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-autoplay') == 'true',
							tsvgElasticVideoDefaults<?php echo esc_attr( $tsvg_shortcode_id ); ?> = {
								videoMaxWidth: '855px', youtubePlayerParams: true, vimeoPlayerParams: true, dailymotionPlayerParams: true, vkPlayerParams: true, videojs: true, videojsOptions: {}
							},
							tsvgElasticAutoplayDefaults<?php echo esc_attr( $tsvg_shortcode_id ); ?> = {
								autoplay: tsvgElasticCheckAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?>,
								pause: 5000,
								progressBar: true,
								fourceAutoplay: false,
								autoplayControls: true,
								appendAutoplayControlsTo: '.tsvg-elastic-lg-toolbar<?php echo esc_attr( $tsvg_shortcode_id ); ?>'
							},
							tsvgElasticAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?> = function (element) {
								this.core = $(element).data('lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>');
								this.$el = $(element);
								if (2 > this.core.$items.length) {
									return false;
								}
								this.core.s = $.extend({}, tsvgElasticAutoplayDefaults<?php echo esc_attr( $tsvg_shortcode_id ); ?>, this.core.s);
								this.interval = false;
								this.fromAuto = true;
								this.canceledOnTouch = false;
								this.fourceAutoplayTemp = this.core.s.fourceAutoplay;
								if (!this.core.doCss()) {
									this.core.s.progressBar = false;
								}
								this.init();
								return this;
							},
							tsvgElasticVideo<?php echo esc_attr( $tsvg_shortcode_id ); ?> = function (element) {
								this.core = $(element).data('lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>');
								this.$el = $(element);
								this.core.s = $.extend({}, tsvgElasticVideoDefaults<?php echo esc_attr( $tsvg_shortcode_id ); ?>, this.core.s);
								this.videoLoaded = true;
								this.init();
								return this;
							};
						tsvgElasticVideo<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.init = function () {
							let _this = this;
							_this.core.$el.on('hasVideo.lg.tm', function (event, index, src, html) {
								_this.core.$slide.eq(index).find('.tsvg-elastic-lg-video<?php echo esc_attr( $tsvg_shortcode_id ); ?>').append(_this.loadVideo(src, 'tsvg-elastic-lg-object', true, index, html));
								if (html) {
									if (_this.core.s.videojs) {
										try {
											videojs(_this.core.$slide.eq(index).find('.tsvg-elastic-lg-html5').get(0), _this.core.s.videojsOptions, function () {
												if (!_this.videoLoaded) {
													this.play();
												}
											});
										} catch (e) {
											console.error('Make sure you have included videojs');
										}
									}
									else {
										_this.core.$slide.eq(index).find('.tsvg-elastic-lg-html5').get(0).play();
									}
								}
							});
							_this.core.$el.on('onAferAppendSlide.lg.tm', function (event, index) {
								// _this.core.$slide.eq(index).find('.tsvg-elastic-lg-video-cont').css('max-width', _this.core.s.videoMaxWidth);
								_this.videoLoaded = false;
							});
							let loadOnClick = function ($el) {
								if ($el.find('.tsvg-elastic-lg-object').hasClass('tsvg-elastic-lg-has-poster') && $el.find('.tsvg-elastic-lg-object').is(':visible')) {
									if (!$el.hasClass('tsvg-elastic-lg-has-video')) {
										$el.addClass('tsvg-elastic-lg-video-playing tsvg-elastic-lg-has-video');
										let _src;
										let _html;
										let _loadVideo = function (_src, _html) {
											$el.find('.tsvg-elastic-lg-video').append(_this.loadVideo(_src, '', false, _this.core.index, _html));
											if (_html) {
												if (_this.core.s.videojs) {
													try {
														videojs(_this.core.$slide.eq(_this.core.index).find('.tsvg-elastic-lg-html5').get(0), _this.core.s.videojsOptions, function () {
															this.play();
														});
													} catch (e) {
														console.error('Make sure you have included videojs');
													}
												}
												else {
													_this.core.$slide.eq(_this.core.index).find('.tsvg-elastic-lg-html5').get(0).play();
												}
											}
										};
										_src = _this.core.$items.eq(_this.core.index).attr('href') || _this.core.$items.eq(_this.core.index).attr('data-src');
										_html = _this.core.$items.eq(_this.core.index).attr('data-html');
										_loadVideo(_src, _html);
										let $tempImg = $el.find('.tsvg-elastic-lg-object');
										$el.find('.tsvg-elastic-lg-video').append($tempImg);
										if (!$el.find('.tsvg-elastic-lg-video-object').hasClass('lg-html5')) {
											$el.removeClass('tsvg-elastic-lg-complete');
											$el.find('.tsvg-elastic-lg-video-object').on('load.lg error.lg', function () {
												$el.addClass('tsvg-elastic-lg-complete');
											});
										}
									}
									else {
										let youtubePlayer = $el.find('.tsvg-elastic-lg-youtube').get(0);
										let vimeoPlayer = $el.find('.tsvg-elastic-lg-vimeo').get(0);
										let dailymotionPlayer = $el.find('.tsvg-elastic-lg-dailymotion').get(0);
										let html5Player = $el.find('.tsvg-elastic-lg-html5').get(0);
										if (youtubePlayer) {
											youtubePlayer.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
										}
										else if (vimeoPlayer) {
											try {
												$f(vimeoPlayer).api('play');
											} catch (e) {
												console.error('Make sure you have included froogaloop2 js');
											}
										}
										else if (dailymotionPlayer) {
											dailymotionPlayer.contentWindow.postMessage('play', '*');
										}
										else if (html5Player) {
											if (_this.core.s.videojs) {
												try {
													videojs(html5Player).play();
												} catch (e) {
													console.error('Make sure you have included videojs');
												}
											}
											else {
												html5Player.play();
											}
										}
										$el.addClass('tsvg-elastic-lg-video-playing');
									}
								}
							};
							if (_this.core.doCss() && _this.core.$items.length > 1 && ((_this.core.s.enableSwipe && _this.core.isTouch) || (_this.core.s.enableDrag && !_this.core.isTouch))) {
								_this.core.$el.on('onSlideClick.lg.tm', function () {
									let $el = _this.core.$slide.eq(_this.core.index);
									loadOnClick($el);
								});
							}
							else {
								_this.core.$slide.on('click.lg', function () {
									loadOnClick($(this));
								});
							}
							_this.core.$el.on('onBeforeSlide.lg.tm', function (event, prevIndex, index) {
								let $videoSlide = _this.core.$slide.eq(prevIndex);
								let youtubePlayer = $videoSlide.find('.tsvg-elastic-lg-youtube').get(0);
								let vimeoPlayer = $videoSlide.find('.tsvg-elastic-lg-vimeo').get(0);
								let dailymotionPlayer = $videoSlide.find('.tsvg-elastic-lg-dailymotion').get(0);
								let vkPlayer = $videoSlide.find('.tsvg-elastic-lg-vk').get(0);
								let html5Player = $videoSlide.find('.tsvg-elastic-lg-html5').get(0);
								if (youtubePlayer || vimeoPlayer || dailymotionPlayer || vkPlayer) {
									$videoSlide.find('iframe').attr("src",$videoSlide.find('iframe').attr("src"))
								} else if (html5Player) {
									html5Player.pause();
									html5Player.currentTime = 0;
								}
								let _src;
								_src = _this.core.$items.eq(index).attr('href') || _this.core.$items.eq(index).attr('data-src');
								let _isVideo = _this.core.isVideo(_src, index) || {};
								if (_isVideo.youtube || _isVideo.vimeo || _isVideo.dailymotion || _isVideo.vk || _isVideo.wistia) {
									_this.core.$outer.addClass('tsvg-lg-hide-download');
								}
							});
							_this.core.$el.on('onAfterSlide.lg.tm', function (event, prevIndex) {
								_this.core.$slide.eq(prevIndex).removeClass('tsvg-elastic-lg-video-playing');
							});
						};
						tsvgElasticVideo<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.loadVideo = function (src, addClass, noposter, index, html) {
							let tsvg_autoplay = jQuery('.tsvg-main-content-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-autoplay');
							let video = '';
							let autoplay = 1;
							let a = '';
							let isVideo = this.core.isVideo(src, index) || {};
							if (noposter) {
								if (this.videoLoaded) {
									autoplay = 0;
								}
								else {
									autoplay = 1;
								}
							}
							if (isVideo.youtube) {
								a = tsvg_autoplay == "true" ? '?autoplay=1&mute=1' : "";
								video = '<iframe data-title="" class="tsvg-elastic-lg-video-object tsvg-elastic-lg-youtube ' + addClass + '" width="560" height="315" src="//www.youtube.com/embed/' + isVideo.youtube[1] + a + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"  allowfullscreen ></iframe>';
							} else if(src.indexOf('vimeo') != -1) {
								let vimeo_video = tsvg_autoplay == "true" ? src + '?autoplay=1&muted=1' : src;
								video = '<iframe data-title="" class="tsvg-elastic-lg-video-object tsvg-elastic-lg-vimeo ' + addClass + '" width="560" height="315"  src="' + vimeo_video + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"  allowFullScreen></iframe>';
							} else if(src.indexOf('rumble') != -1) {
								video = '<iframe data-title="" class="tsvg-elastic-lg-video-object tsvg-elastic-lg-vimeo ' + addClass + '" width="560" height="315"  src="' + src + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"  allowFullScreen  sandbox="allow-scripts" ></iframe>';
							} else if(isVideo.dailymotion) {
								a = '?wmode=opaque&autoplay=' + autoplay + '&api=postMessage';
								if (this.core.s.dailymotionPlayerParams) {
									a = a + '&' + $.param(this.core.s.dailymotionPlayerParams);
								}
								video = '<iframe data-title="" class="tsvg-elastic-lg-video-object tsvg-elastic-lg-dailymotion ' + addClass + '" width="560" height="315" src="//www.dailymotion.com/embed/video/' + isVideo.dailymotion[1] + a + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"  allowfullscreen></iframe>';
							} else if(isVideo.html5) {
								let fL = html.substring(0, 1);
								if (fL === '.' || fL === '#') {
									html = $(html).html();
								}
								video = html;
							} else if(isVideo.vk) {
								a = '&autoplay=' + autoplay;
								if (this.core.s.vkPlayerParams) {
									a = a + '&' + $.param(this.core.s.vkPlayerParams);
								}
							} else if(isVideo.wistia) {
								video = '<iframe data-title="" class="tsvg-elastic-lg-video-object tsvg-elastic-lg-youtube ' + addClass + '" width="560" height="315" src="' + isVideo.wistia + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"  allowfullscreen></iframe>';
							} else if(isVideo.mp4) {
								let autoplay_attr = tsvg_autoplay == "true" ? "autoplay" : "";
								video = '<video data-title="" class="videos tsvg-elastic-lg-html5" style="width: 100% !important;height: 100% !important;position: absolute;top: 0;left: 0;" controls ' + autoplay_attr + ' controlslist="nodownload" name="media"><source src="' + isVideo.mp4 + ' " type="video/mp4"></video>'
							}
							return video;
						};
						tsvgElasticVideo<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.destroy = function () {
							this.videoLoaded = false;
						};
						$.fn.lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>.modules.video = tsvgElasticVideo<?php echo esc_attr( $tsvg_shortcode_id ); ?>;
						tsvgElasticAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.init = function () {
							let _this = this;
							if (_this.core.s.autoplayControls) {
								_this.controls();
							}
							if (_this.core.s.progressBar) {
								_this.core.$outer.find('.tsvg-elastic-lg').append('<div class="tsvg-elastic-lg-progress-bar"><div class="tsvg-elastic-lg-progress"></div></div>');
							}
							_this.progress();
							if (_this.core.s.autoplay) {
								_this.startlAuto();
							}
							_this.$el.on('onDragstart.lg.tm touchstart.lg.tm', function () {
								if (_this.interval) {
									_this.cancelAuto();
									_this.canceledOnTouch = true;
								}
							});
							_this.$el.on('onDragend.lg.tm touchend.lg.tm onSlideClick.lg.tm', function () {
								if (!_this.interval && _this.canceledOnTouch) {
									_this.startlAuto();
									_this.canceledOnTouch = false;
								}
							});
						};
						tsvgElasticAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.progress = function () {
							let _this = this,
							 	_$progressBar,
							 	_$progress;
							_this.$el.on('onBeforeSlide.lg.tm', function () {
								if (_this.core.s.progressBar && _this.fromAuto) {
									_$progressBar = _this.core.$outer.find('.tsvg-elastic-lg-progress-bar');
									_$progress = _this.core.$outer.find('.tsvg-elastic-lg-progress');
									if (_this.interval) {
										_$progress.removeAttr('style');
										_$progressBar.removeClass('tsvg-elastic-lg-start');
										setTimeout(function () {
											_$progress.css('transition', 'width ' + (_this.core.s.speed + _this.core.s.pause) + 'ms ease 0s');
											_$progressBar.addClass('tsvg-elastic-lg-start');
										}, 20);
									}
								}
								if (!_this.fromAuto && !_this.core.s.fourceAutoplay) {
									if ($('.tsvg-elastic-lg-slideshow').hasClass('ts-vgallery-pause-circle-o')) {
										jQuery('.tsvg-elastic-lg-slideshow').addClass('ts-vgallery-play-circle-o');
										jQuery('.tsvg-elastic-lg-slideshow').removeClass('ts-vgallery-pause-circle-o');
									}
									_this.cancelAuto();
								}
								_this.fromAuto = false;
							});
						};
						tsvgElasticAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.controls = function () {
							tsvgElasticCheckAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').attr('data-tsvg-autoplay') == 'true';
							this.core.s.autoplay = tsvgElasticCheckAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?>;
							let _this = this,
								_html = '<i class="tsvg-elastic-lg-slideshow ts-vgallery ts-vgallery-play-circle-o tsvg-elastic-lg-toolbar-icon"></i>',
								x = 0;
							$(this.core.s.appendAutoplayControlsTo).append(_html);
							_this.core.$outer.find('.tsvg-elastic-lg-slideshow').on('click.lg', function () {
								if ($('.tsvg-elastic-lg-slideshow').hasClass('ts-vgallery-play-circle-o')) {
									x = 0;
								} else {
									x = 1;
								}
								x++;
								if (x % 2 == 1) {
									jQuery('.tsvg-elastic-lg-slideshow').removeClass('ts-vgallery-play-circle-o');
									jQuery('.tsvg-elastic-lg-slideshow').addClass('ts-vgallery-pause-circle-o');
								} else if ($('.tsvg-elastic-lg-slideshow').hasClass('ts-vgallery-pause-circle-o')) {
									jQuery('.tsvg-elastic-lg-slideshow').addClass('ts-vgallery-play-circle-o');
									jQuery('.tsvg-elastic-lg-slideshow').removeClass('ts-vgallery-pause-circle-o');
								}
								if ($(_this.core.$outer).hasClass('tsvg-elastic-lg-show-autoplay')) {
									_this.cancelAuto();
									_this.core.s.fourceAutoplay = false;
								} else {
									if (!_this.interval) {
										_this.startlAuto();
										_this.core.s.fourceAutoplay = _this.fourceAutoplayTemp;
									}
								}
							});
						};
						tsvgElasticAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.startlAuto = function () {
							let _this = this;
							_this.core.$outer.find('.tsvg-elastic-lg-progress').css('transition', 'width ' + (_this.core.s.speed + _this.core.s.pause) + 'ms ease 0s');
							_this.core.$outer.addClass('tsvg-elastic-lg-show-autoplay');
							_this.core.$outer.find('.tsvg-elastic-lg-progress-bar').addClass('tsvg-elastic-lg-start');
							jQuery('.tsvg-elastic-lg-slideshow').removeClass('ts-vgallery-play-circle-o');
							jQuery('.tsvg-elastic-lg-slideshow').addClass('ts-vgallery-pause-circle-o');
							_this.interval = setInterval(function () {
								if (_this.core.$items.length > _this.core.index + 1) {
									_this.core.index++;
								} else {
									_this.core.index = 0;
								}
								_this.fromAuto = true;
								_this.core.slide(_this.core.index, false, false);
							}, _this.core.s.speed + _this.core.s.pause);
						};
						tsvgElasticAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.cancelAuto = function () {
							clearInterval(this.interval);
							this.interval = false;
							this.core.$outer.find('.tsvg-elastic-lg-progress').removeAttr('style');
							this.core.$outer.removeClass('tsvg-elastic-lg-show-autoplay');
							this.core.$outer.find('.tsvg-elastic-lg-progress-bar').removeClass('tsvg-elastic-lg-start');
						};
						tsvgElasticAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?>.prototype.destroy = function () {
							this.cancelAuto();
							this.core.$outer.find('.tsvg-elastic-lg-progress-bar').remove();
						};
						$.fn.lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>.modules.autoplay = tsvgElasticAutoplay<?php echo esc_attr( $tsvg_shortcode_id ); ?>;
					})(jQuery, window, document);
					jQuery('.tsvg-section-<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>').fadeIn();
					tsvgElasticInstance<?php echo esc_attr( $tsvg_shortcode_id ); ?> = jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>();
					<?php if ( $tsvg_edit === 'true' ) { ?>
                        tsvgElasticResizeWidth = function () {
                            tsvgElasticDoneResizing<?php echo esc_attr( $tsvg_shortcode_id ); ?>();
                        };
						tsvgElasticForAdmin =  function() {
							if (tsvgElasticInit<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>) {
								jQuery('.tsvg-elastic-blocks-list-<?php echo esc_attr( $tsvg_shortcode_id ); ?>').lightGallery<?php echo esc_attr( $tsvg_shortcode_id ); ?>();
							}
						};
                    <?php } ?>
                }
                clearInterval(tsvgElasticInterval<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>);
                tsvgElasticInit<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = true;
            }
        },
        tsvgElasticInterval<?php echo esc_attr( $tsvg_js_shortcode_id ); ?> = setInterval(tsvgElasticCreate<?php echo esc_attr( $tsvg_js_shortcode_id ); ?>, 1000);
</script>