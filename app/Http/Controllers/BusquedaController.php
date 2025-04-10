<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Busqueda;

class BusquedaController extends Controller
{
    public function guardarBusqueda(Request $request){
        $nombre = $request->input('nombre');
        $duracion = $request->input('duracion');
        $año = $request->input('año');
        $genero = $request->input('genero');
        $director = $request->input('director');
        $actores = $request->input('actores');
        $sinopsis = $request->input('sinopsis');
        $imagen = $request->input('imagen');

        $busqueda = New Busqueda();
        $busqueda->nombre = $nombre;
        $busqueda->duracion = $duracion;
        $busqueda->año = $año;
        $busqueda->genero = $genero;
        $busqueda->director = $director;
        $busqueda->actores = $actores;
        $busqueda->sinopsis = $sinopsis;
        $busqueda->imagen = $imagen;
        $res = $busqueda->save();
        // return redirect()->route('home')->with('success', 'Busqueda guardada correctamente');

        if($res){
            return response()->json([
                'status' => 'success',
                'message' => 'Busqueda guardada correctamente'
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'error al guardar'
            ]);
        }
       

    }

    public function ordenarColumna(Request $request){
        $columna = $request->query('columna');
        $orden = $request->query('orden');

        $busquedas = Busqueda::orderBy($columna, $orden)->get();

        return response()->json([
            'busquedas' => $busquedas
        ]);
    }

    public function getAllBusquedas(){
        $busquedas = Busqueda::orderBy('id', 'desc')->paginate(5);
        return response()->json([
            'busquedas' => $busquedas
        ]);
    }

    public function busquedaPorColumna(Request $request){
        $columna = $request->query('columna');
        $valor = $request->query('valor');

        $busquedas = Busqueda::where($columna, 'like', '%' . $valor . '%')->get();

        return response()->json([
            'busquedas' => $busquedas
        ]);
    }
}

