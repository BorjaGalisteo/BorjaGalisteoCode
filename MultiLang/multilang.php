<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/backoffice/include/conectar.php');

$default_lang = 'es';
function detect_browser_language($default_lang){
    $lang_browser = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $idiomas = array('es','en','de','ru');

    $lang_parse = Array();
    preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $idiomas, $lang_parse);

    if (count($lang_parse[1]) == 0) {
        return $default_lang;
    }

    foreach ($lang_parse as $lang) {
        $lang = strtoupper($lang);
        if (in_array($lang, $idiomas)) {
            return $lang;
        }
    }
    return $default_lang;
}
session_start();
if (!isset($_SESSION["lang"])){
    $_SESSION["lang"] = detect_browser_language($default_lang);
}

if (isset($_GET["lang"])) {
    $_SESSION["lang"] = $_GET["lang"];
}

$translations = "";
function TRADUCCION($texto){
    //usamos esta global unicamente por optimizacion, para que una vez seteada no tenga que volver a setearse. Como máximo
    //se setearía una vez por página.
    global $translations;
    $idioma =  $_SESSION["lang"];

    if($translations == "") {
        $raw = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/backoffice/multilenguas/".$idioma.".json");
        $translations = json_decode($raw, true);
    }

    $contenido = explode(".", $texto,2);

    $id = intval($contenido[0]);
    //return "TOKEN";
    if (isset($translations[$id])){
        $str = str_replace("\'", "'", $translations[$id]);
        return $str;
    } else {
        if ($contenido[0] == 'X') {
            return $contenido[1];
        }
        return $contenido[1];
    }
}