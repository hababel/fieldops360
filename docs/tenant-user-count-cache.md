# Estrategia de cache para el conteo de usuarios por tenant

El dashboard central necesita conocer el número de usuarios existentes en todos los tenants sin ejecutar consultas costosas en cada solicitud. Para ello se utiliza un mecanismo de **caché** junto con un **job asíncrono** que mantiene estos valores actualizados.

## Actualización del cache

El job `UpdateTenantUserCounts` itera por los tenants, cuenta los usuarios de cada base de datos y almacena el resultado en caché:

- `tenant:{id}:users_count` – número de usuarios del tenant individual.
- `tenants.users.count` – número total de usuarios de todos los tenants.

Las entradas se guardan con una vigencia de 10 minutos (`Cache::put` con `now()->addMinutes(10)`).

## Disparo del job

El controlador `TenantController@index` intenta leer `tenants.users.count`. Si la clave no existe, se despacha el job `UpdateTenantUserCounts` y se muestra provisionalmente el valor `0`.

El job también puede ejecutarse de manera programada (por ejemplo, usando `schedule:run`) o dispararse cuando se creen o eliminen usuarios, garantizando que el dashboard reciba datos lo más actualizados posible sin penalizar el tiempo de respuesta.
