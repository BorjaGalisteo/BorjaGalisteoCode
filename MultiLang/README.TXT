Multilang.php contiene la funcion traducción que es usada para traducir todos los textos de una web.
Ejemplo de uso:
    <?=TRADUCCION("1.HOLA")?>
El fichero busca en un diccionario (dependiendo del idioma seteado en sesion busca en un diccionario o en otro)
 por la clave "1" Y muestra el value al que corresponda. En caso de no encontrar esta
clave en el diccionario muestra el texto escrito despues del primer punto, en este caso "HOLA" .