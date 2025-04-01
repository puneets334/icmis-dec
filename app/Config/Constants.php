<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);

define('E_FILING_URL', 'http://10.25.78.23:82'); 
define('LIVE_URL','https://main.sci.gov.in/');

/**
 * Define for Faster Module .
 */
define('DOCUMENT_ROP',162);
define('DOCUMENT_JUDGMENT',163);
define('DOCUMENT_MEMO_OF_PARTY',164);
define('DOCUMENT_SIGNED_ORDER',165);
define('LIVE_PATH','');//todo::enable when live
$rop_memo = array(DOCUMENT_ROP,DOCUMENT_JUDGMENT,DOCUMENT_SIGNED_ORDER,DOCUMENT_MEMO_OF_PARTY);
define('ROP_MEMO',serialize($rop_memo));
$documents_exempted_from_signing = array(DOCUMENT_ROP,DOCUMENT_JUDGMENT,DOCUMENT_SIGNED_ORDER);
define('DOCUMENT_EXEMPTED_FROM_SIGNING',serialize($documents_exempted_from_signing));
define('TEST',array(DOCUMENT_ROP,DOCUMENT_JUDGMENT,DOCUMENT_SIGNED_ORDER));

define('FASTER_STORAGE','/home/reports/supremecourt/faster_assets/');// - /home/reports/supremecourt/faster_assets/
define('FASTER_STORAGE_FOR_DB','/supremecourt/faster_assets/'); // -  /supremecourt/faster_assets/
define('WEB_ROOT', "http://".$_SERVER['HTTP_HOST']);
define('WEB_ROOT_URL', "http://".$_SERVER['HTTP_HOST']);
define('FILE_ROOT_PATH','/reports');
define('ICMIS_ROP_URL',"http://".$_SERVER['HTTP_HOST']."/reports/jud_ord_html_pdf/");
define('ICMIS_NOTICE_URL',"http://".$_SERVER['HTTP_HOST']."/reports/pdf_notices/");

define('ADD_DOCUMENTS',1);
define('DIGITAL_SIGNATURE',2);
define('DIGITAL_CERTIFICATION',3);
define('DOWNLOAD',4);
define('ADD_RECIPIENTS',5);
define('OTP_SENT',6);
define('OTP_RESEND',7);
define('OTP_VERIFY',8);
define('COMPLETE',9);
define('DELETE_ATTACHED_FILE',10);

// "Coping Mudules" Constant variables
define('GET_SERVER_IP','http://XXXX');
define('SCISMS_Consignment_dispatch','1107161234270560829'); //ok
define('SCISMS_e_copying_appli','1107161216076262823'); //ok
define('SMS_KEY', 'sdjkfgbsjh$1232_12nmnh');//key for 67 server kjuy@98123_-fgbvgAD and key for 60 server sdjkfgbsjh$1232_12nmnh
define('SMS_API_IP', 'xxxx');//to push sms access ip
define('SCISMS_Party_verification_rejected','1107161234229761818');//ok
define('SCISMS_Party_verification_completed','1107161234213568779');//ok
define('SCISMS_CHECK_EMAIL_FOR_CRN','1107161578937563092');
define('URL_SHORTNER_KEY', "zajkk60ldkq");
define('NEW_SMS_SERVER_HOST_JIO_URL', "https://uninotify.sci.gov.in/api/v1/send");
define('NEW_SMS_SERVER_HOST_JIO_IP', "http://10.192.105.124:91/api/v1/send");
define('LIVE_SMS_KEY_JIO_CLOUD', "sdfmsdbfjh327654t3ufb58");
define('LIVE_EMAIL_KEY_JIO_CLOUD', "sdfmsdbfjh327654t3ufb59");
define('NEW_MAIL_SERVER_HOST_JIO_URL', "https://uninotify.sci.gov.in/api/v1/send");
define('NEW_MAIL_SERVER_HOST_JIO_IP', "http://10.192.105.124:91/api/v1/send");
define('SMS_RESEND_LIMIT', 30);
define('LIVE_EMAIL_KEY', "cKLKqvPlW8");
define('SCISMS_e_copying_g_p','1107161216067222779');