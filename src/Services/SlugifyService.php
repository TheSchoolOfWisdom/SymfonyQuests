<?php


namespace App\Services;


class SlugifyService
{
    public static function slugify(string $slug)
    {
        $slug = mb_strtolower(preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        ));
        return $slug;
    }
}
