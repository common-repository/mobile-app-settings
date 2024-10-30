<?php
/*  
Plugin Name: Mobile App Plugin
Plugin URI: 
Description: With the plugin installed you can turn your Wordpress website into a gorgeous full featured native mobile app in minutes.  
Version: 1.0.5  
Author:  
*/  

include_once(plugin_dir_path( __FILE__ ).'/include/xml-api-functions.php');

add_action('init', 'xml_api_plugin_init');

function xml_api_plugin_init(){

	if(isset($_REQUEST['applacarte_wp_api'])){
		
		//echo '<pre>';
		//print_r($_REQUEST);
		//echo '</pre>';

		switch ($_REQUEST['applacarte_wp_api']) {
			case 'articles':
				//echo 'articles';
				include_once(plugin_dir_path( __FILE__ ).'/api/articles.php');
				break;
			case 'article_details':
				//echo "article_details";
				include_once(plugin_dir_path( __FILE__ ).'/api/article_details.php');			
				break;
			case 'comments':
				//echo "comments";
				include_once(plugin_dir_path( __FILE__ ).'/api/comments.php');				
				break;
			case 'latest_articles':
				//echo "latest_articles";
				include_once(plugin_dir_path( __FILE__ ).'/api/latest_articles.php');				
				break;	
			case 'post_comment':
				//echo "post_comment";
				include_once(plugin_dir_path( __FILE__ ).'/api/post_comment.php');				
				break;		
			case 'post_comment_form':
				//echo "post_comment_form";
				include_once(plugin_dir_path( __FILE__ ).'/api/post_comment_form.php');				
				break;	
			case 'search':
				//echo "search";
				include_once(plugin_dir_path( __FILE__ ).'/api/search.php');				
				break;
                        case 'info':
                                include_once(plugin_dir_path( __FILE__ ).'/api/api_info.php');				
				break;
		}		
		exit;	
	}	
}


add_action('admin_menu', 'xml_api_plugin_menu');

function xml_api_plugin_menu() {
    add_options_page('Mobile App Settings', 'Mobile App Settings', 8, 'alc_wp_options', 'alc_wp_options_page');
}	

function alc_wp_options_page() {
    $opt_name = 'alc_api_options';
    $hidden_field_name = 'alc_api_hidden';
    $data_field_name = 'alc_api_option';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    echo '
<style>
#category_checklist  li{
	list-style-type: none;
        margin-left: 20px;
}

.option_item {
        margin-left: 20px;
}

</style>    
    ';
    
    
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
	//echo '<pre>';
	//print_r($_POST);
	//echo '</pre>';		
		
	$_POST[$data_field_name]['cat'] = $_POST['post_category'];
			
		// Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put an options updated message on the screen

?>



<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>

<?php

    }

?>

<div class="wrap">
<h2>Mobile App Settings</h2>
<form name="form1" method="post" id="xml_api_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<br>
<h3>Categories for export</h3>
<ul id="category_checklist">
<?php
wp_category_checklist(0,0, $opt_val['cat'],0,0,false); ?>
</ul>

<br><br>
<h3>Article listing</h3>
<div class="option_item">
<p>Posts per page:
<input type="text" name="<?php echo $data_field_name; ?>[posts_per_page]" value="<?php echo (empty($opt_val['posts_per_page']))?DEFAULT_ITEMS_PER_PAGE:$opt_val['posts_per_page']; ?>" size="10">
</p>
</div>

<br><br>
<h3>Comments listing</h3>
<div class="option_item">
<p>Comments per page:
<input type="text" name="<?php echo $data_field_name; ?>[comments_per_page]" value="<?php echo (empty($opt_val['comments_per_page']))?DEFAULT_ITEMS_PER_PAGE:$opt_val['comments_per_page']; ?>" size="10">
</p>
</div>
<br>
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p>

</form>
</div>

<?php

}


function add_thickbox_4_me() {
   wp_enqueue_script('thickbox');
   wp_enqueue_style( 'thickbox' );
}
add_action('wp_enqueue_scripts', 'add_thickbox_4_me');
add_action( 'admin_menu', 'add_thickbox_4_me' );

//<link rel='stylesheet' id='thickbox-css'  href='http://magnum/xml_plugin/wp-includes/js/thickbox/thickbox.css?ver=20090514' type='text/css' media='all' />

?>