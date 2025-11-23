<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function insertar(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:50',
            ]);

            $result = DB::select("
                EXEC ventas.sp_insertar_categoria @nombre = ?
            ", [
                $request->nombre
            ]);

            return response()->json([
                'ok' => true,
                'categoriaID' => $result[0]->categoriaID ?? null,
                'mensaje' => 'Categoría registrada correctamente.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
