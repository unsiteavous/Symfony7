<?php
namespace App\Services;

trait Slug {
  public function enslug(string $string): string
  {
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $string = strtolower(trim($string));
    $string = str_replace(' ', '-', $string);

    $string = strip_tags($string);
    $string = preg_replace('/[^a-z0-9-_]/', '', $string);
    $string = preg_replace('/-+/', '-', $string);
    return $string;
  }
}