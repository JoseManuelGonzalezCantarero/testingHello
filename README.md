testingHello
============

## Primeros pasos
1. Asegúrate de tener  [Composer instalado](https://getcomposer.org/).

2. Instala las dependencias:
```bash
cd Ruta del Proyecto
composer install
```
3. Asegúrate de que en `app/config/parameters.yml` tienes las credenciales de tu base de datos. Después:
```bash
bin/console doctrine:database:create
bin/console doctrine:migrations:migrate
bin/console doctrine:fixtures:load
```
Con ésto, cargaremos unos usuarios de prueba ( puedes mirar el fichero `src/AppBundle/DataFixtures/ORM/fixtures.yml`)
para ver las credenciales de éstos usuarios.

4. Arranca el servidor de PHP
```bash
bin/console server:run
```
Ejemplos de endpoints para la api:
1. `http://localhost:8000/api/user?username=Manu&plainPassword=manumanu&roles=ROLE_ADMIN`
(Éste endpoint crearía un usuario nuevo ( método POST))

2. `http://localhost:8000/api/user/Manu?username=Pepe`
(Éste endpoint actualizaría al usuario Manu, cambiando su nombre por Pepe ( método PUT o PATCH))

3. `http://localhost:8000/api/user/Manu`
(Éste endpoint, usando el método DELETE, borraría al usuario Manu de la base de datos)

4. `http://localhost:8000/api/user/Manu`
(Éste endpoint, usando el método GET, nos devolvería la información del usuario Manu)

