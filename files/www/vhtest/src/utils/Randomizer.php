<?php
Namespace VoucherPool\Utils;

class Randomizer
{
    public static function randomCode($limit)
    {
        return strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit));
    }
}