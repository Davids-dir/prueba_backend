# Prueba Rockethall

## Instalación
El proyecto incluye docker-compose, con lo que para realizar la instalación, deberemos de acceder a la ruta del proyecto y lanzar los siguientes comandos:

```docker compose up -d --build```

Accederemos al contenedor del servidor mediante:

```docker-compose exec web bash```

Una vez nos encontremos dentro del servidor introduciremos los comandos iniciales de Symfony:

```composer install```

```php bin/console doctrine:migrations:migrate```

Para acceder a la DB mediante la imagen de **PhpMyAdmin** iremos a la ruta *http://localhost:8081*, las credenciales de acceso para root son:

**User:** ```root```  
**Password:** ```password```


Las peticiones hacia nuestra aplicación se realizarán en la ruta *http://localhost:8080*

Incluye también una colección para Postman en formato ```json``` para ser importada.