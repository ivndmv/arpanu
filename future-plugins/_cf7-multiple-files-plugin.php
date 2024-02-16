<?php
/*
Plugin Name:  
Description:  
Version:    
Author:
This is going to be plugin that adds custom contactform 7 tag for uploading multiple files
*/
// add_action( 'wpcf7_init', 'cf7_add_form_tag_multiple_files' );
// function cf7_add_form_tag_multiple_files() {
//     wpcf7_add_form_tag( 
//     array('multifiles', 'multifiles*'), 
//     'cf7_add_form_tag_multiple_files_handler',
//     array( 'name-attr' => true, 'file-uploading' => true )
//     ); 
// }


// function cf7_add_form_tag_multiple_files_handler( $tag ) {
//     //file input
//     if ( empty( $tag->name ) ) {
//       return '';
//     }
//     $validation_error = wpcf7_get_validation_error( $tag->name );
//     $class = wpcf7_form_controls_class( $tag->type );
//     if ( $validation_error ) {
//       $class .= ' wpcf7-not-valid';
//     }
//     $atts_file = array();
//     $atts_file['multiple'] = 'multiple';
//     $atts_file['size'] = $tag->get_size_option( '40' );
//     $atts_file['class'] = $tag->get_class_option( $class );
//     $atts_file['id'] = $tag->get_id_option();
//     $atts_file['capture'] = $tag->get_option( 'capture', '(user|environment)', true );
//     $atts_file['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );
//     $atts_file['accept'] = wpcf7_acceptable_filetypes(
//       $tag->get_option( 'filetypes' ), 'attr'
//     );
//     if ( $tag->is_required() ) {
//       $atts_file['aria-required'] = 'true';
//     }
//     if ( $validation_error ) {
//       $atts_file['aria-invalid'] = 'true';
//       $atts_file['aria-describedby'] = wpcf7_get_validation_error_reference(
//         $tag->name
//       );
//     } else {
//       $atts_file['aria-invalid'] = 'false';
//     }
//     $atts_file['type'] = 'file';
//     $atts_file['name'] = $tag->name;
//     //text input to store the file URLs
//     $atts_text = array(
//       'type' => 'hidden',
//       'name' => $tag->name,
//       'id' => $tag->name,
//       'value' => ''
//     );
//     $inputs = sprintf(
//         '<input %s /><input %s />',
//         wpcf7_format_atts( $atts_file ),
//         wpcf7_format_atts( $atts_text )
//       );
//     $input_file = sprintf(
//       '<span class="wpcf7-form-control-wrap" data-name="%1$s"><input %2$s />%3$s</span>',
//       esc_attr( $tag->name ),
//       wpcf7_format_atts( $atts_file ),
//       $validation_error
//     );
//     $input_text = sprintf(
//       '<input %s />',
//       wpcf7_format_atts( $atts_text )
//     );
//     return $input_file . $input_text;
// }


// add_action( 'wp_ajax_nopriv_cf7_form_tag_multiple_files_upload', 'cf7_form_tag_multiple_files_upload' );
// function cf7_form_tag_multiple_files_upload() {
//     $a = array();
//     $target_dir = WP_CONTENT_DIR . '/cf7-multifiles/';
//     $a['files-number'] = (int)$_POST['files-number'];
//     $files_number = (int)$_POST['files-number'];
//     for ($i = 0; $i < $files_number; $i++) {
//       $a['file-name-' . $i] = $_POST['file-name-' . $i];
//       $a['file-tmp-' . $i] = $_FILES['file-info-' . $i]['tmp_name'];
//       move_uploaded_file($_FILES['file-info-' . $i]['tmp_name'], $target_dir . $_POST['file-name-' . $i]);
//     }
//     echo json_encode($a);
//     wp_die(); // this is required to terminate immediately and return a proper response
// }


// add_action('wp_footer', 'js_cf7_form_tag_multiple_files_upload');
// function js_cf7_form_tag_multiple_files_upload() {  


// <script type="text/javascript">
//     const forms = document.querySelectorAll('.wpcf7 .wpcf7-form')
//     forms.forEach (form => {
//       let userFiles = form.querySelector('input[type=file]')
//       let userFilesTextInput = form.querySelector('#' + userFiles.getAttribute('name')) // we use this to store file urls
//       let fileUrls = []
//       allowedFiles = ["application/pdf"];
//       form.addEventListener('change', e => {
//         if (userFiles.files.length > 0) {
//           for (let i = 0; i < userFiles.files.length; i++) { 
//             if (allowedFiles.includes(userFiles[i].files[0]['type']) ) {
//             }
//           }
//         }
//       })
//       form.addEventListener('submit', e => { 
//         // e.preventDefault();
//         let data = new FormData();
//         data.append( 'action', 'cf7_form_tag_multiple_files_upload' )
//         data.append('files-number', userFiles.files.length)
//         for (let i = 0; i < userFiles.files.length; i++) {
//           data.append( 'file-name-' + i, userFiles.files[i].name )
//           data.append( 'file-info-' + i, userFiles.files[i] )
//           fileUrls.push(location.origin + '/wp-content/cf7-multifiles/' + userFiles.files[i].name)
//         }
//         userFilesTextInput.setAttribute('value', fileUrls)
//         let ajaxScript = { ajaxUrl : `${location.origin}/wp-admin/admin-ajax.php` } 
//         fetch( ajaxScript.ajaxUrl, { method: 'POST', body: data } )
//         .then( response => response.json())
//         .then( data => console.log(data))
//         .catch( err => console.log(err) )
//     })
//     })
// </script>

//}
?>