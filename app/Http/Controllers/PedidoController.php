<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function insertarCompleto(Request $request)
    {
        try {
            $request->validate([
                'clienteID' => 'required|integer',
                'detalles' => 'required|array',
                'detalles.*.productoID' => 'required|integer',
                'detalles.*.cantidad' => 'required|integer|min:1'
            ]);

            $jsonDetalles = json_encode($request->detalles);

            $result = DB::select("
                EXEC ventas.sp_InsertarPedidoCompleto_JSON
                    @ClienteID = ?,
                    @DetallesJSON = ?
            ", [
                $request->clienteID,
                $jsonDetalles
            ]);

            return response()->json([
                'ok' => true,
                'pedido' => $result[0] ?? null
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
