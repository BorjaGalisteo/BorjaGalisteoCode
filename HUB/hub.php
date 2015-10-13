<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/backoffice/front/core.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/backoffice/multilenguas/traducciones.php");


if(isset($_GET["idioma"])){
    $_SESSION["lang"] = $_GET["idioma"];
}

$idioma = $_SESSION["lang"];
$friendly_url = $idioma."/".$_GET["url"];
//si tenemos un tercer paremetro lo concatenamos como el nombre de la pagina
if (isset($_GET["url2"])){
    $friendly_url .= "/".$_GET["url2"];
}

//En friendly_$idioma contenemos el mapeo de todas las URL's amigables con la ruta del fichero a que corresponde.
//Este fichero es creado por el cliente mediante el backoffice
$raw = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/backoffice/seo/friendly_$idioma.json");
$urls = json_decode($raw, true);

$url = $urls[$friendly_url];

if(!empty($url)){
    //Hacemos un Require del fichero que corresponde a la URL amigable en nuestro diccionario.
    require_once($_SERVER["DOCUMENT_ROOT"] . "hub.php");
}else{
    //puede ser que la URL que tenemos no coincida con nuestro idioma en SESSION en ese caso buscamos la URL amigable
    // en todo los idiomas, cuando lo encontremos seteamos el idioma en sesion y llevamos a la página correcta
    $query = "select * from pagina where friendly_es like '".$friendly_url."' or friendly_en like '".$friendly_url."' or friendly_de like '".$friendly_url."'";
    $info = $db->get_row($query);
    if ($info->friendly_es != ""){
        $_SESSION["lang"] = "es";
    }
    if ($info->friendly_en != ""){
        $_SESSION["lang"] = "de";
    }
    if ($info->friendly_de != ""){
        $_SESSION["lang"] = "de";
    }
    if (!empty($info->url)){
        require_once($_SERVER["DOCUMENT_ROOT"].$info->url);
    }else{
        //Si no encontramos esta URL en ningun fichero de URLs amigables llevamos a la página 404
        require_once($_SERVER["DOCUMENT_ROOT"]."/404.php");
    }
}

