<?php

define("API_VERSION", "1.0");

//define("THUMB_WIDTH", 80);
//define("THUMB_HEIGHT", 60);

//define("THUMB_WIDTH_2x", THUMB_WIDTH*2); // retina display image size for image resizing
//define("THUMB_HEIGHT_2x", THUMB_HEIGHT*2);// retina display image size for image resizing

define("SYNOPSIS_LENGTH", 300); // sysnopsis string length

define("DEFAULT_ITEMS_PER_PAGE", 20);

function get_ALC_API_Version() {
    $XML .= '<api_version>'.API_VERSION.'</api_version>';
    
    return $XML;
    
}


/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @return string 
 */
function trim_text($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }
  
    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }
  
    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);
  
    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }
  
    return $trimmed_text;
}


function stripHtmlTags($str) 
{
    $description = strip_tags($str);
    
    $description = preg_replace("/(\[[^\]]*\])/s", "", $description); // remove 3rd party plugin strnage code e.g. [caption id="attachment_5986" align="alignleft" width="300"]
    
    $description = str_replace("&nbsp;", " ", $description);
    $description = preg_replace('/\s+/', ' ',$description);
    
    //no need to trim, already shorter than trim length
    if (strlen($description) <= SYNOPSIS_LENGTH) {
        return $description;
    }
    
    //find last space within length
    $last_space = strrpos(substr($description, 0, SYNOPSIS_LENGTH), ' ');
    $description = substr($description, 0, $last_space);
    
    //$description = substr($description, 0, SYNOPSIS_LENGTH);
    
    return $description;
}

function strip($value)
{
  if(get_magic_quotes_gpc() == 0)
    return $value;
  else
    return stripslashes($value);
}


function categoryListXML ($alc_api_options) {
    $args = array(
        //'orderby' => 'name',
        //'order' => 'ASC',
        'hierarchical' => 1,
        'hide_empty' => 0,
        'include' => $alc_api_options['cat']
    );
    $categories = get_categories($args);


    /* all categories */

    $XML .= "<categories>";

    if (!empty($categories)) {

        //echo '<pre>';
        //print_r($category);
        //print_r($alc_api_options);
        //echo '</pre>';
        foreach ($categories as $category) {

            $XML .= "<category>";
            $XML .= "<categoryID>" . $category->term_id . "</categoryID>";
            $XML .= "<categoryName>" . $category->name . "</categoryName>";

            $XML .= "<categoryDescription>";
            if (!empty($category->description)) {
                $XML .="<![CDATA[" . $category->description . "]]>";
            }
            $XML .= "</categoryDescription>";

            if ($category->category_parent > 0) {
                $XML .= "<categoryParent>" . $category->category_parent . "</categoryParent>";
            }

            $XML .= "</category>";
        }
    }

    $XML .= "</categories>";
    
    return $XML;
}


?>