<?php
ini_set("display_errors", "0"); 
?>
<form action="<?php bloginfo('url');?>/?applacarte_wp_api=post_comment" method="post" target="_blank">
Article ID: <input type="text" name="aid" value="9">
<br>Author: <input type="text" name="author" value="">
<br>Email: <input type="text" name="email" value="">
<br>Body: <textarea name="body"></textarea>
<br><input type="submit" >
</form>

