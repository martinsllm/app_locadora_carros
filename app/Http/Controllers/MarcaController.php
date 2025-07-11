<?php

namespace App\Http\Controllers;

use App\Http\Requests\Marca\StoreMarcaRequest;
use App\Http\Requests\Marca\UpdateMarcaRequest;
use App\Models\Marca;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    protected $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = $this->marca->with('modelos')->get();
        return response()->json($results, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarcaRequest $request)
    {
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $result = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        return response()->json($result, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarcaRequest $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização: o recurso solicitado não existe.'], 404);
        }

        if ($request->file('imagem')) {
            //Remove o arquivo antigo se for enviado um no request
            Storage::disk('public')->delete($marca->imagem);

            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');
            $marca->fill($request->all());

            $marca->imagem = $imagem_urn;
        } else {
            $marca->fill($request->all());
        }

        $marca->save();
        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão: o recurso solicitado não existe.'], 404);
        }

        //Remove o arquivo se existir
        Storage::disk('public')->delete($marca->imagem);


        $marca->delete();
        return response()->json(['msg' => 'Marca removida com sucesso!'], 200);
    }
}
