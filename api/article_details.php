<?php

//ini_set("display_errors", "0"); 

$alc_api_options = get_option('alc_api_options');

/* 	post	 */

$post_id = (INT) strip_tags($_GET['aid']);
$post = get_post($post_id);

$XML = '<?xml version="1.0" encoding="utf-8"?>';
$XML .= "<article>";
$XML .= get_ALC_API_Version();


if (!empty($post)) {

    //echo '<pre>';
    //print_r($post);
    //echo '</pre>';

    $XML .= "<title>" . get_the_title($post_id) . "</title>";
    $XML .= "<webLink>" . get_permalink($post_id) . "</webLink>";

    //$thumbnail_id = get_post_meta(get_the_ID(), '_thumbnail_id', true);

    $description = apply_filters('the_content', $post->post_content);
    $XML .= "<description><![CDATA[" . $description . "]]></description>";

    $args = array('post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => $post_id);
    $attachments = get_posts($args);
    $XML .= "<galleryimages>";
    if ($attachments) {
        foreach ($attachments as $img) {
            if (wp_attachment_is_image($img->ID)) {
                $XML .= "<image>";
                $XML .= "<caption>" . $img->post_content . "</caption>";
                $XML .= "<path>" . $img->guid . "</path>";
                $XML .= "</image>";
            }
        }
    }
    $XML .= "</galleryimages>";
    
    /*	Query comments for post	*/
    $comments_count = get_comment_count($post_id);
    $XML .= "<commentCount>".$comments_count['approved']."</commentCount>";

    $XML .= "<error>1</error>";
    $XML .= "<errorMessage></errorMessage>";
} else {

    $XML .= "<error>-1</error>";
    $XML .= "<errorMessage>Invaild post id</errorMessage>";
}
$XML .= "</article>";

header("Content-Type:text/xml");
echo $XML;
?>