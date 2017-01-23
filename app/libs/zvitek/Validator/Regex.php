<?php

namespace zvitek\Validator;

class Regex
{

    /** @TODO set correctly regex */
    const PASSWORD = '/[A-Z]+[a-z]+[0-9]+/';
    const PHONE    = '([0-9]\s*){9}';
    const SMS_CODE = '([0-9]){6}';
}