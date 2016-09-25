<?php
/**
 * Class file for the Link and obfuscate telephone numbers Wordpress plugin
 * LAOTN = Link and Obfuscate Telephone Numbers
 *
 * @author  John Evans<john@grandadevans.com>
 * @since   0.2
 * @version 1.4.1
 * @package Link and Obfuscate Telephone Numbers Wordpress Plugin
 *
 */
class LAOTN
{

    /**
     * This method is here purely to act as a link/shortcut to the main method
     * below. I've had feedback that a more verbose shortcode would be better
     * and so I've simply added one to the script.
     *
     * @param array $args The array of information passed to the plugin by
     *              Wordpress
     * @return      Returns the finished obfuscated article whether it be a link
     *              not
     * @since       1.1
     * @static
     *
     */
    public static function linkToAction($args)
    {
        //Get values from shortcode and put them in local variables
        extract
        (
            shortcode_atts
            (
                array
                (
                   'tel'                   => "",
                   'link'                  => true,
                   'debug'                 => false,
                   'link_text'             => "",
                   'non_mobile_text'       => "",
                   'css_class'             => "",
                   'mobile_css_class'      => "",
                   'non_mobile_css_class'  => "",
                   'use_htmlentities'      => true,
                   'use_noscript_fallback' => true,
                   'noscript_message'      => __("Please enable JavaScript to see this field.", "tel-obfuscate-shortcode")
          ),
                $args
      )
  );

  $out = do_shortcode('[link_obfuscate_telephone tel="' . $tel . '" link="' . $link . '" debug="' . $debug . '" link_text="' . $link_text . '" non_mobile_text="' . $non_mobile_text . '" use_html_entities="' . $use_htmlentities . '" use_noscript_fallback="' . $use_noscript_fallback . '" noscript_message="' . $noscript_message . '" css_class="' . $css_class . '" mobile_css_class = "' . $mobile_css_class . '" non_mobile_css_class = "' . $non_mobile_css_class . '"]');

        return $out;
    }

    public static function action($args)
    {
        // Set up a few variables that we will need
        $debug_text = "Starting Debugging Script...\n";

        //Get values from shortcode and put them in local variables
        extract
        (
            shortcode_atts
            (
                array
                (
                   'tel'                   => "",
                   'link'                  => true,
                   'debug'                 => false,
                   'link_text'             => "",
                   'non_mobile_text'       => "",
                   'css_class'             => "",
                   'mobile_css_class'      => "",
                   'non_mobile_css_class'  => "",
                   'use_htmlentities'      => true,
                   'use_noscript_fallback' => true,
                   'noscript_message'      => __("Please enable JavaScript to see this field.", "tel-obfuscate-shortcode")
          ),
                $args
      )
  );


  if(defined(LAOTN_DEBUG) || $debug == true) {
      define("DEBUG", true);
  } else {
      define("DEBUG", false);
  }
        // Return with an error if the telephone number is not set or is not a
        // string
        if(! (string) $tel || strlen($tel) == 0)
        {
            return __("You have not entered a telephone number for this shortcode.", "tel-obfuscate-shortcode");
        } else {

            //Init return variable
            $ret = (string) trim($tel);

            $debug_text .= "Telephone number has been set to " . $ret . "\n";

            // Set link text
            if (isset($link_text) && strlen($link_text) > 0)
            {
                $debug_text .= "Link text has been to " . $link_text . "\n";
            } else {
                $ret = $tel;
                $link_text = $ret;
                $debug_text .= "The link text is either not a string or is empty\n";
            }

            // Create a new instance of mdetect
            require_once(LAOTNPATH . 'includes/mdetect.php');

            $mdetect = new uagent_info;

            if (isset($non_mobile_text) && strlen($non_mobile_text) > 0 && !$mdetect->DetectMobileQuick())
            {
                $ret = $non_mobile_text;
            }

            if ($mdetect->DetectMobileQuick() == true)
            {
                $debug_text .= "Mobile device has been detected\n";
            } else {
                $debug_text .= "Mobile device has not been detected\n";
            }

            if ($link == true)
            {
                $debug_text .= "Linking is enabled\n";
            } else {
                $debug_text .= "Linking is not enabled\n";
            }

            //Wrap in tel: link
            if ($link == true && ($mdetect->DetectMobileQuick() == true))
            {
                $ret = '<a href="tel:' . $ret . '">' . $link_text . ' </a>';

                $debug_text .= "As a mobile device has been detected a link is now being presented and not simply the tele0phone number\n";

            } else {

                $debug_text .= "As a mobile device has not been detected the telephone number is being presented and not a link\n";

            }

            $_SESSION['debug_text'] = $debug_text;

            //Encode as htmlentities
            if($use_htmlentities === "1")
            {
                // About to run a function so put the contents of the debug_text var on the session
                $_SESSION['debug_text'] = $debug_text;

                $ret = LAOTN::html_entities_all($ret);

                $debug_text = $_SESSION['debug_text'];


                $debug_text .= "htmlentities has been selected and the new return value is " . $ret . "\n";

            } else {

                $debug_text .= "htmlentities has not been set\n";
            }

            $_SESSION['debug_text'] = $debug_text;

            //Convert to JS snippet
            $ret = LAOTN::safe_text($ret);

            $debug_text = $_SESSION['debug_text'];


            if ($use_noscript_fallback == true)
            {
                $debug_text .= "No script fallback text is set at <noscript>" . $noscript_message . "</noscript>\n";
            } else {

                $debug_text .= "No script fallback tag will be presented\n";

            }

            //Add noscript fallback
            if($use_noscript_fallback == true)
            {
                $ret .= '<noscript>' . $noscript_message . '</noscript>';
            }

            if(defined(LAOTN_DEBUG) || DEBUG == true)
            {
                $ret .= '<pre>
                        ' . $debug_text . '
                </pre>';
            }

            // Add a custom class if one is specified
            // @ since 1.4
            if (isset($css_class) && strlen($css_class) > 0)
            {
                $class_to_apply = $css_class;
            }

            if (isset($mobile_css_class) && strlen($mobile_css_class) > 0 && $mdetect->DetectMobileQuick() == true)
            {
                $class_to_apply .= ' ' . $mobile_css_class;
            }

            if (isset($non_mobile_css_class) && strlen($non_mobile_css_class) > 0 && $mdetect->DetectMobileQuick() == false)
            {
                $class_to_apply .= ' ' . $non_mobile_css_class;
            }

            if (isset($class_to_apply))
            {
                $ret = '<span class="' . $class_to_apply . '">' . $ret . '</span>';
            }

        }
        return $ret;


    }


