Generator CRUD menggunakan Integrasi CI dan ExtJS4.2
==============
	Copyright (C) 2016 Eko Junaidi Salam

## Demo
Contoh halaman [http://ekojs.github.io/ci_extjs](http://ekojs.github.io/ci_extjs) atau bisa melihat juga di [http://ekojs.github.io/akreditasi](http://ekojs.github.io/akreditasi)

Karena github.io hanya mengeksekusi non PHP file, maka sample page dibuat dengan meniadakan aksi dari PHP file. Sample page lebih kepada "Contoh" tampilan bila berhasil dikonfigurasikan...

## Instalasi
1. Ekstrak aplikasi ini dalam satu folder.
2. Untuk menggunakan halaman generator import file sql yang ada pada [`resources/test.sql`](https://github.com/ekojs/ci_extjs/blob/master/resources/test.sql) ke database anda.
3. Ubah konfigurasi pada database di [`application/config/database.php`](https://github.com/ekojs/ci_extjs/blob/master/application/config/database.php).
4. Untuk versi PHP 5.6 keatas silahkan set define environtment ke `production` pada file [index.php](https://github.com/ekojs/ci_extjs/blob/master/index.php#L21).

## Generator
Akses halaman generator pada alamat http://localhost/ci_extjs/welcome/gce 

## Struktur Aplikasi
* `app/` - Folder ini berisi file development ExtJS
* `application/` - App Folder untuk CodeIgniter
* `ext/` - Folder yang berisi core framework ExtJS4.2
* `resources/` - Folder ini dapa berisi file js, images, css
* `system/` - Folder ini berisi core framework CodeIgniter

## Baca Artikel berikut selengkapnya
Untuk informasi tambahan baca di [ekojunaidisalam.com](http://ekojunaidisalam.com/2016/04/06/integrasi-ci-dan-extjs4-2/)
