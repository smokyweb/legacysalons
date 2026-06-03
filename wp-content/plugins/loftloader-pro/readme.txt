=== LoftLoader Pro ===
Contributors: loftocean
Tags: customize, loading, loading screen, loader, page preloader, preload, preloader, preloader animation, preloader builder, preloader with image, website preloader, wordpress preloader
Author link: https://www.loftocean.com
Requires at least: 5.0
Tested up to: 6.6

== Copyright ==

LoftLoader bundles the following third-party resources:

WaitforImages
Copyright (c) 2011-2018 Alexander Dickson @alexdickson
Licensed under the MIT licenses.
Source http://alexanderdickson.com

Mobile Detect Library
Homepage: http://mobiledetect.net
License https://github.com/serbanghita/Mobile-Detect/blob/master/LICENSE.txt MIT License
Author: Serban Ghita <serbanghita@gmail.com>
Aauthor: Nick Ilyin <nick.ilyin@gmail.com>
Original author: Victor Stanciu <vic.stanciu@gmail.com>

Simple HTML Dom
Website: http://sourceforge.net/projects/simplehtmldom/
Licensed under The MIT License
Authors: S.C. Chen, John Schlick, Rus Carroll, logmanoriginal
Contributors: Yousuke Kumakura, Vadim Voituk, Antcs

== Changelog ==
= 2.5.0 =
* New: Display On - "Selected Post Types" - Included Pages
* New: Display On - "Selected Post Types" - Option to enable Once Per Session
* New: Progress - "Percentage" - Option to set progress percentage indicator margins
* New: Progress - "Percentage" - New Position Option: Absolute Position
* New: Progress - "Percentage" - 6 preset selectable absolute position options
* Fixed: Changed deprecated JavaScript event "unload" to "pagehide"
* Fixed: When smooth page transition feature is enabled and background opacity is less than 1, opacity is reduced when entering a new page

= 2.4.0 =
* New: Ending Animation - No Animation
* New: Smooth Page Transition - Option to manually set the percentage at which the progress ends when leaving the current page (when loader contains progress)
* New: Smooth Page Transition - Option to manually set how long to stay on the current page before leaving it (when loader contains progress)
* New: Smooth Page Transition - Option to show or hide the close button when leaving the current page
* New: Option to add specific parameters to disable the loader when these parameters are included in the URL
* New: Trigger Smooth Page Transition on specific buttons (BETA)
* Improved: Rearranged the structure of all option of Smooth Page Transition in the settings panel
* Improved: Once Per Session feature
* Fixed: When the loader is set to only appear on page transition, if the "Disable Page Scroll while Loading" feature is activated, the page cannot be scrolled even though the loader does not appear when the page is opened for the first time
* Fixed: Minor CSS Issues

= 2.3.3 =
* New: Smooth Page Transition - Reverse background animation direction when reappearing (works for: Slide to Left / Slide to Right / Slide Up / Slide Down)
* New: Added the Custom CSS box
* Improved: Ensured images in loading screen are available earlier by adding rel="preload" attribute value
* Improved: When "Once Per Session" is enabled, the loader no longer appears when opening the page in a new tab or new window
* Fixed: A minor conflict with Divi Theme (causing "Once Per Session" to not work)

= 2.3.2 =
* New: Option to enable Adaptive Height for the loading screen on mobile devices
* New: Gradient background color for the progress bar
* Updated: Google Fonts List
* Fixed: CSS issues for ending animations (Fade, Slide Up)

= 2.3.1 =
* Fixed: JS error when the visitor disables browser cookie on the device (cookie-related/once-per-session feature will not work at this time, but the loader can display and end)
* Improved: When Cloudflare Rocket Loader feature is enabled, the "Once Per Session" feature can work 
* Improved: Added width and height attributes to image elements
* Improved: Removed most non-composited animations

