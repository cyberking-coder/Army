<?php

return [
    /*
     * Livewire 3's class namespace / autoload defaults. Left unset uses
     * the framework defaults; we only override temporary_file_upload
     * so Livewire doesn't push multi-megabyte temp files to R2.
     */

    'temporary_file_upload' => [
        // Use the local 'public' disk for Livewire's intermediate temp
        // storage. On form save, Filament copies the file to its own
        // configured disk (R2) and deletes the temp. Keeps R2 writes
        // to one atomic PUT per final file.
        'disk'          => 'public',
        'rules'         => null,
        'directory'     => 'livewire-tmp',
        'middleware'    => null,
        'preview_mimes' => [
            'png','gif','bmp','svg','wav','mp4','mov','avi','wmv','mp3','m4a',
            'jpg','jpeg','mpga','webp','wma','pdf',
        ],
        'max_upload_time' => 5,
        'cleanup'         => true,
    ],
];
