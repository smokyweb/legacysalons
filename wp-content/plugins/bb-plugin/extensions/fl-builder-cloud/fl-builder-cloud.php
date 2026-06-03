<?php

if ( defined( 'FL_ASSISTANT_VERSION' ) ) {
	return;
}

define( 'FL_BUILDER_CLOUD_DIR', FL_BUILDER_DIR . 'extensions/fl-builder-cloud/' );
define( 'FL_BUILDER_CLOUD_URL', FLBuilder::plugin_url() . 'extensions/fl-builder-cloud/' );

if ( file_exists( FL_BUILDER_CLOUD_DIR . 'assistant/fl-assistant.php' ) ) {
	define( 'FL_ASSISTANT_BB_EXTENSION', true );
	require_once FL_BUILDER_CLOUD_DIR . 'assistant/fl-assistant.php';
}
