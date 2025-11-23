<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function insertar(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:60',
                'precio' => 'required|numeric|min:0',
                'categoriaID' => 'required|integer',
            ]);

            $result = DB::select("
                EXEC ventas.sp_insertar_producto
                    @nombre = ?,
                    @precio = ?,
                    @categoriaID = ?
            ", [
                $request->nombre,
                $request->precio,
                $request->categoriaID,
            ]);

            return response()->json([
                'ok' => true,
                'productoID' => $result[0]->productoID ?? null,
                'mensaje' => 'Producto registrado correctamente.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

        public function listarProductos()
    {
        try {
            $productos = DB::select("SELECT * FROM ventas.vw_obtener_productos");

            return response()->json([
                'ok' => true,
                'data' => $productos
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
