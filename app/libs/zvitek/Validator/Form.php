<?php

namespace zvitek\Validator;

class Form
{

    const INPUT_DIACRITICS_CHARTS_PATTERN = '[a-zA-ZěščřžýáíéďťňúůĚŠČŘŽÝÁÍÉĎŤŇÚŮ ]+';
    const INPUT_NAME_AND_SURNAME_PATTERN  = '\S{2,}( \S{2,})+';
    const INPUT_PHONE_PATTERN             = '([0-9]\s*){9}';
}