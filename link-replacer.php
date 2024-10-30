<?php
/*  Copyright 2009  Waseem Senjer  (email : waseem.senjer@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/*
Plugin Name: Link Replacer
Plugin URI: http://www.shamekh.ws
Description: it's a plugin to replace text with link , you can store all the words and the links to replace it immediately without edit it in the WYSIWYG editor , this plugin can save your time .
Author: Waseem Senjer
Version: 1.0
Author URI: http://www.shamekh.ws
*/

// Insert the Lr_add_pages() sink into the plugin hook list for 'admin_menu'
add_action('admin_menu', 'Lr_add_pages');
//initialization of database options data
register_activation_hook( __FILE__, 'link_replacer_install' );
// Uninstallation
register_deactivation_hook( __FILE__, 'link_replacer_uninstall' );


//initialization of database options data
function link_replacer_install() {
    $newoptions = get_option('link_replacer_options');
    $newoptions['text'] = "google\nwww.google.com\nyahoo\nwww.yahoo.com";
    add_option('link_replacer_options', $newoptions);
}


// mt_add_pages() is the sink function for the 'admin_menu' hook
function Lr_add_pages() {
    add_options_page('Site Help', 'Link Replacer', 5, __FILE__, 'link_replacer_page');
}

// link_replacer_page() displays the page content for the custom Test Toplevel menu
function link_replacer_page() {
  $options = $newoptions =(array) get_option('link_replacer_options');
	// if submitted, process results
    echo "<div class=\"wrap\"><h1>Link Replacer options</h1>";
	if ( $_POST["link_replacer_submit"] ) {
         echo '<!-- Last Action --><div id="message" class="updated fade"  ><p>'."<strong><span style=\"color: #008000;\">Updated Successfully</span></strong>".'</p></div>';
		$newoptions['text'] = strip_tags(stripslashes($_POST["text"]));
	}
	// any changes? save!
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('link_replacer_options', $options);
	}

 
	// options form
echo '<form method="post" action="'.get_bloginfo('wpurl').'/wp-admin/options-general.php?page=link-replacer/link-replacer.php">';
echo '<strong><span style="color: #ff0000;">Very Important Remark :</span></strong>
write the links like this example<br>
first write the name of the site <b>without spaces</b> then go to a new line and write the site link <span style="color: #ff0000;"><strong>without</strong></span> "<strong>http://</strong>" and so on  , see the example';
echo '<p>notice that the words case sensetive  Google != google</p>';
echo '<p>Example:</p>
<ul>
<li><code>google</code><br>
<code>www.google.com</code><br>
<code>yahoo</code><br>
<code>www.yahoo.com</code><br>
<code>MyBlog</code><br>
<code>www.myblog.com</code><br></li>
</ul>';
echo '<h3>Links</h3>';
echo '<table class="form-table">';
echo " <textarea name=\"text\" rows=\"20\" cols=\"50\">";
echo $newoptions['text'];
echo "</textarea>";
echo "</table>";
echo '<p class="submit"><input type="submit" name="link_replacer_submit" value="Update Options &raquo;"></input></p>';
echo "</div>";
echo '</form>';
 // The form end
}




function link_replacer($text){
 $text2=(array) get_option('link_replacer_options'); //get data from the databse
$text3=$text; // copy the data
$data=preg_split ("/\s+/", $text2['text']);  // fetch the data
 //$myArray = explode('/\s+/',$text2['text']); another way
 for ($i=0;$i<count($data);$i++) {
     $pos = stripos($text3, $data[$i]);
     if ($pos === false) {
   // do nothing :)
        }else {    // data[i] = site name , data[$i+1] = the link
$help = '<a href="http://'.$data[$i+1].'" target="_blank" >'.$data[$i].'</a>'; // The new Text
$text=str_replace($data[$i],$help,$text); // replace the new data in the content
 }

 }
return $text;

}


// Uniinstallation
function link_replacer_uninstall () {
	delete_option('link_replacer_options');
}


// register the plugin in the post content
add_filter('the_content',link_replacer);


?>