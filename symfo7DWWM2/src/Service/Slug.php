<?php

namespace App\Service;

trait Slug
{
  public function enslug($string): string
  {
    // accents en lettres minuscules
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

    // caractères interdits
    $string = preg_replace('/[^0-9a-z\-\_\ ]/', '', $string);

    // espaces en tirets
    return strtolower(str_replace(' ', '-', $string));
  }
}