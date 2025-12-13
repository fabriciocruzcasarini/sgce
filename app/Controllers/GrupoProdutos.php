<?php

namespace App\Controllers;

use App\Models\GrupoProdutosModel;

class GrupoProdutos extends BaseController
{
    protected $grupoModel;

    public function __construct()
    {
        $this->grupoModel = new GrupoProdutosModel();
    }

    public function index()
    {
        $data['grupos'] = $this->grupoModel->findAll();
        $data['title'] = 'Lista de Grupos de Produtos';
        return view('grupo_produtos/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Criar Grupo de Produtos';
        return view('grupo_produtos/form', $data);
    }

    public function store()
    {
        $dadosGrupo = $this->request->getPost();

        // Valida e salva Grupo
        if (!$this->grupoModel->save($dadosGrupo)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->grupoModel->errors());
        }

        // ID do grupo (para novo ou edição)
        $grupoId = $this->grupoModel->getInsertID() ?: $dadosGrupo['id'] ?? null;

        return redirect()->to('/grupo-produtos')->with('success', 'Grupo salvo com sucesso!');
    }

    public function edit($id)
    {
        $data['title'] = 'Editar Grupo de Produtos';
        $data['grupo'] = $this->grupoModel->find($id);
        return view('grupo_produtos/form', $data);
    }

    public function desativar($id)
    {
        $grupo = $this->grupoModel->find($id);

        if (!$grupo) {
            return redirect()->back()->with('error', 'Grupo não encontrado.');
        }

        $this->grupoModel->update($id, ['status' => 'inativo']);
        return redirect()->to('/grupo-produtos')->with('success', 'Grupo desativado com sucesso!');
    }
}
