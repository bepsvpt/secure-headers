<?php

$SecureHeaders = require_once dirname(__FILE__) . '/secure-headers.php';

/**
 * The output content is used for .env file settings and is for auxiliary purposes
 * @param array|bool|string $SecureHeaders
 * @param string $subKey
 */
function outputEnvKeyVal($SecureHeaders, string $subKey = '')
{
    echo ($subKey != '') ? ('<br><br>###### ' . $subKey . '------') : '';
    foreach ($SecureHeaders as $item => $value) {
        if (is_array($value)) {
            if (count($value) === 0) {
                echo '<br>' . $subKey . $item . '=' . '[]';
            } else {
                $sub = ($subKey != '') ? $subKey : '';
                outputEnvKeyVal($value, $sub . $item . '---');
            }
        } elseif (is_bool($value)) {
            echo '<br>' . $subKey . $item . '=' . ((boolean)$value === true ? 'true' : 'false');
        } else {
            echo '<br>' . $subKey . $item . '=' . (string)$value;
        }
    }
}

outputEnvKeyVal($SecureHeaders);
