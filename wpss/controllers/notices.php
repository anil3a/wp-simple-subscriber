<?php 

/**
 *
 * Notice for subscriber actions.
 *
 * @package Controllers
 * @subpackage Notices
 *
**/

// Namespace.
namespace WPSS\Controllers;

// Prevent direct unless 'ajaxrequest' is set.
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) && !isset($_REQUEST['ajaxrequest'])){
    die('Sorry. This file cannot be loaded directly.');
}


class Notices
{
    /**
     * Instance
     * Instance for this class.
     *
     * @access static
     * @param null
     * @return null
     * @since 1.2.0
     * @version 1.2.0
    **/
    private static $instance;

    /**
     * Constant
     * Constant for this class.
     *
     * @since 1.2.0
     * @version 1.2.0
    **/
    const NOTICE_FIELD = 'wpss_admin_notice_message';

    /**
     * __contructor
     * Contructor for this class.
     *
     * @access protected
     * @param null
     * @return null
     * @since 1.2.0
     * @version 1.2.0
    **/
    protected function __construct() {}
    
    /**
     * __clone
     * Clone for this class.
     *
     * @access private
     * @param null
     * @return null
     * @since 1.2.0
     * @version 1.2.0
    **/
    private function __clone() {}

    /**
     * __wakeup
     * Wakeup for this class.
     *
     * @access private
     * @param null
     * @return null
     * @since 1.2.0
     * @version 1.2.0
    **/
    private function __wakeup() {}

    /**
     * getInstance
     * Getting Instace of this class.
     *
     * @access static
     * @param null
     * @return null
     * @since 1.2.0
     * @version 1.2.0
    **/
    static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * displayNotice
     * Main Displaying Notice of this class.
     *
     * @access public
     * @param null
     * @return string
     * @since 1.2.0
     * @version 1.2.0
    **/
    public function displayNotice()
    {
        $option      = get_option(self::NOTICE_FIELD);
        $message     = isset($option['message']) ? $option['message'] : false;
        $noticeLevel = ! empty($option['notice-level']) ? $option['notice-level'] : 'notice-error';

        if ($message) {
            echo "<div class='notice {$noticeLevel} is-dismissible'><p>{$message}</p></div>";
            delete_option(self::NOTICE_FIELD);
        }
    }

    /**
     * displayError
     * Display error with message of this class.
     *
     * @access public
     * @param string $message
     * @return string
     * @since 1.2.0
     * @version 1.2.0
    **/
    public function displayError($message)
    {
        $this->updateOption($message, 'notice-error');
    }

    /**
     * displayWarning
     * Display warning with message of this class.
     *
     * @access public
     * @param string $message
     * @return string
     * @since 1.2.0
     * @version 1.2.0
    **/
    public function displayWarning($message)
    {
        $this->updateOption($message, 'notice-warning');
    }

    /**
     * displayInfo
     * Display information with message of this class.
     *
     * @access public
     * @param string $message
     * @return string
     * @since 1.2.0
     * @version 1.2.0
    **/
    public function displayInfo($message)
    {
        $this->updateOption($message, 'notice-info');
    }

    /**
     * displaySuccess
     * Display success with message of this class.
     *
     * @access public
     * @param string $message
     * @return string
     * @since 1.2.0
     * @version 1.2.0
    **/
    public function displaySuccess($message)
    {
        $this->updateOption($message, 'notice-success');
    }

    /**
     * updateOption
     * Using updateoption to display message of this class.
     *
     * @access protected
     * @param string $message
     * @param string $noticeLevel
     * @return string
     * @since 1.2.0
     * @version 1.2.0
    **/
    protected function updateOption($message, $noticeLevel) {
        update_option(self::NOTICE_FIELD, [
            'message' => $message,
            'notice-level' => $noticeLevel
        ]);
    }
}

 ?>