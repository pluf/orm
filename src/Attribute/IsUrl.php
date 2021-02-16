<?php
namespace Pluf\Orm\Attribute;

class IsUrl
{
    
    
    /**
     * Validate an url.
     *
     * Only the structure is checked, no check of availability of the
     * url is performed. It is a really basic validation.
     */
    public static function isValidUrl($url)
    {
        $ip = '(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.' . '(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}';
        $dom = '([a-z0-9\.\-]+)';
        return (preg_match('!^(http|https|ftp|gopher)\://(' . $ip . '|' . $dom . ')!i', $url)) ? true : false;
    }
}

