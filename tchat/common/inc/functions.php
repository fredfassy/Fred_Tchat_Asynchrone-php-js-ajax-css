<?php

function str_random($length){
    $alphanumeric = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphanumeric, $length)), 0, $length);
}