    /**
    * Encodes every character in $text into its numeric html representation.
    * http://stackoverflow.com/questions/3005116/how-to-convert-all-characters-to-their-html-entity-equivalent-using-php/3005240
    *
    * @param string    String which should be encoded
    * @return          Returns the fully HTML encoded string
    * @since           0.2
    * @static
    */
    protected static function html_entities_all($text)
    {
        $debug_text = $_SESSION['debug_text'];

        $debug_text .= "About to htmlencode all characters\n";

        $text = mb_convert_encoding($text , 'UTF-32', 'UTF-8');
        $t = unpack("N*", $text);
        $t = array_map(array('LAOTN', 'html_entities_closure_wrap'), $t);

        $ret = implode("", $t);

        $debug_text .= $ret;

        $SESSION['debug_text'] = $debug_text;

        return $ret;
    }



    /**
    * This method has been added purely for servers that have a PHP version of
    * less that 5.3
    *
    * @param string    String to be wrapped
    * @return          Returns the completed string
    * @since           0.2
    * @static
    */
    protected static function html_entities_closure_wrap($n, $debug_text="")
    {
        return "&#$n;";
    }


    /**
    * The actual obfuscator function
    * http://khromov.wordpress.com/2011/10/04/php-function-for-scrambling-e-mail-addressesphone-numbers-using-javascript/
    *
    * @param string    Characters to Obfuscate
    * @return          Returns the obfuscated text
    * @since           0.2
    * @static
    **/
    protected static function safe_text($text)
    {

        $debug_text = $_SESSION['debug_text'];

        if (mb_detect_encoding($text, 'UTF-8', true))
        {
            $debug_text .= "Text is detected as UTF-8 <-- Good\n";
        } else {
            $debug_text .= "Text is detected as NOT-UTF-8 <-- Not Good\n";
        }

        //Check if text is UTF-8 and decode if it is
        if(mb_detect_encoding($text, 'UTF-8', true))
        {
            $text = utf8_decode($text);

            $debug_text  .= "Decoded text\n";
        } else {

            $debug_text .= "Encoding is not detected at UTF-8\n";

        }

        //Create the obfuscation array
        $chars = str_split($text);

        $debug_text .= "Stripped the characters\n";

        $enc[] = rand(0, 255);

        foreach($chars as $char)
        {
            $enc[] = ord($char) - $enc[ sizeof($enc) - 1 ];
        }

        $finished_array = join(',', $enc);

        $debug_text .= "t var has been finalised as " . $finished_array . "\n";

        $ret = 'var t=[' . $finished_array . ']; for (var i=1; i<t.length; i++) { document.write(String.fromCharCode(t[i]+t[i-1])); } ';

        $debug_text .= "The javascript that will obfuscate the text is set to:\n\n";
        $debug_text .= $ret;

        $_SESSION['debug_text'] = $_SESSION['debug_text'] . '&lt;script type="text/javascript"&gt;' . $debug_text . '&lt;script&gt;';

        return '<script type="text/javascript">' . $ret . '</script>';
    }

}

