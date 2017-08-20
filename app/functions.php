<?php
/**
 * Generic functions to use throughout
 *
 * $di is available here from public/index.php
 */

/**
 * Gets the base URL
 *
 * @param  string $append    Add to URL (Don't include a starting /)
 *
 * @return string
 */
function getBaseUrl($append = false)
{
    $base_url = preg_replace('/(^https?)+(:\/{2})/i', '', URL);
    $url = \HTTPS ? 'https://' : 'http://';

    return $url . rtrim($base_url, '/') . '/' . ltrim($append);
}

// --------------------------------------------------------------

/**
 * Cleans up a source name for display
 *
 * @param  [type] $name [description]
 * @return [type]       [description]
 */
function formatName($name)
{
    $name = str_replace('-', ' ', $name);
    $name = ucwords($name);
    return $name;
}

// --------------------------------------------------------------

/**
 * Set form Data for a page refresh.
 *
 * @param  string $name  field name
 *
 * @return mixed
 */
function formData($name) {
    if (!isset($_SESSION)) {
        return false;
    }

    if (isset($_SESSION['formData']) && isset($_SESSION['formData'][$name])) {
        return $_SESSION['formData'][$name];
    }

    return false;
}

// --------------------------------------------------------------

/**
 * Clear Form Data
 *
 * @return bool
 */
function formDataClear()
{
    if (!isset($_SESSION)) {
        return false;
    }

    if (isset($_SESSION['formData'])) {
        unset($_SESSION['formData']);
        return true;
    }

    return false;
}

// --------------------------------------------------------------

/**
 * Get the current DateTime (SQL Friendly)
 *
 * @return string
 */
function getDateTime()
{
    return date('Y-m-d H:i:s');
}

// --------------------------------------------------------------

function getTimeElapsed($datetime, $full = false) {
    $now = new \DateTime;
    $ago = new \DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
    {
        $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


// --------------------------------------------------------------

/**
 * A simple print_r Shortcut
 *
 * @param  mixed $data
 *
 * @return void
 */
function pr($data)
{
    echo '<pre>';
    print_r($data);
    die('</pre>');
}

// --------------------------------------------------------------

/**
 * A simple var_dump Shortcut
 *
 * @param  mixed $data
 *
 * @return void
 */
function vd($data)
{
    echo '<pre>';
    var_dump($data);
    die('</pre>');
}

