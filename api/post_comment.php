<?php
ini_set("display_errors", "0"); 

    $alc_api_options = get_option('alc_api_options');
	
	
$XML  = '<?xml version="1.0" encoding="utf-8"?>';

$XML .= "<errors>";
$XML .= get_ALC_API_Version();

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$comment_registration = get_option('comment_registration');
	if($comment_registration == 1 && !is_user_logged_in()){
		
		$XML .= "<error>-1</error>";
		$XML .= "<errorMessage>Users must be registered and logged in to comment.</errorMessage>";
	
	}else{
				/*	post	*/

				$post_id = (INT)strip_tags($_POST['aid']);	
				$post = get_post($post_id);
				
				if(!empty($post)){
				
					if(comments_open($post_id)){

								//echo '<pre>';
								//print_r($post);
								//echo '</pre>';	
							
								$error	= 1;
								$errorMessage = '';
								
								$comment_author = strip_tags($_POST['author']);	
								$comment_author_email = strip_tags($_POST['email']);	
								$comment_body = strip_tags($_POST['body']);	
								
								if (!strlen($comment_author)) {
									$errorMessage .= 'author is required.';
									$error = -1;
								}
								
								if (!strlen($comment_author_email)) {
									$errorMessage .= 'email address is required.';
									$error = -1;
								}else if (!ereg("^[a-zA-Z0-9\.\_\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $comment_author_email)) {
									$errorMessage .= 'email address is incorrect.';
									$error = -1;
								}
								
								if (!strlen($comment_body)) {
									$errorMessage .= 'body is required.';
									$error = -1;
								}		
								
								
								if($error == 1){	// NO ERRORS
								
										$time = current_time('mysql');

										$comment_moderation = get_option('comment_moderation');
										if($comment_moderation == 1){
											$comment_approved = 0;
										}else{
											$comment_approved = 1;
										}
										
										$data = array(
											'comment_post_ID' => $post_id,
											'comment_author' => $comment_author,
											'comment_author_email' => $comment_author_email,
											'comment_author_url' => 'http://',
											'comment_content' => $comment_body,
											//'comment_type' => ,
											'comment_parent' => 0,
											//'comment_author_IP' => '127.0.0.1',
											//'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
											'comment_date' => $time,
											'comment_approved' => $comment_approved,
										);

										//echo '<pre>';
										//print_r($data);
										//echo '</pre>';
										
										$comment_id = wp_insert_comment($data);		
										if($comment_id){
											
											if($comment_moderation == 1){
												$XML .= "<error>0</error>";
												$XML .= "<errorMessage>Comment needs to be approved to go live</errorMessage>";										
											}else{
												$XML .= "<error>1</error>";
												$XML .= "<errorMessage></errorMessage>";		
											}	
										
										}else{
										
											$XML .= "<error>-1</error>";
											$XML .= "<errorMessage>Insert comment error</errorMessage>";		
										
										}
											
								
								}else{

									$XML .= "<error>-1</error>";
									$XML .= "<errorMessage>".$errorMessage."</errorMessage>";			

								}	
					
					}else{

						$XML .= "<error>-1</error>";
						$XML .= "<errorMessage>Comments closed for this article.</errorMessage>";								
					}					
									
			}else{

				$XML .= "<error>-1</error>";
				$XML .= "<errorMessage>Invaild article id.</errorMessage>";	
							
			}

		}						
				
}else{

	$XML .= "<error>-1</error>";
	$XML .= "<errorMessage>Invaild server request method. Use POST</errorMessage>";
	
}

	$XML .= "</errors>";

 header("Content-Type:text/xml");
 echo $XML;
 
?>