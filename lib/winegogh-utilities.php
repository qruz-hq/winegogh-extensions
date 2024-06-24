<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Parse date in various formats to a specified return format.
 *
 * @param string $date The date in various formats.
 * @param string $return_format The return date format (default is "Y-m-d").
 * @return string The date in the specified return format.
 */
function winegogh_parse_date( $date, $return_format = 'Y-m-d' ) {
    $months = [
        'enero' => '01',
        'febrero' => '02',
        'marzo' => '03',
        'abril' => '04',
        'mayo' => '05',
        'junio' => '06',
        'julio' => '07',
        'agosto' => '08',
        'septiembre' => '09',
        'octubre' => '10',
        'noviembre' => '11',
        'diciembre' => '12',
        'january' => '01',
        'february' => '02',
        'march' => '03',
        'april' => '04',
        'may' => '05',
        'june' => '06',
        'july' => '07',
        'august' => '08',
        'september' => '09',
        'october' => '10',
        'november' => '11',
        'december' => '12'
    ];

    // Check for format "20 de Julio de 2024"
    if ( preg_match( '/(\d{1,2}) de (\w+) de (\d{4})/', $date, $matches ) ) {
        $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
        $month = $months[strtolower($matches[2])];
        $year = $matches[3];
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
    }

    // Check for format "July 20, 2024"
    elseif ( preg_match( '/(\w+) (\d{1,2}), (\d{4})/', $date, $matches ) ) {
        $day = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
        $month = $months[strtolower($matches[1])];
        $year = $matches[3];
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
    }

    // Attempt to parse as a standard date format (YYYY-MM-DD, DD/MM/YYYY, etc.)
    else {
        $timestamp = strtotime($date);
    }

    // Return the date in the specified format if the timestamp is valid
    if ($timestamp !== false) {
        return date($return_format, $timestamp);
    }

    // Return the original date if no match
    return $date;
}
?>