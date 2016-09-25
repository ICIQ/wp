=== Link/Obfuscate Telephone Numbers ===
Contributors: grandadevans
Donate link: http://grandadevans.com/resources/wordpress-plugin-to-link-and-obfuscate-telephone-numbers/
Tags: obfuscate,link,telephone,tel:,mobile,cell,smartphone,smart-phone
Requires at least: 3.0
Tested up to: 3.7.1
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A shortcode to link to telephone numbers if the user is on a smartphone or leave plain text if not and then obfuscate the text/link. Example [link_obfuscate_telephone tel="1234567890"] will both obfuscte and link where needed. New in version 1.3 is the ability to present different text to non-smartphone users (eg. your phone number in a different format). New in version 1.4 you can now add a custom class to the output as well as an option to provide a custom class to both mobile and non mobile devices.

== Description ==

This plugin will convert telephone numbers to callable links if the user is on
a smartphone. If the user is not on a smartphone you can either give them custom text/format or leave the telephone number in plain text. The text/link telephone number is then obfuscated to
reduce the risk of spambots intercepting your number.

It is also now possible to use the shortcode
<code>[link_obfscate_telephone tel="???"]</code>
and
<code>[link_and_obfuscate_telephone_number tel="???"]</code>

The following options are avaiable:

*  Add custom link text such as "Call Me!"
*  Add custom text for non-smartphone user (eg. different format)
*  Add a custom general CSS class to the output (eg class="telephone number")
*  Add a class for mobile only devices (eg class="mobile_device")
*  Add a class for no mobile devices (eg class="non_mobile_devices")
*  Turn the link feature off so that just plain telephone numbers are displayed
*  Turn the HTML_entities option on or off
*  Turn noscript on or off
*  Add a custom noscript message

This plugin is based upon and a re-write of the plugin at http://wordpress.org/extend/plugins/email-obfuscate-shortcode/

== Installation ==
To use this plugin you can:
1.  Upload the plugin folder to your "wp-content/plugins/" folder
1.  Search from within the plugin screen in your admin pages for "Link/Obfuscate Telephone Numbers"
You should then activate the plugin from within the plugin admin screen

To use the plugin place the shortcode [link_and_obfuscate_telephone_number] or
[link_obfuscte_teleophone] where you want your code to appear. Inside the shortcode should be a minimum
of a "tel" tag containing the telephone number you wish to act upon. Other options available are:

*  tel --> The number to you want to action
*  link --> enter link="0" to disable links
*  debug --> debug="1" will output debugging information
*  link_text --> Will change the link text eg link="Call Me"
*  non_mobile_text --> display different text/a different format to the user eg non_mobile_text= "+44 (0) 1226 123456"
*  css_class --> Surround the output in a custom class
*  mobile_css_class --> Apply a custom class to mobile only devices
*  non_mobile_css_class --> Apply a custom class to to non mobile devices
*  use_noscript_fallback --> Turn off the <noscript> message
*  noscript_message --> customise the noscript message

Simple example
<code>
[link_and_obfuscate_telephone_number tel="1234567890"]
</code>

As an example you could use:

<code>
[link_and_obfuscate_telephone_number
    tel="1234567890"
    link="1"
    debug="0"
    link_text="Call Me"
    non_mobile_text="+44 (0) 01226 1234567"
    css_class="my_phone_number"
    mobile_css_class="mobile_device"
    non_mobile_css_class="non_mobile_device"
    use_noscript_fallback="1"
    noscript_message="JS not enabled"]
</code>

You may or may not be able to write the shortcode in the multiline format depending on your system configuration.
If you are unable to then simply list the shortcode attributes on a single line

== Frequently Asked Questions ==

= Why should I obfuscate telephone numbers? =
Telephone numbers are as succeptable to spambots as emails are and it makes
perfect sense to obfuscate telephone numbers if you are obfuscating email
addresses

= Why create links on smartphones? =
Modern Smartphones are capable of recognising tel: links that can take the
referenced telephone number and place a call to it, therefore saving you the
trouble of writing a telephone number down on one screen to switch to the
dialler and enter it in there.

== Screenshots ==

1. This shows how the plugin affects numbers on a non-mobile device in the plaintext format (custom non_mobile_text screenshot to follow).
2. this shows how the plugin affects numbers on a mobile device.
3. This shows the result of clicking on either of the links in the previous mobile screen.
4. This shows the resulting HTML from both the non-mobile device.

== Upgrade Notice ==

= 1.4.1 =
* Added mobile_css_class and non_mobile_css_class to the previous entry

= 1.4 =
* Added class property that wraps the output to allow styling of links or text

= 1.3 =
* Tested on Wordpress version 3.7
* Added non_mobile_text attribute to display a custom message or a custom format to non smartphone users

= 1.2 =
* Much better debugging facilities

= 1.1 =
* You can now use both shortcodes link_and_obfuscte_telephone_number and link_obfuscate_telephone

= 1.0 =
* Code now fully up to date

= 0.2 =
* Any version prior to this are not listed in the Wordpress Plugin Directory

== Changelog ==

= 1.4.1 =
* Added mobile_css_class and non_mobile_css_class to the previous entry

= 1.4 =
* Added class property that wraps the output to allow styling of links or text

= 1.3 =
* Tested on Wordpress version 3.7
* Added non_mobile_text attribute to display a custom message or a custom format to non smartphone users

= 1.2 =
* Much better debugging facilities

= 1.1.1 =
* Copied readme description over to php file

= 1.1 =
* Upgraded code to enable the use of a more verbose or less verbose shortcode
* Added example to short description so that it's obvious how to use it from the plugin page on readme page
* Made clear in readme that it cannot be a multi-line code

= 1.0 =
* Plugin accepted to Wordpress Plugin Directory so this is verion 1.0

= 0.2 =
* Upgraded to deccent folder structure and set to upgradable format
* Re-wrote readme file

