<?php

/**
 * [!!] Note: If you want to make modifications to the default configurations,
 * it is highly recommended to copy this file into your applications config
 * folder and change them there.
 *
 * Doing this will allow you to upgrade your installation without losing custom
 * configurations.
 */

/**
* Session Configuration
*
* This configuration file handles all of the data needed to work with Nerd's
* session class.
*
* @var    array
*/
return [
    /**
     * Are session enabled?
     *
     * @var boolean
     */
    'enabled' => true,

    /**
     * Session handler to use for this application
     *
     * @var string
     */
    'handler' => '\\Nerd\\Session\\Handler\\File',

    /**
     * Session Lifetime
     *
     * If a session is not updated by the time (in seconds) lifetime has elapsed
     * the session will be destroyed.
     *
     * @var integer
     */
    'lifetime' => 7200, // 2 hours

    /**
     * Enable flash session support
     *
     * Session flash provides the ability to send messages between two requests.
     *
     * @var    boolean
     */
    'useFlash' => true,

    /**
     * Session name
     *
     * The session name references the name of the session, which is used in cookies
     * and URLs (e.g. PHPSESSID). It should contain only alphanumeric characters; it
     * should be short and descriptive (i.e. for users with enabled cookie warnings).
     * If name is specified, the name of the current session is changed to its value.
     *
     * If left null, the default (PHPSESSID) will be used.
     *
     * @var    string
     */
    'name' => 'NERDSESS',

    /**
     * Enable use of cookies with sessions
     *
     * If set to true, then cookies will be used to keep session data active between
     * the user's page refreshes.
     *
     * @var    boolean
     */
    'useCookies' => true,

    'cookieLifetime' => 1209600,

    'gc' => [

        'probability' => 50,

        'divisor' => 100,
    ],
];
