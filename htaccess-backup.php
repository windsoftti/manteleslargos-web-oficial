RewriteEngine On
RewriteBase /

RewriteRule ^tipo-proveedores/([^/\.]+)$ tipo-proveedores.php?eventTypeSlug=$1

RewriteRule ^negocios/([^/\.]+)$ negocios.php?eventTypeSlug=$1
RewriteRule ^negocios/([^/\.]+)/([^/\.]+)$ negocios.php?eventTypeSlug=$1&supplierTypeSlug=$2
RewriteRule ^negocios/([^/\.]+)/([^/\.]+)/([^/\.]+)$ negocio.php?eventTypeSlug=$1&supplierTypeSlug=$2&businessSlug=$3

RewriteRule ^eventos-recientes/([^/\.]+)$ evento-reciente.php?recentEventSlug=$1

RewriteRule ^tips/([^/\.]+)$ tip.php?tipSlug=$1

RewriteRule ^editar-invitacion/([^/\.]+)$ editar-invitacion.php?invitationSlug=$1

RewriteRule ^confirmar-cuenta/([^/\.]+)$ confirmar-cuenta.php?accessToken=$1

RewriteRule ^invitaciones/01/([^/\.]+)$ invitacion-plantilla-01.php?invitationSlug=$1
RewriteRule ^invitaciones/02/([^/\.]+)$ invitacion-plantilla-02.php?invitationSlug=$1
RewriteRule ^invitaciones/03/([^/\.]+)$ invitacion-plantilla-03.php?invitationSlug=$1

RewriteRule ^([^\.]+)$ $1.php [NC,L]

ErrorDocument 404 'Pagina no encontrada'

# php -- BEGIN cPanel-generated handler, do not edit
# Set the “ea-php72” package as the default “PHP” programming language.
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php72 .php .php7 .phtml
</IfModule>
# php -- END cPanel-generated handler, do not edit
RewriteCond %{HTTPS} off
RewriteCond %{HTTP:X-Forwarded-SSL} !on
RewriteCond %{HTTP_HOST} ^manteleslargos\.com$ [OR]
RewriteCond %{HTTP_HOST} ^www\.manteleslargos\.com$
RewriteRule ^/?$ "https\:\/\/manteleslargos\.com\/" [R=301,L]

