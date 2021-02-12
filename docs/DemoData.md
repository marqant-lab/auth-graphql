
## Demo data

If you need demo users, just execute:

```shell script
php artisan db:seed --class=Marqant\\AuthGraphQL\\Database\\Seeders\\UserSeeder
```

This seeder will create `'demo@demo.com'` and `'admin@admin.com'` Users 
with password `'Password123$'`.
