<?php

use Illuminate\Contracts\Validation\Validator;

function messageValidation(Validator $validator){
    $errors = $validator->errors()->getMessages();
    $content = array_values($errors);
    $message = $content[0][0];
    return $message;
} 
/**
 * Convierte una fecha de tipo json date a un formato valido de php
 * @param $date Json Date
 * @return PHP dateFormat Object
 */

function jsonDateToPhp(string|null $date){
    if(!$date){
        return null;
    }
    preg_match("/\d+/", $date, $matches);
    $timestamp = $matches[0] / 1000;
    $fechaNormal  = new DateTime();
    $fechaNormal->setTimestamp($timestamp);
    return $fechaNormal->format('Y-m-d H:i:s');
}


/**
 * Limpiar texto enriquecido
 * @param $text
 * @return string
 */

 function clearRichText(string|null $text){
    if(!$text){
        return null;
    }
    $pattern   = '/[\x00-\x1F\x7F-\xFF]/';
    $cleanText = preg_replace($pattern, '', $text);
    return $cleanText;
 }