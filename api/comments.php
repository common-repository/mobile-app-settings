<?php
ini_set("display_errors", "0"); 

    $alc_api_options = get_option('alc_api_options');
 

	$XML  = '<?xml version="1.0" encoding="utf-8"?>';
        
	$XML .= "<comments>";
        $XML .= get_ALC_API_Version();
 
	/*	comments per page	*/
	 
	$comments_per_page = $alc_api_options['comments_per_page'];
	if(empty($comments_per_page)){ 
			$comments_per_page = DEFAULT_ITEMS_PER_PAGE; 
	}
 
 
	$page = (INT)strip_tags($_GET['pg']);
	if(!isset($page) || $page < 1){
		$offset = 0;
	}else{
		$offset = ($page-1)*$comments_per_page;
	} 
	

	/*	post	*/

	$post_id = (INT)strip_tags($_GET['aid']);
	$post = get_post($post_id);

	
				if(!empty($post)){
					
					//echo '<pre>';
					//print_r($category);
					//echo '</pre>';
					
			 
					/*	Query comments for post	*/
					$args = array('post_id' => $post_id, 'number'=>$comments_per_page, 'offset' => $offset, 'order' => 'DESC', 'status' => 'approve');		//, 'status' => 'approve'
					$comments = get_comments($args);	

					$comments_count = get_comment_count($post_id);
					$comments_max_page = ceil($comments_count['approved'] / $comments_per_page);

					$XML .= "<max_num_comment_pages>".$comments_max_page."</max_num_comment_pages>";	 // added total page count	 
					
					
					if(!empty($comments)){
							
						foreach($comments as $comment){
					
						//echo '<pre>';
						//print_r($comment);
						//echo '</pre>';
					
						$XML .= "<comment>";
						
						$XML .= "<commentID>".$comment->comment_ID."</commentID>";
						$XML .= "<commentAuthor>".$comment->comment_author."</commentAuthor>";			
						$XML .= "<commentContent>".$comment->comment_content."</commentContent>";
						$XML .= "<commentDate>". mysql2date("Y-m-d H:i",$comment->comment_date)."</commentDate>";
						
						$XML .= "</comment>";	
						}

						wp_reset_postdata();
			 
						$XML .= "<error>1</error>";
						$XML .= "<errorMessage></errorMessage>";
						
					}else{
					
						$XML .= "<error>-1</error>";
						$XML .= "<errorMessage>No comments</errorMessage>";		
						
					}
			 
				}else{
			 
					$XML .= "<error>-1</error>";
					$XML .= "<errorMessage>Invaild article id</errorMessage>"; 
				}
	
	
		$XML .= "</comments>";
 
 header("Content-Type:text/xml");
 echo $XML;

?>