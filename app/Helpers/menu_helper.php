<?php

use CodeIgniter\HTTP\URI;

if (! function_exists('is_active')) {
    /**
     * Return 'c2g-active' jika URL saat ini match pola.
     * $patterns bisa string (dengan wildcard *) atau array pola.
     */
    function is_active(string|array $patterns, string $class = 'c2g-active'): string
    {
        $patterns = (array) $patterns;
        foreach ($patterns as $p) {
            if (url_is($p)) {
                return $class;
            }
        }
        return '';
    }
}

if (! function_exists('is_open')) {
    /**
     * Return 'show' jika salah satu pola cocok, untuk sub-menu <ul>.
     */
    function is_open(string|array $patterns, string $class = 'show'): string
    {
        return is_active($patterns, $class);
    }
}

if (! function_exists('aria_expanded')) {
    /**
     * Return 'true' atau 'false' string untuk aria-expanded pada tombol dropdown.
     */
    function aria_expanded(string|array $patterns): string
    {
        return is_active($patterns) ? 'true' : 'false';
    }
}