= 2.3.0 =
* New: Background Section - new Ending Animation: Slide Down
* New: Added "Additional Display Option" for Smooth Page Transition 
* New: For developers - added API for Smooth Page Transition. Then developers can use Smooth Page Transition on custom HTML element
* Improved: Random Message content issue when Smooth Page Transition is enabled
* Improved: Option to adjust the order of loading JavaScript
* Changed: Minimum Load Time - The upper limit of the value is changed to 30 seconds
* Fixed: Display On - Handpick request error
* Fixed: Minor CSS issues on Chrome browser on iOS

= 2.2.3 =
* Improved: Compatibility with WordPress 5.5

= 2.2.2 =
* New: Display On - "Sitewide" - New option to manually exclude pages/posts
* New: Display On - "All Pages" - New option to manually exclude pages
* New: Loader - New option to use custom HTML loader
* Fixed: Conflicts between "Translate Press" and the "Smooth Page Transition" feature of LoftLoader Pro
* Fixed: Minor CSS issues

= 2.2.1 =
* Fixed: Maximum Load Time setting issue for initial installation

= 2.2.0 =
* New: Option to set Maximum Load Time
* New: Option to enable detecting autoplay video (video source: YouTube / Vimeo / Media Library)
* New: When background is set to use background image, added a new option to upload a separate background image for mobile devices
* Improved: Hide loader when editing content with a third-party page builder plugin
* Improved: Hide loader when opening theme customizer
* Improved: Compatibility with Swift Performance plugin
* Improved: Prevent Google Fonts from loading when there is no text in the loading screen
* Improved: “Detect Elements” - “Detect All Elements” option description changed to “Detect when the browser stops loading” to make the description more accurate
* Improved: Added a data attribute when the loader is created with Any Page Extension feature (for better debugging)
* Improved: For browsers that support “Back/Forward Cache”, hide the loader when going back/forward by clicking the “Back” or “Forward” button of the browser
* Improved: LoftLoader Pro settings Panel UI
* Fixed: Google Fonts load incorrectly when font name is not a single word
* Fixed: Any Page Extension did not work on WooCommerce Shop page
* Fixed: When “Once Per Session” is enabled, hide the loader when clicking on the “Back” button of the browser
* Fixed: Potential layout issues for split background image
* Fixed: Potential conflicts with Gutenberg when Any Page Extension feature is enabled
* Fixed: Minor CSS issues

= 2.1.1 =
* Improved: Prevent preloader styles being affected by Lazy Loading feature
* Fixed: Some JavaScripts are delayed due to conflicts between Once Per Session feature and CloudFlare
* Fixed: CDN issue caused by code changes made in the previous update (to resolve conflicts with ConveyThis plugin)
* Fixed: Minor style issues in LoftLoader Settings panel for WordPress 5.3
* Updated: Plugin requirements information

= 2.1.0 =
* New: Option to show all elements for Smooth Page Transition feature
* New: Option to disable the close button
* Improved: Once Per Session now works on cached pages
* Improved: Display On > Handpick - Improved the design and user experience of this option
* Improved: The minimum load time setting doesn’t work because the theme or other plugins also use the “loaded” class. So we added a new class “loftloader-loaded” to < body > when page is loaded
* Improved: Rearranged options in Advanced section
* Fixed: Media Library cannot open on LoftLoader settings panel
* Fixed: Smooth Page Transition feature doesn’t work on internal links that are added dynamically by JavaScript after page is loaded
* Fixed: Code conflicts between Max Mega Menu and LoftLoader
* Fixed: Compatibility issue between ConveyThis and LoftLoader

= 2.0.1 =
* Improved: Improved plugin code in accordance with the latest Envato WordPress plugin requirements
* Fixed: PHP Conflict with some maintenance plugins
* Fixed: AJAX conflict with other plugins

