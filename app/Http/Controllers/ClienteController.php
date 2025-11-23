<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function insertar(Request $request)
    {
        try {
            // Validación básica
            $request->validate([
                'nombres' => 'required|string|max:60',
                'apellidos' => 'required|string|max:60',
                'identificacion' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:100',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
            ]);

            DB::statement("
                EXEC ventas.sp_insertar_cliente
                    @nombres = ?,
                    @apellidos = ?,
                    @identificacion = ?,
                    @email = ?,
                    @telefono = ?,
                    @direccion = ?
            ", [
                $request->nombres,
                $request->apellidos,
                $request->identificacion,
                $request->email,
                $request->telefono,
                $request->direccion,
            ]);

            return response()->json([
                'ok' => true,
                'mensaje' => 'Cliente registrado correctamente.'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function listar()
    {
        try {
            $clientes = DB::select("SELECT * FROM ventas.vw_clientes ORDER BY clienteID DESC");

            return response()->json([
                'ok' => true,
                'data' => $clientes
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
