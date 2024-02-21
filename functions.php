<?php
// Automatic theme updates from the GitHub repository
add_filter('pre_set_site_transient_update_themes', 'automatic_GitHub_updates', 100, 1);
function automatic_GitHub_updates($data) {
  // Theme information
  $theme   = get_stylesheet(); // Folder name of the current theme
  $current = wp_get_theme()->get('Version'); // Get the version of the current theme
  // GitHub information
  $user = 'ivndmv'; // The GitHub username hosting the repository
  $repo = 'arpanu'; // Repository name as it appears in the URL
  // Get the latest release tag from the repository. The User-Agent header must be sent, as per
  // GitHub's API documentation: https://developer.github.com/v3/#user-agent-required
  $file = @json_decode(@file_get_contents('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest', false,
      stream_context_create(['http' => ['header' => "User-Agent: ".$user."\r\n"]])
  ));
  if($file) {
	$update = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    // Only return a response if the new version number is higher than the current version
    if($update > $current) {
  	  $data->response[$theme] = array(
	      'theme'       => $theme,
	      // Strip the version number of any non-alpha characters (excluding the period)
	      // This way you can still use tags like v1.1 or ver1.1 if desired
	      'new_version' => $update,
	      'url'         => 'https://github.com/'.$user.'/'.$repo,
	      'package'     => $file->assets[0]->browser_download_url,
      );
    }
  }
  return $data;
}


add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );
function add_theme_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'cf7-styles', get_template_directory_uri() . '/css/cf7.css', array(), '1.1', 'all' );
	wp_enqueue_script( 'cf7-scripts', get_template_directory_uri() . '/js/cf7.js', array( 'jquery' ), 1.1, true );
}


//shortcodes
add_shortcode('site_name', 'return_site_name');
function return_site_name($atts) {
    return get_bloginfo('name');
}


add_shortcode('site_url', 'return_site_url');
function return_site_url($atts) {
    return get_bloginfo('wpurl');
}


add_shortcode('site_domain', 'return_site_domain');
function return_site_domain($atts) {
    return parse_url( get_site_url(), PHP_URL_HOST );
}

// Add a filter hook to modify the countries array. We are making the first item to be BG
add_filter('custom_countries_array', 'modify_countries_array');
function modify_countries_array($countries_array) {
  $first_item = $countries_array[32]; // 32 is Bulgaria
  unset($countries_array[32]);
  array_unshift($countries_array, $first_item);
  return $countries_array;
}

//pagespeed
add_filter( 'script_loader_tag', 'prefix_defer_js_rel_preload', 10, 4 );
function prefix_defer_js_rel_preload($html) {
  if ( ! is_admin() ) {
    if (!str_contains($html, '/wp-includes/js/jquery/')) {
      $html = str_replace( '></script>', ' defer></script>', $html );
    }
  }
  return $html;
}

add_filter( 'style_loader_tag', 'prefix_defer_css_rel_preload', 10, 4 );
function prefix_defer_css_rel_preload( $html, $handle, $href, $media ) {
    if ( ! is_admin() ) {
        $html = '<link rel="preload" href="' . $href . '" as="style" id="' . $handle . '" media="' . $media . '" onload="this.onload=null;this.rel=\'stylesheet\'">'
            . '<noscript>' . $html . '</noscript>';
    }
    return $html;
}

add_action('wp_footer', 'lazy_load');
function lazy_load() {
  ?><script>
    document.querySelectorAll('.lazy-load img').forEach( img => {
    img.setAttribute('loading', 'lazy')
    })
  </script>
<?php }



add_action('admin_init', 'add_settings_section_clickup');  
function add_settings_section_clickup() {  
    add_settings_section(  
        'settings_section_clickup', // Section ID 
        'ClickUp Settings', // Section Title
        'add_settings_section_clickup_callback', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );
    
    add_settings_field( // Option 1
        'clickup_list_id', // Option ID
        'ClickUp List ID', // Label
        'clickup_list_id_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'settings_section_clickup', // Name of our section
        array( // The $args
            'clickup_list_id' // Should match Option ID
        )  
    );     
    register_setting('general', 'clickup_list_id', 'esc_attr');
}

function add_settings_section_clickup_callback() { // Section Callback
    echo '<p>Open a ClickUp list and copy the List ID from its URL</p>';  
}

function clickup_list_id_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" style="max-width: 400px; width: 100%;" />';
}

add_shortcode('clickup_list_id', 'clickup_list_id_function');
function clickup_list_id_function() {
  return get_option( 'clickup_list_id' );
}

add_action('wp_footer', 'populate_cf7_hidden_fields');
function populate_cf7_hidden_fields() {
  $clickup_list_link = get_option( 'clickup_list_id' );
  $clickup_list_id = trim(explode('/v/li/', $clickup_list_link)[1]);
?>
  <script type="text/javascript">
  const cf7MakeHiddenFields = document.querySelectorAll('input[name="cf7-make-list-id"]')
  if (cf7MakeHiddenFields.length > 0) {
      cf7MakeHiddenFields.forEach(field => {
          field.value = '<?php echo $clickup_list_id; ?>'
      })
  }
  </script>
<?php  
}

?>