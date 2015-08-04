<?php
namespace Smartsupp;

/**
 * Generates widget chat code for Smartsupp.com
 *
 * PHP version >=5.3
 *
 * @package    Smartsupp
 * @author     Marek Gach <gach@kurzor.net>
 * @copyright  since 2015 SmartSupp.com
 * @version    Git: $Id$
 * @link       https://github.com/smartsupp/chat-code-generator
 * @since      File available since Release 0.1
 */
class ChatGenerator
{
    /**
     * @var array Values which are allowed for language param.
     */
    protected $allowed_languages = array('en', 'fr', 'es', 'de', 'ru', 'cs', 'sk', 'pl', 'hu', 'cn', 'da', 'nl', 'it',
        'pt', 'hi', 'ro', 'no');

    /**
     * @var array Values which are allowed for ratingType param.
     */
    protected $allowed_rating_types = array('advanced', 'simple');

    /**
     * @var array Values which are allowed for alignX param.
     */
    protected $allowed_align_x = array('right', 'left');

    /**
     * @var array Values which are allowed for alignY param.
     */
    protected $allowed_align_y = array('side', 'bottom');

    /**
     * @var array Values which are allowed for widget param.
     */
    protected $allowed_widget = array('button', 'widget');

    /**
     * @var null|string Your unique chat code. Can be obtained after registration.
     */
    protected $key = null;

    /**
     * @var null|string By default chat conversation is terminated when visitor opens a sub-domain on your website.
     */
    protected $cookie_domain = null;

    /**
     * @var string Chat language. Can have any value from $this->allowed_language.
     */
    protected $language = 'en';

    /**
     * @var string Chat charset defaults to utf-8.
     */
    protected $charset = 'utf-8';

    /**
     * @var null|string Email (basic information).
     */
    protected $email = null;

    /**
     * @var null|string Customer name (basic information).
     */
    protected $name = null;

    /**
     * @var null|array contain additional user information.
     */
    protected $variables = null;

    /**
     * @var bool When the visitor ends the conversation a confirmation window is displayed. This flag defaults to true
     * and can be changed.
     */
    protected $send_email_transcript = true;

    /**
     * @var bool Indicate if rating is enabled.
     */
    protected $rating_enabled = false;

    /**
     * @var string Rating type.
     */
    protected $rating_type = 'simple';

    /**
     * @var bool Set if rating comment is enambled.
     */
    protected $rating_comment = false;

    /**
     * @var string Chat X align.
     */
    protected $align_x = 'right';

    /**
     * @var string Chat Y align.
     */
    protected $align_y = 'bottom';

    /**
     * @var int Chat X offset.
     */
    protected $offset_x = 10;

    /**
     * @var int Chat Y offset.
     */
    protected $offset_y = 100;

    /**
     * @var string Widget type.
     */
    protected $widget = 'widget';

    /**
     * @var null|string Google analytics key value.
     */
    protected $ga_key = null;

    /**
     * @var null|array Google analytics additional options.
     */
    protected $ga_options = null;

    /**
     * @var bool
     */
    protected $hide_widget = false;

    /**
     * Set chat language. Also is checking if language is one of allowed values.
     *
     * @param string $language
     * @throws \Exception when parameter value is incorrect
     */
    public function setLanguage($language)
    {
        if (!in_array($language, $this->allowed_languages)) {
            throw new \Exception("Language $language is not allowed value. You can use only one of values: " .
                implode(', ', $this->allowed_languages) . ".");
        }

        $this->language = $language;
    }

    /**
     * Set the charset. Check also if charset is allowed and valid value.
     *
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Allows to set Smartsupp code.
     *
     * @param string $key Smartsupp chat key.
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Smartsupp visitor is identified by unique key stored in cookies. By default chat conversation is terminated when
     * visitor opens a sub-domain on your website. You should set main domain as cookie domain if you want chat
     * conversations uninterrupted across your sub-domains. Insert the cookieDomain parameter in your chat code on main
     * domain and all sub-domains where you want the chat conversation uninterrupted.
     *
     * Example: Use value '.your-domain.com' to let chat work also on all sub-domains and main domain.
     *
     * @param string $cookie_domain
     */
    public function setCookieDomain($cookie_domain)
    {
        $this->cookie_domain = $cookie_domain;
    }

