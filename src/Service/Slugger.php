<?php
/**
 * Created by PhpStorm.
 * User: ante
 * Date: 30.12.18.
 * Time: 21:06
 */

namespace App\Service;


/**
 * Class Slugger
 * @package App\Service
 */
class Slugger
{
    /**
     * Function to generate slug from post title
     *
     * @param string $text
     * @return false|string|string[]|null
     */
    public function makeSlug(string $text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}