<?php
/**
 * Generic functions to use throughout
 *
 * $di is available here from public/index.php
 */

/**
 * Converts an Object to Array - Needed at times due to Phalcons specialized classes
 *
 * @param $obj Object
 *
 * @return array
 */
function objectToArray($object): array
{
    return json_decode(json_encode($object), true);
}

/**
 * Cleans up a source name for display
 *
 * @param  [type] $name [description]
 *
 * @return [type]       [description]
 */
function formatName($name)
{
    $name = str_replace('-', ' ', $name);
    $name = ucwords($name);

    return $name;
}

/**
 * Set form Data for a page refresh.
 *
 * @param  string $name field name
 *
 * @return mixed
 */
function formData($name)
{
    if ( ! isset($_SESSION)) {
        return false;
    }

    if (isset($_SESSION[ 'formData' ]) && isset($_SESSION[ 'formData' ][ $name ])) {
        return $_SESSION[ 'formData' ][ $name ];
    }

    return false;
}

/**
 * Clear Form Data
 *
 * @return bool
 */
function formDataClear(): bool
{
    if ( ! isset($_SESSION)) {
        return false;
    }

    if (isset($_SESSION[ 'formData' ])) {
        unset($_SESSION[ 'formData' ]);

        return true;
    }

    return false;
}

/**
 * Get the current DateTime (SQL Friendly)
 *
 * @return string
 */
function getDateTime(): string
{
    return date('Y-m-d H:i:s');
}

/**
 * @param      $datetime
 * @param bool $full
 *
 * @return string
 */
function getTimeElapsed($datetime, $full = false): string
{
    $now  = new DateTime;
    $ago  = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    ];
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[ $k ]);
        }
    }

    if ( ! $full) {
        $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


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