    /**
     * When the visitor ends the conversation a confirmation window is displayed. In this window there is by default a
     * button to send a transcript of the chat conversation to email. You can choose not to show this button.
     */
    public function disableSendEmailTranscript()
    {
        $this->send_email_transcript = false;
    }

    /**
     * After visitors ends a chat conversation, he is prompted to rate the conversation. Rating is disabled by default.
     * Together with enabling it you can set additional parameters.
     *
     * @param string $rating_type
     * @param boolean|false $rating_comment
     * @throws \Exception when parameter value is incorrect
     */
    public function enableRating($rating_type = 'simple', boolean $rating_comment = false)
    {
        if (!in_array($rating_type, $this->allowed_rating_types)) {
            throw new \Exception("Rating type $rating_type is not allowed value. You can use only one of values: " .
                implode(', ', $this->allowed_rating_types) . ".");
        }

        $this->rating_enabled = true;
        $this->rating_type = $rating_type;
        $this->rating_comment = $rating_comment;
    }

    /**
     * You can send basic information about web visitors from your database to Smartsupp (name, email). So your visitors
     * won't be anonymous and your chat agents will see info about every visitor, enabling agents to better focus on VIP
     * visitors and provide customized answers.
     *
     * @param $name User name.
     * @param $email User e-mail address.
     */
    public function setUserBasicInformation($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Will add additional parameter into Extra user info variables list.
     *
     * @param $id Parameter ID.
     * @param $label Parameter label.
     * @param $value Parameter value.
     */
    public function setUserExtraInformation($id, $label, $value)
    {
        $variable = array('id' => $id, 'label' => $label, 'value' => $value);

        $this->variables[] = $variable;
    }

    /**
     * By default the chat box is displayed in bottom right corner of the website. You can change the default position
     * along the bottom line or place the chat on right or left side of the website.
     *
     * @param string $align_x Align to right or left.
     * @param string $align_y Align to bottom or side.
     * @param int $offset_x Offset from left or right.
     * @param int $offset_y Offset from top.
     * @throws \Exception When params are not correct.
     */
    public function setBoxPosition($align_x = 'right', $align_y = 'bottom', int $offset_x = 10, int $offset_y = 100)
    {
        if (!in_array($align_x, $this->allowed_align_x)) {
            throw new \Exception("AllignX value $align_x is not allowed value. You can use only one of values: " .
                implode(', ', $this->allowed_align_x) . ".");
        }

        if (!in_array($align_y, $this->allowed_align_y)) {
            throw new \Exception("AllignX value $align_y is not allowed value. You can use only one of values: " .
                implode(', ', $this->allowed_align_y) . ".");
        }

        $this->align_x = $align_x;
        $this->align_y = $align_y;
        $this->offset_x = $offset_x;
        $this->offset_y = $offset_y;
    }

    /**
     * We supports two chat-box layouts, widget and button. By default is activated layout widget.
     *
     * @param string $widget Parameter value.
     * @throws \Exception when parameter value is incorrect
     */
    public function setWidget($widget = 'widget')
    {
        if (!in_array($widget, $this->allowed_widget)) {
            throw new \Exception("AllignX value $widget is not allowed value. You can use only one of values: " .
                implode(', ', $this->allowed_widget) . ".");
        }

        $this->widget = $widget;
    }

    /**
     * Smartsupp is linked with your Google Analytics (GA) automatically. Smartsupp automatically checks your site's
     * code for GA property ID and sends data to that account. If you are using Google Tag Manager (GTM) or you don't
     * have GA code directly inserted in your site's code for some reason, you have to link your GA account as described
     * here.
     * If you have sub-domains on your website and you are tracking all sub-domains in one GA account, use the gaOptions
     * parameter. You can find more info about gaOptions in Google Analytics documentation
     * (@see https://developers.google.com/analytics/devguides/collection/analyticsjs/advanced#customizeTracker).
     *
     * @param $ga_key Google analytics key.
     * @param array|null $ga_options Additional gaOptions.
     */
    public function setGoogleAnalytics($ga_key, Array $ga_options = null)
    {
        $this->ga_key = $ga_key;
        $this->ga_options = $ga_options;
    }

    /**
     * You can hide chat box on certain pages by setting this variable.
     */
    public function hideWidget()
    {
        $this->hide_widget = true;
    }
}
