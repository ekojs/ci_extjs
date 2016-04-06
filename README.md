Generator CRUD menggunakan Integrasi CI dan ExtJS4.2
==============
	Copyright (C) 2016 Eko Junaidi Salam

##Demo
Contoh halaman [http://ekojs.github.io/ci_extjs](http://ekojs.github.io/ci_extjs) atau bisa melihat juga di [http://ekojs.github.io/akreditasi](http://ekojs.github.io/akreditasi)

Karena github.io hanya mengeksekusi non PHP file, maka sample page dibuat dengan meniadakan aksi dari PHP file. Sample page lebih kepada "Contoh" tampilan bila berhasil dikonfigurasikan...

##Instalasi
Untuk menggunakan aplikasi ini, anda harus mengekstrak semuanya ke dalam satu folder. Untuk menggunakan halaman generator anda perlu import data sql yang ada pada [`resources/test.sql`](https://github.com/ekojs/ci_extjs/blob/master/resources/test.sql) file into ke dalam database anda dan ubah konfigurasi pada database di [`application/config/database.php`](https://github.com/ekojs/ci_extjs/blob/master/application/config/database.php).

##Struktur Aplikasi
* `app/` - Folder ini berisi file development ExtJS
* `application/` - App Folder untuk CodeIgniter
* `ext/` - Folder yang berisi core framework ExtJS4.2
* `resources/` - Folder ini dapa berisi file js, images, css
* `system/` - Folder ini berisi core framework CodeIgniter

##Baca Artikel berikut selengkapnya
Untuk informasi tambahan baca di [ekojunaidisalam.com](http://ekojunaidisalam.com/2016/04/06/integrasi-ci-dan-extjs4-2/)
