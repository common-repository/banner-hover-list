=== Banner Hover List ===
Contributors: Module Express
Donate link: http://beautiful-module.com/
Tags: responsive Banner Hover List,Banner Hover List,mobile touch Banner Hover List,image slider,responsive header gallery slider,responsive banner slider,responsive header banner slider,header banner slider,responsive slideshow,header image slideshow
Requires at least: 3.5
Tested up to: 4.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add an Responsive header Banner Hover List OR Responsive Banner Hover List inside wordpress page OR Template. Also mobile touch Banner Hover List

== Description ==

This plugin add a Responsive Banner Hover List in your website. Also you can add Responsive Banner Hover List page and mobile touch slider in to your wordpress website.

View [DEMO](http://beautiful-module.com/demo/banner-hover-list/) for additional information.

= Installation help and support =
* Please check [Installation and Document](http://beautiful-module.com/documents/wordpress/banner-hover-list.docx/)  on our website.

The plugin adds a "Responsive Banner Hover List" tab to your admin menu, which allows you to enter Image Title, Content, Link and image items just as you would regular posts.

To use this plugin just copy and past this code in to your header.php file or template file 
<code><div class="headerslider">
 <?php echo do_shortcode('[sp_banner.hover.list]'); ?>
 </div></code>

You can also use this Banner Hover List inside your page with following shortcode 
<code>[sp_banner.hover.list] </code>

Display Banner Hover List catagroies wise :
<code>[sp_banner.hover.list cat_id="cat_id"]</code>
You can find this under  "Banner Hover List-> Gallery Category".

= Complete shortcode is =
<code>[sp_banner.hover.list cat_id="9" autoplay="true" autoplay_interval="3000"]</code>
 
Parameters are :

* **limit** : [sp_banner.hover.list limit="-1"] (Limit define the number of images to be display at a time. By default set to "-1" ie all images. eg. if you want to display only 5 images then set limit to limit="5")
* **cat_id** : [sp_banner.hover.list cat_id="2"] (Display Image slider catagroies wise.) 
* **autoplay** : [sp_banner.hover.list autoplay="true"] (Set autoplay or not. value is "true" OR "false")
* **autoplay_interval** : [sp_banner.hover.list autoplay="true" autoplay_interval="3000"] (Set autoplay interval)

= Features include: =
* Mobile touch slide
* Responsive
* Shortcode <code>[sp_banner.hover.list]</code>
* Php code for place image slider into your website header  <code><div class="headerslider"> <?php echo do_shortcode('[sp_banner.hover.list]'); ?></div></code>
* Banner Hover List inside your page with following shortcode <code>[sp_banner.hover.list] </code>
* Easy to configure
* Smoothly integrates into any theme
* CSS and JS file for custmization

== Installation ==

1. Upload the 'banner-hover-list' folder to the '/wp-content/plugins/' directory.
2. Activate the 'Banner Hover List' list plugin through the 'Plugins' menu in WordPress.
3. If you want to place Banner Hover List into your website header, please copy and paste following code in to your header.php file  <code><div class="headerslider"> <?php echo do_shortcode('[sp_banner.hover.list limit="-1"]'); ?></div></code>
4. You can also display this Images slider inside your page with following shortcode <code>[sp_banner.hover.list limit="-1"] </code>


== Frequently Asked Questions ==

= Are there shortcodes for Banner Hover List items? =

If you want to place Banner Hover List into your website header, please copy and paste following code in to your header.php file  <code><div class="headerslider"> <?php echo do_shortcode('[sp_banner.hover.list limit="-1"]'); ?></div>  </code>

You can also display this Banner Hover List inside your page with following shortcode <code>[sp_banner.hover.list limit="-1"] </code>



== Screenshots ==
1. Designs Views from admin side
2. Catagroies shortcode

== Changelog ==

= 1.0 =
Initial release