= 2.0 =
* New: Any Page Extension can be enabled for blog posts and custom post types (portfolios, products, etc.)
* New: Option to detect different elements (All Elements / Images / Videos / Images & Videos)
* New: URL parameter for disabling the preloader
* New: Added the config file for Polylang
* Improved: Animation for progress indicators
* Improved: Compatibility with other third-party plugins
* Improved: Improved plugin code in accordance with the latest Envato WordPress plugin requirements
* Improved: Introducing a new library to improve the accuracy of Mobile Detection
* Fixed: Media Library issue due to conflicts with other plugins
* Fixed: Unable to trigger the Gutenberg Update button when adding Any Page Extension shortcode
* Fixed: Minor CSS issues

= 1.2.4 =
* New: Background Section - 2 new Ending Animations: Slide Left, Slide Right
* Changed: The minimum PHP version requirement changed from PHP 5.4 to PHP 5.3
* Fixed: PHP error message displayed in the dashboard

= 1.2.3 =
* New: Random Message feature - enter multiple messages and display a random one when loading the page
* New: Feature to detect user website configuration and display warning messages if WordPress/PHP is too old
* Improved: Any Page Extension meta boxes redesigned to fit in Gutenberg Sidebar
* Fixed: PHP cache conflicts with some WordPress Themes/Plugins

= 1.2.2 =
* New: Option to adjust Line Height for Message
* Fixed: JS/PHP conflicts with some WordPress Themes/Plugins
* Improved: Message input field supports simple HTML markups
* Improved: Option to use site default font for message text and progress percentage number

= 1.2.1 =
* Fixed: Entire website is down after activating the plugin if the server PHP version is older than 5.4
* Improved: Added notifications when outdated PHP versions detected (older than PHP 5.4)
* New: For developers - after the loading process is completed, trigger a JavaScript event “loftloaderprodone” on DOM object “document”. Then developers can bind their own code to this event.

= 1.2 =
* New: Option to choose entrance animation (None / Fade In / Slide Up) for inner elements such as the loader, progress indicator and message.
* New: Option to choose exit animation (None / Slide Up) for inner elements such as the loader, progress indicator and message.
* Fixed: The issue of inserting HTML code into robots.txt

= 1.1.9 =
* New: Option to change custom loader image max width for responsive design
* Improved: LoftLoader Customizer panel independence
* Fixed: JS conflicts with Smart Slider 3 Pro version
* Fixed: Progress bar CSS issue under Chrome v67.x

= 1.1.8 =
* New: Option to make the preloader spin counterclockwise with Custom Image Rotating loader
* Fixed: Image moves while loading with Custom Image Loading loader

= 1.1.7 =
* Fixed: Cookies conflict with cache plugins when "Once Per Session" feature is not enabled

= 1.1.6 =
* New: Background Section - Background Image can choose "cover" or "contain" as the full background image size
* New: Option to disable page scrolling while loading
* Improved: Rearranged options in More section
* Improved: Compatibility with WordPress 4.9.6 - added suggesting text for Privacy Policy (GDPR tools introduced in WordPress 4.9.6)
* Improved: Use cookies instead of sessions for "Once Per Session" feature
* Fixed: Added CSS for screen reader text in this plugin

= 1.1.5 =
* New: Background Section - 2 new Ending Animations: Split Diagonally - Vertically, Split Diagonally - Horizontally
* New: Loader Section - 1 new Loader Animation: Incomplete Ring
* New: Loader Section - Custom Image Loading Vertically - Animation can goes from Top to Bottom
* New: More Section - when "Smooth Page Transition" feature is enabled, user can exclude specific links so that the feature will be disabled when clicking on those links
* New: More Section - option to "Show Close Button after x seconds"
* Fixed: The issues of "Once Per Session" features due to WP Engine’s cache system ignores PHP Session
* Fixed: Issue when saving styles as an external CSS file
* Fixed: Minor CSS issues

= 1.1.4 =
* Fixed: Settings Panel UI compatibility issues with WordPress v4.9

