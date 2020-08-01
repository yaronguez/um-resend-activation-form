=== Ultimate Member Resend Activation Form ===
Contributors: yaronguez
Donate link: https://www.paypal.me/TrestianLLC
Tags: ultimate-member
Requires at least: 3.0.1
Tested up to: 4.7
Stable tag: 'trunk'
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a [um_resend_activation_form] shortcode allowing users to resend their account activation email

== Description ==

This addon for Ultimate Member provides a form for users to resend their account activation email. This is
useful if the activation email has expired or has been lost. Simply add a [um_resend_activation_form] shortcode
to the page of your choice and specify the page in the _Setup_ section of the _Ultimate Member Settings_ page.
The form is submitted using AJAX.

Ultimate Member is required for this addon to work properly.

See FAQ for customization options.

NOTE: until this plugin is added to the WordPress repo, you will have to install the
(GitHub Updater plugin)[https://github.com/afragen/github-updater] for updates.


== Installation ==

1. Upload `um-resend-activation-form.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add a `[um_resend_activation_form]` shortcode to the page of your choice
1. Visit Ultimate Member -> Settings -> Setup and specify which page the shortcode was added to

== Frequently Asked Questions ==

= How do I display a link to the form somewhere else? =
Add the code: `<?php do_action('um_raf_show_resend_link'); ?> Optionally specify any CSS classes in the second argument, e.g.
`<?php do_action('um_raf_resend_link', 'my_class'); ?>`

= How do I override the styles you provided? =
Create a file called `um_raf.css` in the root of your theme directory. This file will be loaded
automatically after the plugin's own stylesheet.

= What hooks are available to customize the form? =
Many! Take a look at `templates/um-raf-output.php` to see them all.

= What filters are provided to customize the messages returned? =
* `um_raf_invalid_nonce_message`: error message displayed if nonce has expired
* `um_raf_no_user_message`: error message displayed if no user is found for the given email
* `um_raf_not_pending_message`: error message displayed if user's account does not require activation
* `um_raf_email_sent_message`: success message displayed when activation email is sent.
* `um_raf_no_um_message`: error message displayed instead of form if Ultimate Member plugin is not active.
* `um_raf_resend_form_link_text`: text used in link to the resend form

= Your hooks do not satisfy me! I want to create my own form =
Well, if you must. Just copy `um-raf-output.php` the `templates` directory and past it in the root folder of your theme.
Then feel free to customize away! However, tread carefully and be sure not to modify any of the element IDs.

== Changelog ==
= 1.0.2 =
* Applied WPCS validation
* Fixed the captcha errors

= 1.0.1 =
* Fixed the version conflicts
* Added captcha support

= 1.0 =
* Initial launch
