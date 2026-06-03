<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

$countries = array(
	array(
		'name' => __( 'Afghanistan', 'testimonial-pro' ),
		'flag' => '🇦🇫',
	),
	array(
		'name' => __( 'Albania', 'testimonial-pro' ),
		'flag' => '🇦🇱',
	),
	array(
		'name' => __( 'Algeria', 'testimonial-pro' ),
		'flag' => '🇩🇿',
	),
	array(
		'name' => __( 'American Samoa', 'testimonial-pro' ),
		'flag' => '🇦🇸',
	),
	array(
		'name' => __( 'Andorra', 'testimonial-pro' ),
		'flag' => '🇦🇩',
	),
	array(
		'name' => __( 'Angola', 'testimonial-pro' ),
		'flag' => '🇦🇴',
	),
	array(
		'name' => __( 'Anguilla', 'testimonial-pro' ),
		'flag' => '🇦🇮',
	),
	array(
		'name' => __( 'Antigua and Barbuda', 'testimonial-pro' ),
		'flag' => '🇦🇬',
	),
	array(
		'name' => __( 'Argentina', 'testimonial-pro' ),
		'flag' => '🇦🇷',
	),
	array(
		'name' => __( 'Armenia', 'testimonial-pro' ),
		'flag' => '🇦🇲',
	),
	array(
		'name' => __( 'Aruba', 'testimonial-pro' ),
		'flag' => '🇦🇼',
	),
	array(
		'name' => __( 'Australia', 'testimonial-pro' ),
		'flag' => '🇦🇺',
	),
	array(
		'name' => __( 'Austria', 'testimonial-pro' ),
		'flag' => '🇦🇹',
	),
	array(
		'name' => __( 'Azerbaijan', 'testimonial-pro' ),
		'flag' => '🇦🇿',
	),
	array(
		'name' => __( 'Bahamas', 'testimonial-pro' ),
		'flag' => '🇧🇸',
	),
	array(
		'name' => __( 'Bahrain', 'testimonial-pro' ),
		'flag' => '🇧🇭',
	),
	array(
		'name' => __( 'Bangladesh', 'testimonial-pro' ),
		'flag' => '🇧🇩',
	),
	array(
		'name' => __( 'Barbados', 'testimonial-pro' ),
		'flag' => '🇧🇧',
	),
	array(
		'name' => __( 'Belarus', 'testimonial-pro' ),
		'flag' => '🇧🇾',
	),
	array(
		'name' => __( 'Belgium', 'testimonial-pro' ),
		'flag' => '🇧🇪',
	),
	array(
		'name' => __( 'Belize', 'testimonial-pro' ),
		'flag' => '🇧🇿',
	),
	array(
		'name' => __( 'Benin', 'testimonial-pro' ),
		'flag' => '🇧🇯',
	),
	array(
		'name' => __( 'Bermuda', 'testimonial-pro' ),
		'flag' => '🇧🇲',
	),
	array(
		'name' => __( 'Bhutan', 'testimonial-pro' ),
		'flag' => '🇧🇹',
	),
	array(
		'name' => __( 'Bolivia', 'testimonial-pro' ),
		'flag' => '🇧🇴',
	),
	array(
		'name' => __( 'Bosnia and Herzegovina', 'testimonial-pro' ),
		'flag' => '🇧🇦',
	),
	array(
		'name' => __( 'Botswana', 'testimonial-pro' ),
		'flag' => '🇧🇼',
	),
	array(
		'name' => __( 'Brazil', 'testimonial-pro' ),
		'flag' => '🇧🇷',
	),
	array(
		'name' => __( 'British Virgin Islands', 'testimonial-pro' ),
		'flag' => '🇻🇬',
	),
	array(
		'name' => __( 'Brunei', 'testimonial-pro' ),
		'flag' => '🇧🇳',
	),
	array(
		'name' => __( 'Bulgaria', 'testimonial-pro' ),
		'flag' => '🇧🇬',
	),
	array(
		'name' => __( 'Burkina Faso', 'testimonial-pro' ),
		'flag' => '🇧🇫',
	),
	array(
		'name' => __( 'Burundi', 'testimonial-pro' ),
		'flag' => '🇧🇮',
	),
	array(
		'name' => __( 'Cambodia', 'testimonial-pro' ),
		'flag' => '🇰🇭',
	),
	array(
		'name' => __( 'Cameroon', 'testimonial-pro' ),
		'flag' => '🇨🇲',
	),
	array(
		'name' => __( 'Canada', 'testimonial-pro' ),
		'flag' => '🇨🇦',
	),
	array(
		'name' => __( 'Cape Verde', 'testimonial-pro' ),
		'flag' => '🇨🇻',
	),
	array(
		'name' => __( 'Cayman Islands', 'testimonial-pro' ),
		'flag' => '🇰🇾',
	),
	array(
		'name' => __( 'Central African Republic', 'testimonial-pro' ),
		'flag' => '🇨🇫',
	),
	array(
		'name' => __( 'Chad', 'testimonial-pro' ),
		'flag' => '🇹🇩',
	),
	array(
		'name' => __( 'Chile', 'testimonial-pro' ),
		'flag' => '🇨🇱',
	),
	array(
		'name' => __( 'China', 'testimonial-pro' ),
		'flag' => '🇨🇳',
	),
	array(
		'name' => __( 'Colombia', 'testimonial-pro' ),
		'flag' => '🇨🇴',
	),
	array(
		'name' => __( 'Comoros', 'testimonial-pro' ),
		'flag' => '🇰🇲',
	),
	array(
		'name' => __( 'Cook Islands', 'testimonial-pro' ),
		'flag' => '🇨🇰',
	),
	array(
		'name' => __( 'Costa Rica', 'testimonial-pro' ),
		'flag' => '🇨🇷',
	),
	array(
		'name' => __( 'Croatia', 'testimonial-pro' ),
		'flag' => '🇭🇷',
	),
	array(
		'name' => __( 'Cuba', 'testimonial-pro' ),
		'flag' => '🇨🇺',
	),
	array(
		'name' => __( 'Curaçao', 'testimonial-pro' ),
		'flag' => '🇨🇼',
	),
	array(
		'name' => __( 'Cyprus', 'testimonial-pro' ),
		'flag' => '🇨🇾',
	),
	array(
		'name' => __( 'Czech Republic', 'testimonial-pro' ),
		'flag' => '🇨🇿',
	),
	array(
		'name' => __( 'Democratic Republic of Congo', 'testimonial-pro' ),
		'flag' => '🇨🇩',
	),
	array(
		'name' => __( 'Denmark', 'testimonial-pro' ),
		'flag' => '🇩🇰',
	),
	array(
		'name' => __( 'Djibouti', 'testimonial-pro' ),
		'flag' => '🇩🇯',
	),
	array(
		'name' => __( 'Dominica', 'testimonial-pro' ),
		'flag' => '🇩🇲',
	),
	array(
		'name' => __( 'Dominican Republic', 'testimonial-pro' ),
		'flag' => '🇩🇴',
	),
	array(
		'name' => __( 'East Timor', 'testimonial-pro' ),
		'flag' => '🇹🇱',
	),
	array(
		'name' => __( 'Ecuador', 'testimonial-pro' ),
		'flag' => '🇪🇨',
	),
	array(
		'name' => __( 'Egypt', 'testimonial-pro' ),
		'flag' => '🇪🇬',
	),
	array(
		'name' => __( 'El Salvador', 'testimonial-pro' ),
		'flag' => '🇸🇻',
	),
	array(
		'name' => __( 'Equatorial Guinea', 'testimonial-pro' ),
		'flag' => '🇬🇶',
	),
	array(
		'name' => __( 'Eritrea', 'testimonial-pro' ),
		'flag' => '🇪🇷',
	),
	array(
		'name' => __( 'Estonia', 'testimonial-pro' ),
		'flag' => '🇪🇪',
	),
	array(
		'name' => __( 'Eswatini', 'testimonial-pro' ),
		'flag' => '🇸🇿',
	),
	array(
		'name' => __( 'Ethiopia', 'testimonial-pro' ),
		'flag' => '🇪🇹',
	),
	array(
		'name' => __( 'Falkland Islands', 'testimonial-pro' ),
		'flag' => '🇫🇰',
	),
	array(
		'name' => __( 'Faroe Islands', 'testimonial-pro' ),
		'flag' => '🇫🇴',
	),
	array(
		'name' => __( 'Fiji', 'testimonial-pro' ),
		'flag' => '🇫🇯',
	),
	array(
		'name' => __( 'Finland', 'testimonial-pro' ),
		'flag' => '🇫🇮',
	),
	array(
		'name' => __( 'France', 'testimonial-pro' ),
		'flag' => '🇫🇷',
	),
	array(
		'name' => __( 'French Guiana', 'testimonial-pro' ),
		'flag' => '🇬🇫',
	),
	array(
		'name' => __( 'French Polynesia', 'testimonial-pro' ),
		'flag' => '🇵🇫',
	),
	array(
		'name' => __( 'French Southern and Antarctic Lands', 'testimonial-pro' ),
		'flag' => '🇹🇫',
	),
	array(
		'name' => __( 'Gabon', 'testimonial-pro' ),
		'flag' => '🇬🇦',
	),
	array(
		'name' => __( 'Gambia', 'testimonial-pro' ),
		'flag' => '🇬🇲',
	),
	array(
		'name' => __( 'Georgia', 'testimonial-pro' ),
		'flag' => '🇬🇪',
	),
	array(
		'name' => __( 'Germany', 'testimonial-pro' ),
		'flag' => '🇩🇪',
	),
	array(
		'name' => __( 'Ghana', 'testimonial-pro' ),
		'flag' => '🇬🇭',
	),
	array(
		'name' => __( 'Gibraltar', 'testimonial-pro' ),
		'flag' => '🇬🇮',
	),
	array(
		'name' => __( 'Greece', 'testimonial-pro' ),
		'flag' => '🇬🇷',
	),
	array(
		'name' => __( 'Greenland', 'testimonial-pro' ),
		'flag' => '🇬🇱',
	),
	array(
		'name' => __( 'Grenada', 'testimonial-pro' ),
		'flag' => '🇬🇩',
	),
	array(
		'name' => __( 'Guadeloupe', 'testimonial-pro' ),
		'flag' => '🇬🇵',
	),
	array(
		'name' => __( 'Guam', 'testimonial-pro' ),
		'flag' => '🇬🇺',
	),
	array(
		'name' => __( 'Guatemala', 'testimonial-pro' ),
		'flag' => '🇬🇹',
	),
	array(
		'name' => __( 'Guernsey', 'testimonial-pro' ),
		'flag' => '🇬🇬',
	),
	array(
		'name' => __( 'Guinea', 'testimonial-pro' ),
		'flag' => '🇲🇱',
	),
	array(
		'name' => __( 'Guinea-Bissau', 'testimonial-pro' ),
		'flag' => '🇬🇲',
	),
	array(
		'name' => __( 'Guyana', 'testimonial-pro' ),
		'flag' => '🇬🇾',
	),
	array(
		'name' => __( 'Haiti', 'testimonial-pro' ),
		'flag' => '🇭🇹',
	),
	array(
		'name' => __( 'Honduras', 'testimonial-pro' ),
		'flag' => '🇭🇳',
	),
	array(
		'name' => __( 'Hong Kong', 'testimonial-pro' ),
		'flag' => '🇭🇰',
	),
	array(
		'name' => __( 'Hungary', 'testimonial-pro' ),
		'flag' => '🇭🇺',
	),
	array(
		'name' => __( 'Iceland', 'testimonial-pro' ),
		'flag' => '🇮🇸',
	),
	array(
		'name' => __( 'India', 'testimonial-pro' ),
		'flag' => '🇮🇳',
	),
	array(
		'name' => __( 'Indonesia', 'testimonial-pro' ),
		'flag' => '🇮🇩',
	),
	array(
		'name' => __( 'Iran', 'testimonial-pro' ),
		'flag' => '🇮🇷',
	),
	array(
		'name' => __( 'Iraq', 'testimonial-pro' ),
		'flag' => '🇮🇶',
	),
	array(
		'name' => __( 'Ireland', 'testimonial-pro' ),
		'flag' => '🇮🇪',
	),
	array(
		'name' => __( 'Isle of Man', 'testimonial-pro' ),
		'flag' => '🇮🇲',
	),
	array(
		'name' => __( 'Israel', 'testimonial-pro' ),
		'flag' => '🇮🇱',
	),
	array(
		'name' => __( 'Italy', 'testimonial-pro' ),
		'flag' => '🇮🇹',
	),
	array(
		'name' => __( 'Jamaica', 'testimonial-pro' ),
		'flag' => '🇯🇲',
	),
	array(
		'name' => __( 'Jersey', 'testimonial-pro' ),
		'flag' => '🇯🇪',
	),
	array(
		'name' => __( 'Jordan', 'testimonial-pro' ),
		'flag' => '🇯🇴',
	),
	array(
		'name' => __( 'Kazakhstan', 'testimonial-pro' ),
		'flag' => '🇰🇿',
	),
	array(
		'name' => __( 'Kenya', 'testimonial-pro' ),
		'flag' => '🇰🇪',
	),
	array(
		'name' => __( 'Kiribati', 'testimonial-pro' ),
		'flag' => '🇰🇮',
	),
	array(
		'name' => __( 'Kuwait', 'testimonial-pro' ),
		'flag' => '🇰🇼',
	),
	array(
		'name' => __( 'Kyrgyzstan', 'testimonial-pro' ),
		'flag' => '🇰🇬',
	),
	array(
		'name' => __( 'Lao People\'s Democratic Republic', 'testimonial-pro' ),
		'flag' => '🇱🇦',
	),
	array(
		'name' => __( 'Latvia', 'testimonial-pro' ),
		'flag' => '🇱🇻',
	),
	array(
		'name' => __( 'Lebanon', 'testimonial-pro' ),
		'flag' => '🇱🇧',
	),
	array(
		'name' => __( 'Lesotho', 'testimonial-pro' ),
		'flag' => '🇱🇸',
	),
	array(
		'name' => __( 'Liberia', 'testimonial-pro' ),
		'flag' => '🇱🇸',
	),
	array(
		'name' => __( 'Libya', 'testimonial-pro' ),
		'flag' => '🇱🇾',
	),
	array(
		'name' => __( 'Liechtenstein', 'testimonial-pro' ),
		'flag' => '🇱🇮',
	),
	array(
		'name' => __( 'Lithuania', 'testimonial-pro' ),
		'flag' => '🇱🇹',
	),
	array(
		'name' => __( 'Luxembourg', 'testimonial-pro' ),
		'flag' => '🇱🇺',
	),
	array(
		'name' => __( 'Madagascar', 'testimonial-pro' ),
		'flag' => '🇲🇬',
	),
	array(
		'name' => __( 'Malawi', 'testimonial-pro' ),
		'flag' => '🇲🇼',
	),
	array(
		'name' => __( 'Malaysia', 'testimonial-pro' ),
		'flag' => '🇲🇾',
	),
	array(
		'name' => __( 'Maldives', 'testimonial-pro' ),
		'flag' => '🇲🇻',
	),
	array(
		'name' => __( 'Mali', 'testimonial-pro' ),
		'flag' => '🇲🇱',
	),
	array(
		'name' => __( 'Malta', 'testimonial-pro' ),
		'flag' => '🇲🇹',
	),
	array(
		'name' => __( 'Marshall Islands', 'testimonial-pro' ),
		'flag' => '🇲🇭',
	),
	array(
		'name' => __( 'Mauritania', 'testimonial-pro' ),
		'flag' => '🇲🇷',
	),
	array(
		'name' => __( 'Mauritius', 'testimonial-pro' ),
		'flag' => '🇲🇺',
	),
	array(
		'name' => __( 'Mayotte', 'testimonial-pro' ),
		'flag' => '🇲🇶',
	),
	array(
		'name' => __( 'Mexico', 'testimonial-pro' ),
		'flag' => '🇲🇽',
	),
	array(
		'name' => __( 'Micronesia', 'testimonial-pro' ),
		'flag' => '🇲🇵',
	),
	array(
		'name' => __( 'Moldova', 'testimonial-pro' ),
		'flag' => '🇲🇩',
	),
	array(
		'name' => __( 'Monaco', 'testimonial-pro' ),
		'flag' => '🇲🇨',
	),
	array(
		'name' => __( 'Mongolia', 'testimonial-pro' ),
		'flag' => '🇲🇳',
	),
	array(
		'name' => __( 'Montenegro', 'testimonial-pro' ),
		'flag' => '🇲🇪',
	),
	array(
		'name' => __( 'Montserrat', 'testimonial-pro' ),
		'flag' => '🇲🇸',
	),
	array(
		'name' => __( 'Morocco', 'testimonial-pro' ),
		'flag' => '🇲🇦',
	),
	array(
		'name' => __( 'Mozambique', 'testimonial-pro' ),
		'flag' => '🇲🇿',
	),
	array(
		'name' => __( 'Myanmar', 'testimonial-pro' ),
		'flag' => '🇲🇲',
	),
	array(
		'name' => __( 'Namibia', 'testimonial-pro' ),
		'flag' => '🇳🇦',
	),
	array(
		'name' => __( 'Nauru', 'testimonial-pro' ),
		'flag' => '🇳🇷',
	),
	array(
		'name' => __( 'Nepal', 'testimonial-pro' ),
		'flag' => '🇳🇵',
	),
	array(
		'name' => __( 'Netherlands', 'testimonial-pro' ),
		'flag' => '🇳🇱',
	),
	array(
		'name' => __( 'New Caledonia', 'testimonial-pro' ),
		'flag' => '🇳🇨',
	),
	array(
		'name' => __( 'New Zealand', 'testimonial-pro' ),
		'flag' => '🇳🇿',
	),
	array(
		'name' => __( 'Nicaragua', 'testimonial-pro' ),
		'flag' => '🇳🇮',
	),
	array(
		'name' => __( 'Niger', 'testimonial-pro' ),
		'flag' => '🇳🇪',
	),
	array(
		'name' => __( 'Nigeria', 'testimonial-pro' ),
		'flag' => '🇳🇬',
	),
	array(
		'name' => __( 'Niue', 'testimonial-pro' ),
		'flag' => '🇳🇺',
	),
	array(
		'name' => __( 'Norfolk Island', 'testimonial-pro' ),
		'flag' => '🇳🇫',
	),
	array(
		'name' => __( 'North Korea', 'testimonial-pro' ),
		'flag' => '🇰🇵',
	),
	array(
		'name' => __( 'North Macedonia', 'testimonial-pro' ),
		'flag' => '🇲🇰',
	),
	array(
		'name' => __( 'Northern Mariana Islands', 'testimonial-pro' ),
		'flag' => '🇲🇵',
	),
	array(
		'name' => __( 'Norway', 'testimonial-pro' ),
		'flag' => '🇳🇴',
	),
	array(
		'name' => __( 'Oman', 'testimonial-pro' ),
		'flag' => '🇴🇲',
	),
	array(
		'name' => __( 'Pakistan', 'testimonial-pro' ),
		'flag' => '🇵🇰',
	),
	array(
		'name' => __( 'Palau', 'testimonial-pro' ),
		'flag' => '🇵🇼',
	),
	array(
		'name' => __( 'Panama', 'testimonial-pro' ),
		'flag' => '🇵🇦',
	),
	array(
		'name' => __( 'Papua New Guinea', 'testimonial-pro' ),
		'flag' => '🇵🇬',
	),
	array(
		'name' => __( 'Paraguay', 'testimonial-pro' ),
		'flag' => '🇵🇾',
	),
	array(
		'name' => __( 'Peru', 'testimonial-pro' ),
		'flag' => '🇵🇪',
	),
	array(
		'name' => __( 'Philippines', 'testimonial-pro' ),
		'flag' => '🇵🇭',
	),
	array(
		'name' => __( 'Pitcairn Islands', 'testimonial-pro' ),
		'flag' => '🇵🇳',
	),
	array(
		'name' => __( 'Poland', 'testimonial-pro' ),
		'flag' => '🇵🇱',
	),
	array(
		'name' => __( 'Portugal', 'testimonial-pro' ),
		'flag' => '🇵🇹',
	),
	array(
		'name' => __( 'Puerto Rico', 'testimonial-pro' ),
		'flag' => '🇵🇷',
	),
	array(
		'name' => __( 'Qatar', 'testimonial-pro' ),
		'flag' => '🇶🇦',
	),
	array(
		'name' => __( 'Réunion', 'testimonial-pro' ),
		'flag' => '🇷🇪',
	),
	array(
		'name' => __( 'Romania', 'testimonial-pro' ),
		'flag' => '🇷🇴',
	),
	array(
		'name' => __( 'Russia', 'testimonial-pro' ),
		'flag' => '🇷🇺',
	),
	array(
		'name' => __( 'Rwanda', 'testimonial-pro' ),
		'flag' => '🇷🇼',
	),
	array(
		'name' => __( 'Saint Barthélemy', 'testimonial-pro' ),
		'flag' => '🇲🇫',
	),
	array(
		'name' => __( 'Saint Helena', 'testimonial-pro' ),
		'flag' => '🇱🇸',
	),
	array(
		'name' => __( 'Saint Kitts and Nevis', 'testimonial-pro' ),
		'flag' => '🇰🇳',
	),
	array(
		'name' => __( 'Saint Lucia', 'testimonial-pro' ),
		'flag' => '🇱🇨',
	),
	array(
		'name' => __( 'Saint Martin', 'testimonial-pro' ),
		'flag' => '🇲🇫',
	),
	array(
		'name' => __( 'Saint Pierre and Miquelon', 'testimonial-pro' ),
		'flag' => '🇵🇲',
	),
	array(
		'name' => __( 'Saint Vincent and the Grenadines', 'testimonial-pro' ),
		'flag' => '🇻🇨',
	),
	array(
		'name' => __( 'Samoa', 'testimonial-pro' ),
		'flag' => '🇼🇸',
	),
	array(
		'name' => __( 'San Marino', 'testimonial-pro' ),
		'flag' => '🇸🇲',
	),
	array(
		'name' => __( 'Sao Tome and Principe', 'testimonial-pro' ),
		'flag' => '🇲🇱',
	),
	array(
		'name' => __( 'Saudi Arabia', 'testimonial-pro' ),
		'flag' => '🇸🇦',
	),
	array(
		'name' => __( 'Senegal', 'testimonial-pro' ),
		'flag' => '🇸🇳',
	),
	array(
		'name' => __( 'Serbia', 'testimonial-pro' ),
		'flag' => '🇷🇸',
	),
	array(
		'name' => __( 'Seychelles', 'testimonial-pro' ),
		'flag' => '🇸🇨',
	),
	array(
		'name' => __( 'Sierra Leone', 'testimonial-pro' ),
		'flag' => '🇸🇱',
	),
	array(
		'name' => __( 'Singapore', 'testimonial-pro' ),
		'flag' => '🇸🇬',
	),
	array(
		'name' => __( 'Sint Maarten', 'testimonial-pro' ),
		'flag' => '🇸🇽',
	),
	array(
		'name' => __( 'Slovakia', 'testimonial-pro' ),
		'flag' => '🇸🇰',
	),
	array(
		'name' => __( 'Slovenia', 'testimonial-pro' ),
		'flag' => '🇸🇮',
	),
	array(
		'name' => __( 'Solomon Islands', 'testimonial-pro' ),
		'flag' => '🇸🇧',
	),
	array(
		'name' => __( 'Somalia', 'testimonial-pro' ),
		'flag' => '🇸🇴',
	),
	array(
		'name' => __( 'South Africa', 'testimonial-pro' ),
		'flag' => '🇿🇦',
	),
	array(
		'name' => __( 'South Georgia and the South Sandwich Islands', 'testimonial-pro' ),
		'flag' => '🇬🇸',
	),
	array(
		'name' => __( 'South Sudan', 'testimonial-pro' ),
		'flag' => '🇸🇸',
	),
	array(
		'name' => __( 'Spain', 'testimonial-pro' ),
		'flag' => '🇪🇸',
	),
	array(
		'name' => __( 'Sri Lanka', 'testimonial-pro' ),
		'flag' => '🇱🇰',
	),
	array(
		'name' => __( 'Sudan', 'testimonial-pro' ),
		'flag' => '🇸🇩',
	),
	array(
		'name' => __( 'Suriname', 'testimonial-pro' ),
		'flag' => '🇸🇷',
	),
	array(
		'name' => __( 'Sweden', 'testimonial-pro' ),
		'flag' => '🇸🇪',
	),
	array(
		'name' => __( 'Switzerland', 'testimonial-pro' ),
		'flag' => '🇨🇭',
	),
	array(
		'name' => __( 'Syria', 'testimonial-pro' ),
		'flag' => '🇸🇾',
	),
	array(
		'name' => __( 'Taiwan', 'testimonial-pro' ),
		'flag' => '🇹🇼',
	),
	array(
		'name' => __( 'Tajikistan', 'testimonial-pro' ),
		'flag' => '🇹🇯',
	),
	array(
		'name' => __( 'Tanzania', 'testimonial-pro' ),
		'flag' => '🇹🇿',
	),
	array(
		'name' => __( 'Thailand', 'testimonial-pro' ),
		'flag' => '🇹🇭',
	),
	array(
		'name' => __( 'Togo', 'testimonial-pro' ),
		'flag' => '🇹🇬',
	),
	array(
		'name' => __( 'Tokelau', 'testimonial-pro' ),
		'flag' => '🇹🇰',
	),
	array(
		'name' => __( 'Tonga', 'testimonial-pro' ),
		'flag' => '🇹🇴',
	),
	array(
		'name' => __( 'Trinidad and Tobago', 'testimonial-pro' ),
		'flag' => '🇹🇹',
	),
	array(
		'name' => __( 'Tunisia', 'testimonial-pro' ),
		'flag' => '🇹🇳',
	),
	array(
		'name' => __( 'Turkey', 'testimonial-pro' ),
		'flag' => '🇹🇷',
	),
	array(
		'name' => __( 'Turkmenistan', 'testimonial-pro' ),
		'flag' => '🇹🇲',
	),
	array(
		'name' => __( 'Tuvalu', 'testimonial-pro' ),
		'flag' => '🇹🇻',
	),
	array(
		'name' => __( 'Uganda', 'testimonial-pro' ),
		'flag' => '🇺🇬',
	),
	array(
		'name' => __( 'Ukraine', 'testimonial-pro' ),
		'flag' => '🇺🇦',
	),
	array(
		'name' => __( 'United Arab Emirates', 'testimonial-pro' ),
		'flag' => '🇦🇪',
	),
	array(
		'name' => __( 'United Kingdom', 'testimonial-pro' ),
		'flag' => '🇬🇧',
	),
	array(
		'name' => __( 'United States', 'testimonial-pro' ),
		'flag' => '🇺🇸',
	),
	array(
		'name' => __( 'Uruguay', 'testimonial-pro' ),
		'flag' => '🇺🇾',
	),
	array(
		'name' => __( 'Uzbekistan', 'testimonial-pro' ),
		'flag' => '🇺🇿',
	),
	array(
		'name' => __( 'Vanuatu', 'testimonial-pro' ),
		'flag' => '🇻🇺',
	),
	array(
		'name' => __( 'Vatican City', 'testimonial-pro' ),
		'flag' => '🇻🇦',
	),
	array(
		'name' => __( 'Venezuela', 'testimonial-pro' ),
		'flag' => '🇻🇪',
	),
	array(
		'name' => __( 'Vietnam', 'testimonial-pro' ),
		'flag' => '🇻🇳',
	),
	array(
		'name' => __( 'Western Sahara', 'testimonial-pro' ),
		'flag' => '🇪🇭',
	),
	array(
		'name' => __( 'Yemen', 'testimonial-pro' ),
		'flag' => '🇾🇪',
	),
	array(
		'name' => __( 'Zambia', 'testimonial-pro' ),
		'flag' => '🇿🇲',
	),
	array(
		'name' => __( 'Zimbabwe', 'testimonial-pro' ),
		'flag' => '🇿🇼',
	),
);