= 1.1.3 =
* New: Any Page Extension - Added an option to display the preloader (created by shortcode) on the page only once during a visitor session
* Fixed: preg_replace php function issue

= 1.1.2 =
* Fixed: Conflict with plugin "Essential Grid" - affected Smooth Page Transition feature
* Fixed: Removed empty <img> tag for loader "Drawing Frame"
* Fixed: Split background image issue on FireFox
* Fixed: Other minor CSS issues
* Improved: Hide LoftLoader when JavaScript is disabled

= 1.1.1 =
* New: Display On - Selected Post Types
* Fixed: Minimal Load Time - Decimal separator (dot) being converted to comma for some locale settings
* Fixed: Back/forward button issue for Safari when Smooth Page Transition feature enabled

= 1.1.0 =
* Improved: LoftLoader Customizer panel independence (so that it will not be affected by theme or other plugins)
* New: Background Section - 2 new Ending Animations: Split & Reveal Vertically, Split & Reveal Horizontally
* New: Loader Section - 2 new Loader Animations: Beating, Custom Image Fading
* New: Loader Section - A new option for Crossing Circles: Blend Mode None
* New: Progress Section - A new Progress Animation: Bar + Number.
* Fixed: Minor bugs of Page smooth transition for IE11 - a tag with href "#" only
* Fixed: Minor bugs of Page smooth transition with WooCommerce - when "Enable AJAX add to cart buttons on archives"
* Fixed: Other CSS issues

= 1.0.10 =
* Fixed: AJAX keep alive error when switch between login and logout user
* New: Two methods to update session state for once per session and homepage + once per session mode
* New: New customize control for AJAX interval

= 1.0.9 =
* Fixed: Page smooth transition a with href "#" only

= 1.0.8 =
* New: Display On - Homepage only + Once per session

= 1.0.7 =
* New: Background Section - Background Image
* New: Advanced Section - Save customize styles as inline styles in <header> or as an external .css file.
* New: "Default" font option in Google Font dropdown list
* Fixed: Minor bugs of Smooth Page Transition feature in Safari
* Changed: Removed inline JS, changed to HTML5 data attributes

= 1.0.6 =
* New: More Section - Smooth Page Transition
* New: Display On - Once per session
* New: Display On - "Sitewide - Selected types"
* New: Advanced - Enable Any Page Extension: let user export loader shortcodes and add it to any page, so display different loaders on different pages.
* Improved: Add CSS to prevent site content from being hidden by other plugins before their code get fully loaded.
* Fixed: Minor bugs on settings panel for WordPress 4.7.x

= 1.0.5 =
* New: Background Section - A new Ending Animation: Shrink & Fade
* New: Loader Section - A new option for Custom Image Rotating: Speed Curve
* New: Loader Section - More options for Drawing Frame: Frame Size & Border Width
* New: Progress Section - More font options for Percentage: Google Fonts, Font Weight and Letter Spacing.
* Updated: Google Font list

= 1.0.4 =
* New: Message Section - Message Position
* New: Message Section - More font options: Google Fonts, font weight and letter spacing.
* Fixed: Minor bugs of the loader Petals
* Improved: Added prefix to main classes to prevent styles from being affected by theme or other plugins.

= 1.0.3 =
* New: Background Section - Gradient Background
* New: Loader Section - Custom Static Image
* New: More Section - Devices
* Fixed: Backend layout issues with Divi Theme
* Fixed: Minor bugs of the loader Wave/Crossing Circles
* Improved: Load Time code
* Improved: Backend panel code
* Improved: Minified JS and CSS
* Changes: Remove width limitation of custom image (was 200px)
* Changes: Increase the z-index of the loading screen

= 1.0.2 =
* New: More section - Minimum Load Time

= 1.0.1 =
* New: Handpick for Display On section.
* New: Custom Welcome Message
* Fix: Customizer minor bug

= 1.0.0 =
* Initial Public Release
