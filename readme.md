## Rutas
---
```php 
$router->get('game_logs/group', 'game_log\game_logController@list_by_group');
sirve para listar los registros del formulario por grupos
```

```php 
$router->post('game_logs/date/{fecha}','game_log\game_logController@filter_by_date');
sirve para filtrar los registros por fecha recibe por parametro una fecha
```
---
[Diagrama_entidar_relacion](https://drive.google.com/file/d/1cG50K5Qtf6Ia6ySeBnxMLg6kx5eDKhcE/view?usp=sharing)