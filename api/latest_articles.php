<?php

ini_set("display_errors", "0");
//ini_set("display_errors", "1"); 
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

$alc_api_options = get_option('alc_api_options');


/* 	postst per page	 */

$page = (INT) strip_tags($_GET['pg']);

if (!isset($page) || $page < 1) {
    $page = 1;
}

$posts_per_page = $alc_api_options['posts_per_page'];
if (empty($posts_per_page)) {
    $posts_per_page = DEFAULT_ITEMS_PER_PAGE;
}


$XML = '<?xml version="1.0" encoding="utf-8"?>';

$XML .= "<articles>";
$XML .= get_ALC_API_Version();

$XML .= categoryListXML($alc_api_options); // category listing

$XML .= "<cid>_LATEST_ARTICLES_</cid>"; // just return selected category id

//$XML .= articleListImageSizeXML($alc_api_options); // list image size

if (!empty($alc_api_options['cat'])) {

//print_r($alc_api_options['cat']);

    /* 	Query posts for the latest articles	 */
    $args = array('post_type' => 'post', 'category__in' => $alc_api_options['cat'], 'posts_per_page' => $posts_per_page, 'paged' => $page);
    $cat_query = new WP_Query($args);

    $XML .= "<max_num_pages>" . $cat_query->max_num_pages . "</max_num_pages>";  // added total page count	

    if ($cat_query->have_posts()) {
        while ($cat_query->have_posts()) : $cat_query->the_post();

            $XML .= "<article>";
            $XML .= "<guid>" . get_the_ID() . "</guid>";
            $XML .= "<title>" . get_the_title() . "</title>";
            $XML .= "<webLink>" . get_permalink() . "</webLink>";


            if (!empty($cat_query->post->post_excerpt)) {
                $description = $cat_query->post->post_excerpt;
            } else {
                $description = $cat_query->post->post_content;
            }

            $description = stripHtmlTags($description);
            
            $XML .= "<description><![CDATA[" . $description . "]]></description>";
            $XML .= "<pubDate>" . get_the_time("Y-m-d H:i") . "</pubDate>";

            $thumbnail_id = get_post_thumbnail_id(); //get_post_meta(get_the_ID(), '_thumbnail_id', true);

            $XML .= "<featureImage>";
            if (!empty($thumbnail_id)) {
                //$xml_thumb = wp_get_attachment_image_src($thumbnail_id, 'full');
                //$xml_thumb_src = get_tim_thumb($xml_thumb[0], THUMB_WIDTH_2x, THUMB_HEIGHT_2x);

                $xml_thumb_src = wp_get_attachment_thumb_url($thumbnail_id);                              
                
                $XML .= $xml_thumb_src;
            }
            $XML .= "</featureImage>";

            $XML .= "</article>";
        endwhile;

        wp_reset_postdata();
    }

    $XML .= "<error>1</error>";
    $XML .= "<errorMessage></errorMessage>";
} else {
    $XML .= "<error>-1</error>";
    $XML .= "<errorMessage>No categories</errorMessage>";
}

$XML .= "</articles>";

header("Content-Type:text/xml");
echo $XML;

//print_r ($alc_api_options['cat']);
?>