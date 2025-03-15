'mongodb' => [
'driver'   => 'mongodb',
'host'     => env('MONGO_DB_HOST', '127.0.0.1'),
'port'     => env('MONGO_DB_PORT', 27017),
'database' => env('MONGO_DB_DATABASE', 'your_database'),
'username' => env('MONGO_DB_USERNAME', ''),
'password' => env('MONGO_DB_PASSWORD', ''),
'options'  => [
'database' => 'admin' // opțional, specifică baza de date pentru autentificare
]
],
