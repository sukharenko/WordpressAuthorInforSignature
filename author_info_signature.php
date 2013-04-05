<?php
/*
Plugin Name: Author Info Signature
Plugin URI: http://sukharenko.com/category/development/wordpress/
Description: Add a author info signature to the bottom of posts with the author's information
Author: Eugenij 'Scorp' Sukharenko
Version: 1.0
Author URI: http://sukharenko.com
*/

$author_info_version = "1.0";

add_option('author_info_show_email', FALSE);

function get_avatar_url($get_avatar)
{
	preg_match("/src='(.*?)'/i", $get_avatar, $matches);
	return $matches[1];
}

function author_info_add_option_pages()
{
	if (function_exists('add_options_page')) {
		add_options_page("Add Author Info", 'Author Info Signature', 8, __FILE__, 'author_info_options_page');
	}		
}

function author_info_options_page()
{

	global $author_info_version;
	
	if (isset($_POST['set_defaults']))
	{

		echo '<div id="message" class="updated fade"><p><strong>';

		update_option('author_info_show_email', FALSE);

		echo 'Default Options Loaded!';
		echo '</strong></p></div>';

	}
	else if (isset($_POST['info_update']))
	{

		echo '<div id="message" class="updated fade"><p><strong>';
		
		update_option('author_info_show_email', (bool)$_POST["author_info_show_email"]);
		
		echo 'Configuration Updated!';
		echo '</strong></p></div>';

	}

?>

<div class=wrap>

	<h2>Author Info Signature v<?php echo $author_info_version; ?></h2>

	<div>For more information visit:</div>
	<div><a href="http://sukharenko.com/category/development/wordpress/" target="_blank">http://sukharenko.com/category/development/wordpress/</a></div>
	<div><a href="https://github.com/scorpca/WordpressAuthorInforSignature" target="_blank">https://github.com/scorpca/WordpressAuthorInforSignature</a></div><br />

	<h3>Options:</h3>
	
	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
	<input type="hidden" name="info_update" id="info_update" value="true" />
	
	<div style="padding: 0 0 0 30px">
		<input type="checkbox" name="author_info_show_email" value="checkbox" <?php if (get_option('author_info_show_email')) echo "checked='checked'"; ?>/>&nbsp;&nbsp;
		<strong>Display E-Mail Address</strong>
	</div>

	<div class="submit">
		<input type="submit" name="info_update" value="<?php _e('Update options'); ?> &raquo;" />
		<input type="submit" name="set_defaults" value="<?php _e('Load Default Options'); ?> &raquo;" />
	</div>
		
	</form>

</div>

<?

}

function author_info_generate($content)
{

	// Get author information
	$a_login = get_the_author_login();
	$a_first = get_the_author_firstname();
	$a_last = get_the_author_lastname();
	$a_nick = get_the_author_nickname();
	$a_email = get_the_author_email();
	$a_url = get_the_author_url();
	$a_desc = get_the_author_description();
	$a_avatar = get_avatar(get_the_author_id(), 32);
	
	$opt_show_email = get_option("author_info_show_email");

	$info = '';

	if (is_single())
	{
	
		$info = '<div style="width: auto; height: auto; border: 1px solid black; padding: 10px; -webkit-border-radius: 10px;">';
		
		$info .= '<img src="' . get_avatar_url(get_avatar(get_the_author_id(), 120 )) . '" align="left" class="authorimage" style="margin-right: 10px;" />';
		
		$info .= '<div><b>' . $a_first . ' ' . $a_last . ' (' . $a_nick . ')</b></div>';
		$info .= '<div>' . $a_desc . '</div><br />';
		$info .= '<div><a href="' . $a_url . '">' . $a_url . '</a></div>';
		if ($opt_show_email)
		{
			$info .= '<div><a href="mailto:' . $a_email . '">' . $a_email . '</a></div>';
		}
		
		$info .= '<div class="clear"></div>';
		
		$info .= '</div>';
		
		$content .= $info;
	
	}
	
	return $content;

}

add_filter('the_content', 'author_info_generate');
add_action('admin_menu', 'author_info_add_option_pages');

?>
