<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        try {
            $result = DB::select("EXEC ventas.sp_dashboard_estadisticas");

            if (empty($result)) {
                return response()->json([
                    'ok' => false,
                    'error' => 'SP no retornó datos'
                ], 500);
            }

            $jsonColumn = (array)$result[0];
            $rawJson = reset($jsonColumn);

            $data = json_decode($rawJson, true);

            return response()->json([
                'ok' => true,
                'totales' => $data['totales'],
                'estados' => $data['estados'],
                'clientes' => $data['clientes'],
                'actividad' => $data['actividad'],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
