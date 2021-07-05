<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HelloController  {
    
    public function hello(Request $request, $name) {
        return new Response("Hello " . $name);
    }

    public function somme($nombre1, $nombre2, $nombre3) {
        return new Response("Hello " . ($nombre1 + $nombre2 + $nombre3));
    }
}