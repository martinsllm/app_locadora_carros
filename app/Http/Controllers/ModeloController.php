<?php

namespace App\Http\Controllers;

use App\Http\Requests\Modelo\StoreModeloRequest;
use App\Http\Requests\Modelo\UpdateModeloRequest;
use App\Models\Modelo;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    protected $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->modelo->with('marca')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModeloRequest $request)
    {
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');

        $result = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json($result, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModeloRequest $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização: o recurso solicitado não existe.'], 404);
        }

        if ($request->file('imagem')) {
            //Remove o arquivo antigo se for enviado um no request
            Storage::disk('public')->delete($modelo->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens/modelos', 'public');
            $modelo->fill($request->all());

            $modelo->imagem = $imagem_urn;
        } else {
            $modelo->fill($request->all());
        }

        $modelo->save();
        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão: o recurso solicitado não existe.'], 404);
        }

        //Remove o arquivo se existir
        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return response()->json(['msg' => 'Modelo removido com sucesso!'], 200);
    }
}
