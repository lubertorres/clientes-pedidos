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
                'identificacion' => 'required|string|max:15',
                'detalles' => 'required|array',
                'detalles.*.productoID' => 'required|integer',
                'detalles.*.cantidad' => 'required|integer|min:1'
            ]);

            $jsonDetalles = json_encode($request->detalles);

            $result = DB::select("
                EXEC ventas.sp_InsertarPedidoCompleto_JSON
                    @in_identificacion = ?,
                    @in_DetallesJSON = ?
            ", [
                $request->identificacion,
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

    public function listarPedidos()
    {
        try {
            $pedidos = DB::select("SELECT * FROM ventas.vw_pedidos_completos ORDER BY fechaPedido DESC");

            $pedidos = array_map(function($p) {
                $p = (array) $p;
                $p['detalles'] = isset($p['detalles']) ? json_decode($p['detalles'], true) : [];
                return $p;
            }, $pedidos);

            return response()->json([
                'ok' => true,
                'data' => $pedidos
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editarDetalles(Request $request, $pedidoID)
    {
        try {
            $request->validate([
                'detalles' => 'required|array',
                'detalles.*.productoID' => 'required|integer',
                'detalles.*.cantidad' => 'required|integer|min:1'
            ]);

            $jsonDetalles = json_encode($request->detalles);

            $result = DB::select("
                EXEC ventas.sp_EditarPedidoDetalles_JSON
                    @in_pedidoID = ?,
                    @in_DetallesJSON = ?
            ", [
                $pedidoID,
                $jsonDetalles
            ]);

            return response()->json([
                'ok' => true,
                'pedido' => $result[0] ?? null,
                'mensaje' => 'Pedido actualizado correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function anularPedido($pedidoID)
    {
        try {
            $result = DB::select("
                EXEC ventas.sp_AnularPedido @pedidoID = ?
            ", [$pedidoID]);

            return response()->json([
                'ok' => true,
                'mensaje' => $result[0]->mensaje ?? 'Pedido anulado correctamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cambiarEstado(Request $request, $pedidoID)
    {
        try {
            $request->validate([
                'estado' => 'required|string'
            ]);

            $estado = $request->estado;

            $result = DB::select("
                EXEC ventas.sp_CambiarEstadoPedido
                    @pedidoID = ?,
                    @nuevoEstado = ?
            ", [
                $pedidoID,
                $estado
            ]);

            return response()->json([
                'ok' => true,
                'pedido' => $result[0],
                'mensaje' => "Estado actualizado a {$estado}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
