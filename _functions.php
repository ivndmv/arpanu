<?php

add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );
function add_theme_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	// wp_enqueue_style( 'cf7', get_template_directory_uri() . '/css/cf7.css', array(), '1.1', 'all' );
	// wp_enqueue_script( 'script', get_template_directory_uri() . '/js/cf7.js', array( 'jquery' ), 1.1, true );
}

//multiple files
add_action( 'wpcf7_init', 'cf7_add_form_tag_multiple_files' );
function cf7_add_form_tag_multiple_files() {
    wpcf7_add_form_tag( 
    'multifiles', 
    'cf7_add_form_tag_multiple_files_handler',
    array( 'name-attr' => true )
    ); 
    
}

function cf7_add_form_tag_multiple_files_handler( $tag ) {
    //file input
    $atts_file = array(
        'type' => 'file',
        'name' => $tag->name,
        'multiple' => 'multiple',
    );

    //text input to store the file URLs
    $atts_text = array(
      'type' => 'hidden',
      'name' => $tag->name,
      'id' => $tag->name,
      'value' => ''
    );

    $inputs = sprintf(
        '<input %s /><input %s />',
        wpcf7_format_atts( $atts_file ),
        wpcf7_format_atts( $atts_text )
      );
    return $inputs;
}











add_action( 'wp_ajax_nopriv_cf7_form_tag_multiple_files_upload', 'cf7_form_tag_multiple_files_upload' );

function cf7_form_tag_multiple_files_upload() {

    $a = array();

    $target_dir = WP_CONTENT_DIR . '/cf7-multifiles/';

    $a['files-number'] = (int)$_POST['files-number'];

    $files_number = (int)$_POST['files-number'];

    for ($i = 0; $i < $files_number; $i++) {
      $a['file-name-' . $i] = $_POST['file-name-' . $i];
      $a['file-tmp-' . $i] = $_FILES['file-info-' . $i]['tmp_name'];
      move_uploaded_file($_FILES['file-info-' . $i]['tmp_name'], $target_dir . $_POST['file-name-' . $i]);
    }
    echo json_encode($a);
    wp_die(); // this is required to terminate immediately and return a proper response
}


add_action('wp_footer', 'js_cf7_form_tag_multiple_files_upload');
function js_cf7_form_tag_multiple_files_upload() {  
?><script type="text/javascript">
    const forms = document.querySelectorAll('.wpcf7 .wpcf7-form')
    forms.forEach (form => {
      let userFiles = form.querySelector('input[type=file]')
      let userFilesTextInput = form.querySelector('#' + userFiles.getAttribute('name')) // we use this to store file urls
      let fileUrls = []

      form.addEventListener('submit', e => { 
        // e.preventDefault();
        let data = new FormData();

        data.append( 'action', 'cf7_form_tag_multiple_files_upload' )
        data.append('files-number', userFiles.files.length)

        for (let i = 0; i < userFiles.files.length; i++) {
          data.append( 'file-name-' + i, userFiles.files[i].name )
          data.append( 'file-info-' + i, userFiles.files[i] )
          fileUrls.push(location.origin + '/wp-content/cf7-multifiles/' + userFiles.files[i].name)
        }
        userFilesTextInput.setAttribute('value', fileUrls)

        let ajaxScript = { ajaxUrl : `${location.origin}/wp-admin/admin-ajax.php` } 
        fetch( ajaxScript.ajaxUrl, { method: 'POST', body: data } )
        .then( response => response.json())
        .then( data => console.log(data))
        .catch( err => console.log(err) )
    })
    })
</script>
<?php
}



















//countrycodes

add_action( 'wpcf7_init', 'custom_add_form_tag_countrycodes' );
function custom_add_form_tag_countrycodes() {
  wpcf7_add_form_tag(
    'countrycodes',
    'custom_countrycodes_form_tag_handler',
    array( 'name-attr' => true )
  );
}

function custom_countrycodes_form_tag_handler( $tag ) {
    $atts = array(
      'type' => 'select',
      'name' => $tag->name,
      'class' => 'cf7-countrycodes-form-tag'
    );
    
    $select = sprintf(
      '<select %s />',
      wpcf7_format_atts( $atts )
    );

    //include countries
    $countries_json  = file_get_contents(get_template_directory_uri() . "/countries-json/countries.json");
    $countries_array = json_decode($countries_json);

    //make the BG item to be the default one
    $bg_item = $countries_array[32];
    unset($countries_array[32]);
    array_unshift($countries_array, $bg_item);
    
    //populate options
    foreach ($countries_array as $country) {
      $select .= '<option value="'.$country->dial_code.'">' . $country->code . ' ' . $country->dial_code . '</option>';
    }
     
    $select .= '</select>';

    return $select;
}

add_action( 'wp_enqueue_scripts', 'add_select2' );
function add_select2() {
    wp_enqueue_style( 'select2-css', get_template_directory_uri() . '/select2/css/select2.min.css', array(), '1.1', 'all' );
    wp_enqueue_script( 'select2-js', get_template_directory_uri() . '/select2/js/select2.min.js', array( 'jquery' ), 1.1, true );
}

add_action('wp_footer', 'custom_countrycodes_form_tag_js');
function custom_countrycodes_form_tag_js() {
  ?><script type="text/javascript">
      jQuery(document).ready(function() {
          jQuery('select.cf7-countrycodes-form-tag').select2();
      });
    </script>
<?php }
?>