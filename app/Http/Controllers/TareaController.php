<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TareaController extends Controller
{
    public function index(){
        return response()->json(['tareas'=> Tarea::all()]);
    }

    public function save(Request $request){

        $tareaNew=($request->id > 0) ? Tarea::find($request->id) : new Tarea();
        $operacion=($request->id > 0) ? "ACTUALIZAR" : "INSERTAR";
        try{
            DB::beginTransaction();

            $tareaNew->name=$request->name;
            $tareaNew->description=$request->description;

            $tareaNew->save();

            DB::commit();
            return response()->json([
                'tarea'=> $tareaNew,
                'message'=> 'Cambios ejecutados con éxito',
                'op' => $operacion
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error'=> $e->getMessage()], 500);
        }

    }

    public function find($id){
        return response()->json(['tarea'=>Tarea::find($id)]);

    }

    public function delete($id){
        $tarea = Tarea::find($id);
        try{
            DB::beginTransaction();
            $tarea->delete();
            DB::commit();
            return response()->json(['message'=> 'Tarea eliminada con éxito'], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['error'=> $e->getMessage()],500);
        }
    }

}
