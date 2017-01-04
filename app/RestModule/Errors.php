<?php

namespace DontPanic\RestModule;

class Errors
{
    const WORK_EXPERIENCE_NOT_FOUND = 'work.experience.not.found';
    const WORK_EXPERIENCE_NOT_CREATED = 'work.experience.not.createdAt';

    const COURSE_NOT_FOUND = 'course.not.found';
    const COURSE_NOT_CREATED = 'course.not.createdAt';

    const COMPANY_NOT_FOUND = 'companyString.not.found';

    const COUNTRY_NOT_FOUND = 'country.not.found';

    const CITY_NOT_FOUND = 'city.not.found';

    const USER_LANGUAGE_NOT_FOUND = 'user.language.not.found';
    const USER_LANGUAGE_NOT_CREATED = 'user.language.not.createdAt';
    const USER_LANGUAGE_EXISTS = 'user.language.exists';

    const USER_UNAUTHORIZED = 'user.unauthorized';

    private function __construct() { }
}