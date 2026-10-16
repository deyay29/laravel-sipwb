<?php

return [
    /*
    | Source of truth untuk link navigasi publik.
    | Dipakai oleh layouts/partials/navbar.blade.php untuk desktop dan mobile,
    | sehingga tidak ada lagi duplikasi daftar menu.
    */
    'public' => [
        [
            'label' => 'Beranda',
            'route' => 'home',
            'pattern' => 'home',
            'icon' => 'fa-house',
        ],
        [
            'label' => 'Berita',
            'route' => 'public.berita',
            'pattern' => 'public.berita*',
            'icon' => 'fa-newspaper',
        ],
        [
            'label' => 'Wisata',
            'route' => 'public.wisata',
            'pattern' => 'public.wisata*',
            'icon' => 'fa-mountain-sun',
        ],
        [
            'label' => 'Kuliner',
            'route' => 'public.kuliner',
            'pattern' => 'public.kuliner*',
            'icon' => 'fa-utensils',
        ],
        [
            'label' => 'Sejarah',
            'route' => 'public.sejarah',
            'pattern' => 'public.sejarah*',
            'icon' => 'fa-landmark',
        ],
        [
            'label' => 'Galeri',
            'route' => 'public.galeri',
            'pattern' => 'public.galeri*',
            'icon' => 'fa-images',
        ],
        [
            'label' => 'Kontak',
            'route' => 'public.kontak',
            'pattern' => 'public.kontak*',
            'icon' => 'fa-envelope',
        ],
    ],

    /*
    | Menu sidebar panel admin.
    */
    'admin' => [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'pattern' => 'admin.dashboard',
            'icon' => 'fa-th-large',
        ],
        [
            'label' => 'Manajemen Kuliner',
            'route' => 'admin.kuliner.index',
            'pattern' => 'admin.kuliner.*',
            'icon' => 'fa-utensils',
        ],
        [
            'label' => 'Manajemen Wisata',
            'route' => 'admin.wisata.index',
            'pattern' => 'admin.wisata.*',
            'icon' => 'fa-mountain-sun',
        ],
        [
            'label' => 'Manajemen Berita',
            'route' => 'admin.berita.index',
            'pattern' => 'admin.berita.*',
            'icon' => 'fa-newspaper',
        ],
        [
            'label' => 'Sejarah & Museum',
            'route' => 'admin.sejarah.index',
            'pattern' => 'admin.sejarah.*',
            'icon' => 'fa-landmark',
        ],
        [
            'label' => 'Manajemen Galeri',
            'route' => 'admin.galeri.index',
            'pattern' => 'admin.galeri.*',
            'icon' => 'fa-images',
        ],
    ],

    /*
    | Tautan ke halaman publik yang relevan dari panel admin.
    */
    'admin_public' => [
        [
            'label' => 'Beranda',
            'route' => 'home',
            'icon' => 'fa-house',
        ],
        [
            'label' => 'Halaman Wisata',
            'route' => 'public.wisata',
            'icon' => 'fa-mountain-sun',
        ],
        [
            'label' => 'Pembaruan Berita',
            'route' => 'public.berita',
            'icon' => 'fa-rss',
        ],
        [
            'label' => 'Arsip Sejarah',
            'route' => 'public.sejarah',
            'icon' => 'fa-scroll',
        ],
        [
            'label' => 'Galeri Publik',
            'route' => 'public.galeri',
            'icon' => 'fa-images',
        ],
        [
            'label' => 'Data Kontak',
            'route' => 'admin.contact.index',
            'pattern' => 'admin.contact.*',
            'icon' => 'fa-address-card',
        ],
    ],
];
