<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FotoJugadorController extends Controller
{
    public function upload($jugadorId)
    {
        // Logic to handle the photo upload for the player
        // This could involve showing a form or handling file uploads
    }

    public function guardarFoto(Request $request, $jugadorId)
    {
        // Logic to save the uploaded photo for the player
        // This could involve validating the request and saving the file
    }
}
