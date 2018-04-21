# Insta Posts By Taylrr
Contributors: Alex Taylor (taylor894)  
Link: http://taylrr.co.uk/  
Tags: instagram, widget, footer, automatic, update  
Requires at least:  
Tested up to: 4.9.5  
Stable tag: trunk  
License: GPLv3  
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html  

<hr>

A lightweight Wordpress plugin for pulling in and displaying images from any public Instagram account.

## Description ##

A fully-functional lightweight Wordpress plugin that can display images from any public Instagram account you specify, caches images for a given amount of time and will automatically update with new images after that cache expires.

Other features include:

*   Predictive auto-fill when entering your Instagram account, with profile pictures and full names.
*   Displays images in a "width-filling" responsive grid layout, where the number of rows and columns can be customised.
*   Wordpress widget included for easily adding a grid of your Instagram images to your sidebar.
*   Shortcode provided so you can display images anywhere and easily add to your theme files with `do_shortcode`.
*   Support for any number of accounts via the `user` parameter of the shortcode.
*   Includes CSS for a row of images along your footer, just add a simple snippet to your theme's `footer.php` file - see below for more.

## Installation ##

To install the plugin and get it working, follow the step below.

1. Upload the `taylrr-insta-posts` folder to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' page in WordPress
3. Navigate to the settings page either by clicking 'Insta Posts' in the left menu or clicking 'Settings' under the 'Insta Posts by Taylrr' entry on the 'Plugins' page
4. Enter the Instagram account you want the plugin to use by default, and optionally change the cache time.
5. Finally, simply add the widget where you would like to show images, choosing the number of rows and columns in the widget settings as desired.

## Screenshots ##

A screenshot of the 'Insta Posts' settings page.

![](/taylrr-insta-posts/assets/screenshot-1.png?raw=true "Insta Posts settings page")

The 'Insta Posts' widget.

![](/taylrr-insta-posts/assets/screenshot-2.png?raw=true "Insta Posts widget")

## Changelog ##

### 1.0 ###
* Initial release.

## How to use ##

To start using the 'Insta Posts' plugin, simply

 * Add the Insta Posts widget to your sidebar from the widget settings, and/or
 * Use the `[insta_posts]` shortcode wherever you would like to display images from your instagram feed.
 * All customisable behaviour available within the widget options is also available to alter the behaviour of the shortcode, just pass the desired values to the following within the shortcode
   * `num` -- The total number of images to display, maximum is 12
   * `row` -- The number of images to display in one row
   * `padding` -- The spacing between each image in a row and between rows
   * `user` -- You can even set the user for each shortcode individually, if specified this username will take precedent over the default user saved in the settings, if it is not the default user is always assumed.

For example, the default behaviour would be `[insta_posts num=6 row=2 padding=5]`.

To add a row of images to your footer, simply add the following line to the relevant part of your `footer.php` file.

```html
<div class="insta-posts-footer">
  <?php do_shortcode('[insta_posts num=8 row=8 padding=0]'); ?>
	<a href="//instagram.com/YOUR_USERNAME/" rel="me" target="_blank" class="insta-posts-footer-overlay"><i class="fa fa-instagram"></i> Follow us!</a>
</div>
```
