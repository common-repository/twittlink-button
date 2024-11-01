<?php
/*
Plugin Name: TwittLink Button
Plugin URI: http://www.twittlink.com/tools
Description: Insert TwittLink button in your post
Version: 0.1
Author: TwittLink
Author URI: http://www.twittlink.com
*/

add_action('admin_menu', 'tengine_options');

add_filter('the_content', 'tengine_add_button');
add_filter('get_the_excerpt', 'tengine_remove_filter', 6); 
 
add_option('tb_location', 'top_left');
  
add_option('tb_type', 'big'); 
add_option('tb_page', '0');
add_option('tb_cfield', '0');
add_option('tb_account', '');

function tengine_options()
{
    add_options_page('TwittLink Settings', 'TwittLink', 8, __FILE__, 'tengine_options_page');
}

function tengine_add_button($content)
{    
    global $post;
    
    if(get_option('tb_page') == 0 && is_page())
    {
	return $content;
    }

    $twcv = get_post_meta($post->ID, "twittlink", true);

    if($twcv == "")
    {
	$twcv = 0;
    }

    if(get_option('tb_cfield') == 1 && $twcv != 1)
    {
	return $content;
    }

    $btn_type = 'b';
                       
    if(get_option('tb_type') == 'big')
    {
	$btn_type = 'b';
    }

    if(get_option('tb_type') == 'small')
    {
	$btn_type = 's';
    }
    
    $button = '<script type="text/javascript">tl_title = "'.$post->post_title.'"; tl_url = "'.get_permalink().'"; tl_source = "'.get_option('tb_account').'";</script>';
    $button = $button.'<script type="text/javascript" src="http://www.twittlink.com/tools/button_'.$btn_type.'.js"></script></div>';
    
    if(get_option('tb_location') == 'top_left')
    {
	$button = '<div style="float: left; margin: 0px 10px 4px 4px;">'.$button;
	$content = $button.$content;
    }

    if(get_option('tb_location') == 'top_right')
    {
	$button = '<div style="float: right; margin: 0px 4px 4px 10px;">'.$button;
	$content = $button.$content;
    }

    if(get_option('tb_location') == 'bottom_left')
    {
	$button = '<div style="float: left; margin: 4px 10px 0px 4px;">'.$button;
	$content = $content.$button;
    }

    if(get_option('tb_location') == 'bottom_right')
    {
 	$button = '<div style="float: right; margin: 4px 4px 0px 10px;">'.$button;
	$content = $content.$button;
    }
    
    return $content;
}

function tengine_remove_filter($content) 
{
    remove_action('the_content', 'tengine_add_button');
    return $content;
}

function tengine_options_page()
{
?>
    <div class="wrap">
    <h2>Settings for TwittLink Button</h2>

    <form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?> 
        <table class="form-table">
            <tr>
                <th scope="row">
                    Where do you want your button ?
                </th>
                <td>
                    <p>
                        <input type="radio" value="top_left" <?php if (get_option('tb_location') == 'top_left') echo 'checked="checked"'; ?> name="tb_location" group="tb_location"/> 
                        <label for="tb_location">At the top left of your post</label>     
                    </p>
                    <p>
                        <input type="radio" value="top_right" <?php if (get_option('tb_location') == 'top_right') echo 'checked="checked"'; ?> name="tb_location" group="tb_location"/> 
                        <label for="tb_location">At the top right of your post</label>     
                    </p>
                    <p>
                        <input type="radio" value="bottom_left" <?php if (get_option('tb_location') == 'bottom_left') echo 'checked="checked"'; ?> name="tb_location" group="tb_location"/>
                        <label for="tb_location">At the end left of your post</label>    
                    </p>
                    <p>
                        <input type="radio" value="bottom_right" <?php if (get_option('tb_location') == 'bottom_right') echo 'checked="checked"'; ?> name="tb_location" group="tb_location"/> 
                        <label for="tb_location">At the end right of your post</label> <br/>    
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    Insert button only in posts who have twittlink custom filed set ?
                </th>
                <td>
                    <p>
                        <input type="checkbox" value="1" <?php if (get_option('tb_cfield') == '1') echo 'checked="checked"'; ?> name="tb_cfield" group="tb_cfield"/> 
                        <label for="tb_page">Please do!</label>     
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    What button do you prefer ?
                </th>
                <td>
                    <p>
                        <input type="radio" value="big" <?php if (get_option('tb_type') == 'big') echo 'checked="checked"'; ?> name="tb_type" group="tb_type"/> 
                        <label for="tb_type">Normal sized button</label>     
                    </p>
                    <p>
                        <input type="radio" value="small" <?php if (get_option('tb_type') == 'small') echo 'checked="checked"'; ?> name="tb_type" group="tb_type" />
                        <label for="tb_type">Small sized button</label>    
                    </p>
                </td>
            </tr> 
            <tr>
                <th scope="row">
                    Insert button also in pages ?
                </th>
                <td>
                    <p>
                        <input type="checkbox" value="1" <?php if (get_option('tb_page') == '1') echo 'checked="checked"'; ?> name="tb_page" group="tb_page"/> 
                        <label for="tb_page">Please do!</label>     
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    Your twitter account ?
                </th>
                <td>
                    <p>
                        <input type="text" value="<?php echo get_option('tb_account'); ?>" name="tb_account"/> 
                        <label for="tb_account">By entering you twitter account name, all tweets will have RT @youraccount in front.</label>
                    </p>
                </td>
            </tr>  
        </table>
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="tb_location,tb_type,tb_page,tb_account,tb_cfield" />
        <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Update Settings') ?>" />
        </p>
    </form>
    </div>
<?php
}

?>