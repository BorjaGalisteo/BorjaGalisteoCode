El siguiente código fue creado para la creación de un sistema de resolución de URL's amigables.

El fichero HUB.php se encuentra en la raiz del proyecto y mediante el fichero HTACCESS se fuerza a cualquier URL de
un dominio acuda a este fichero, enviando todo lo que se encuentre detrás de dominio como parametros. de la siguiente forma

#HTACCESS
RewriteEngine On

RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^([A-Za-z0-9-]+)/?$ /hub.php?idioma=$1  [L]
RewriteRule ^([A-Za-z0-9-]+)/([A-Za-z0-9-]+)/?$ /hub.php?idioma=$1&url=$2  [L]
RewriteRule ^([A-Za-z0-9-]+)/([A-Za-z0-9-]+)/([A-Za-z0-9-]+)/?$ /hub.php?idioma=$1&url=$2&url2=$3  [L]

Siendo posible hasta 3 niveles, siendo el primero el idioma.
Ejemplo de URL amigables:
                            www.dominiodelcliente.com/es/paquetes-vacacionales
                            www.dominiodelcliente.com/en/paquetes-vacacionales/Mallorca-2016

#FIN HTACCESS

El fichero HUB se encarga de buscar que archivo corresponde a esta URL amigable y hacer un require_once sobre este. En caso
de no encontrar una matching entre la URL amigable y un fichero servirá el fichero 404.