
<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Barter Advance

Barter Advance is a web application interface with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:


## Initiate of Barter Advance

เพื่อให้ระบบทำงานอย่างถูกต้อง ก่อนสร้างระบบ จะต้องทำการรันคำสั่งเร่ิมต้น ดังต่อไปนี้:

- อัพเดท library ที่เกี่ยวข้อง
```
Composer install
```

- Migrate database
```
php artisan migrate
```

- สร้างข้อมูล location
```
php artisan db:seed --class=CountriesSeeder
php artisan db:seed --class=DistrictsSeeder
php artisan db:seed --class=ProvincesSeeder
php artisan db:seed --class=SubdistrictsSeeder
```

- สร้างข้อมูล Point
```
php artisan db:seed --class=PointSeeder
```

- สร้างข้อมูล Point
```
php artisan db:seed --class=ShopTypeSeeder
```

- สร้างข้อมูล role
```
*ต้องสร้าง permission ก่อน
php artisan db:seed --class=RolesSeeder
```

- สร้างผู้ใช้เริ่มต้น
```
*ต้องสร้าง role ก่อน
php artisan db:seed --class=UsersSeeder
```

- สร้าง key
```
php artisan key:generate
```

- สร้าง symbolic link ของ storage
```
php artisan storage:link
```

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Barter Advance is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## For WTF Bug
CURL 3 ENV lost

- CURL 3 ENV lost
```
php artisan config:clear
php artisan cache:clear
composer dump-autoload
php artisan view:clear
php artisan route:clear
```
