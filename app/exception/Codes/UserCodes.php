<?php
namespace DontPanic\Exception\Code;

/**
 * Class UserCodes
 *
 * @range 10 000 - 19 999
 * @package DontPanic\Exception\Code
 */
class UserCodes
{

    // MISSING (10 000 - 11 999)
    const MISSIGN_PARAMETER_EMAIL    = 10001;
    const MISSIGN_PARAMETER_PASSWORD = 10002;

    // MALFORMED (12 000 - 12 999)
    const MALFORMED_EMAIL    = 12000;
    const MALFORMED_PASSWORD = 12001;

    // NOT FOUND (13 000 - 14 999)
    const EMAIL_NOT_FOUND = 13000;
    const USER_NOT_FOUND  = 13001;

    // CREATE (15 000 - 15 999)
    const ACCOUNT_CREATE = 15000;

    // AUTHORIZATION (16 000 - 16 999)
    const MISSING_AUTH_TOKEN = 16000;
    const MISSING_AUTH_USER  = 16001;

    // OCCUPIED (17 000 - 17 999)
    const OCCUPIED_EMAIL = 17000;

    // PATTERN (18 000 - 18 999)
    const PATTERN_PASSWORD = 18000;
}