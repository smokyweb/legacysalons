<?php
/* 
 *************************************************************************************
 * @since version 1.1.1
 *	Provide the suggesting text for privacy page 
 *************************************************************************************
 */
if ( ! class_exists( 'LoftLoader_Pro_Privacy' ) ) {
	class LoftLoader_Pro_Privacy {
		public function __construct() {
			if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
				$this->privacy_for_post_like();
			}
		}
		private function privacy_for_post_like() {
			$content = sprintf(
				/* translators: 1 3 5 7 9: html tag start. 2 4 6 8 10: html tag end */
				esc_html__( 
					'When the "Once Per Session" feature of LoftLoader Pro is enabled on your site, as the site administrator, you may need to include the following information into your Privacy Policy for GDPR complaint:

%1$sWhat personal data we collect and why we collect it%2$s

%3$sCookies%4$s

The loading screen of this site is enabled "Play Once Per Session" feature. When you visit our site, the once_per_session_visited cookies will be saved in the browser on your computer. This cookie includes no personal data and simply indicates if you have visited this site.

These cookies are "session" cookies, and they will be deleted as soon as you close your web browser. So you will only see the loading screen once during the visit session.

These cookies begin with "loftloader_pro" and end with "_once_per_session_visited", as in the following examples:
%5$s
%7$s
"loftloader_pro_once_per_session_visited"
%8$s
%7$s
"loftloader_pro_homepage_once_per_session_visited"
%8$s
%6$s

And if the site is using LoftLoader Pro\'s "Any Page Extension" feature (to creating different loading screens for different pages), if the site administrator also enabled the "Once Per Session" feature on that page, then the page id cookies will be saved in your browser too. This cookie includes no personal data and simply indicates if you have visited this page. The page id cookies are also "session" cookies, and they will be deleted as soon as you close your web browser. These cookies are those beginning with "loftloader_pro_any_page_id_". 

To find out more about cookies, including how to see what cookies have been set and how to block and delete cookies, please visit %9$shttps://www.aboutcookies.org/%10$s.',
				'loftloader-pro' ),
				'<h2>',
				'</h2>',
				'<h3>',
				'</h3>',
				'<ul>',
				'</ul>',
				'<li>',
				'</li>',
				'<a href="https://www.aboutcookies.org/" target="_blank">',
				'</a>'
			);

			wp_add_privacy_policy_content(
				esc_html__( 'LoftLoader Pro', 'loftloader-pro' ),
				wpautop( $content, false )
			);
		}
	}
	function llp_init_GDPR_privacy() {
		new LoftLoader_Pro_Privacy();
	}
	add_action( 'admin_init', 'llp_init_GDPR_privacy' );
}