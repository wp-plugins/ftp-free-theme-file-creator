<?php
/*
Plugin Name: FTP-Free Theme File Creator
Plugin URI: http://dandyplow.com/ftp-free
Description: WP-Admin lets us upload and create new posts, pages, media, and more, so why do we have to log into our FTP or hosting provider's file manager every time we need to create a new theme template file?  This simple plugin adds a simple, non-invasive form under existing theme files in the theme editor.  Type in a name for the template file, click the submit button, and you're set!
Version: 1.0
Author: P.G. McCullough
Author URI: http://p.mccullo.ug
License: GPL2
*/

/*  Copyright 2014 P.G. McCullough  (email : patrick@dandyplow.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(is_admin()) {
function ftpf_create_template_file(){
if($_GET['add_template_file'] == 1) {
$cleanfilename = str_replace(".php", "FXDOTPHPFX", $_POST['cleanname']);
$cleanfilename = str_replace("-", "FXDASHFX", $cleanfilename);
$cleanfilename = preg_replace("/[^A-Za-z0-9 ]/", '', $cleanfilename);
$cleanfilename = str_replace("FXDOTPHPFX", ".php", $cleanfilename);
$cleanfilename = str_replace("FXDASHFX", "-", $cleanfilename);
file_put_contents(get_template_directory().'/'.$cleanfilename,"<?php
/*
Template Name: ".$_POST['cleantitle']."
*/ ?>");
$newlocale = site_url().'/wp-admin/theme-editor.php?file='.$cleanfilename.'&theme='.$_GET['theme'].'&ftpf_create=1'; ?>
<script>
window.location.replace('<?php echo $newlocale; ?>');
</script>
<?php }
}

add_action('admin_head', 'ftpf_create_template_file');

add_action('admin_footer', 'ftpf_add_template_file');

function ftpf_add_template_file() { 
wp_enqueue_script('jquery');
if (strpos($_SERVER['REQUEST_URI'],'theme-editor.php') !== false) {
echo '
<script>
jQuery("#templateside").append("<h3>Add Template File</h3><br /><form method=\"post\" action=\"?add_template_file=1&theme='.$_GET['theme'].'\"><input name=\"cleantitle\" type=\"text\" oninput=\"if((this.value)==\'\') {jQuery(\'#newfilename\').html(\'(*.php)\');} else {jQuery(\'#newfilename\').html(\'(\'+this.value.toLowerCase().replace(/ /g, \'-\')+\'.php)\'); jQuery(\'#hiddencleanname\').val(jQuery(\'#newfilename\').html())}\"><span style=\'margin-left: 12px;\' class=\'nonessential\' id=\'newfilename\'>(*.php)</span><br /><br /><input type=\"hidden\" name=\"cleanname\" id=\"hiddencleanname\"><input type=\"submit\" class=\"button button-primary\" value=\"Create File\"></form>");
</script>';
}
if($_GET['ftpf_create']==1) { ?>
<script>
jQuery('<div id="message" class="updated"><p>File created successfully.</p></div>').insertBefore('.wrap');
</script>
<?php };
}}; ?>