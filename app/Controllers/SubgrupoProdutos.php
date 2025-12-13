<?php

namespace App\Controllers;

use App\Models\SubgrupoProdutosModel;
use App\Models\GrupoProdutosModel;

class SubgrupoProdutos extends BaseController
{
    protected $subgrupoModel;
    protected $grupoModel;

    public function __construct()
    {
        $this->subgrupoModel = new SubgrupoProdutosModel();
        $this->grupoModel = new GrupoProdutosModel();
    }

    public function index()
    {
        $data['subgrupos_ativos'] = $this->subgrupoModel
            ->select('subgrupo_produtos.*, grupo_produtos.nome as nome_grupo')
            ->join('grupo_produtos', 'grupo_produtos.id = subgrupo_produtos.grupo_produtos_id')
            ->where('subgrupo_produtos.status', 'ativo')
            ->findAll();

        $data['subgrupos_inativos'] = $this->subgrupoModel
            ->select('subgrupo_produtos.*, grupo_produtos.nome as nome_grupo')
            ->join('grupo_produtos', 'grupo_produtos.id = subgrupo_produtos.grupo_produtos_id')
            ->where('subgrupo_produtos.status', 'inativo')
            ->findAll();

        $data['title'] = 'Lista de Subgrupos de Produtos';
        $data['title_inativo'] = 'Lista de Subgrupos de Produtos Inativos';

        return view('subgrupo_produtos/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Criar Subgrupo de Produtos';
        $data['grupos'] = $this->grupoModel->where('status', 'ativo')->findAll();

        //echo ('<pre>');
        //print_r($data);
        //echo ('</pre>');
        return view('subgrupo_produtos/form', $data);
    }

    public function store()
    {
        $dadosSubgrupo = $this->request->getPost();
        $dadosSubgrupo['usuario_id'] = auth()->id();

        // Valida e salva Subgrupo

        if (!$this->subgrupoModel->save($dadosSubgrupo)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->subgrupoModel->errors());
        }

        // ID do grupo (para novo ou edição)
        $subgrupoId = $this->subgrupoModel->getInsertID() ?: $dadosSubgrupo['id'] ?? null;

        return redirect()->to('/subgrupo-produtos')->with('success', 'Subgrupo salvo com sucesso!');
    }

    public function edit($id)
    {
        $data['title'] = 'Editar Subgrupo de Produtos';
        $data['subgrupo'] = $this->subgrupoModel->find($id);
        $data['grupos'] = $this->grupoModel->findAll();
        return view('subgrupo_produtos/form', $data);
    }

    public function desativar($id)
    {
        $subgrupo = $this->subgrupoModel->find($id);

        if (!$subgrupo) {
            return redirect()->back()->with('error', 'Subgrupo não encontrado.');
        }

        $this->subgrupoModel->update($id, ['status' => 'inativo']);
        return redirect()->to('/subgrupo-produtos')->with('success', 'Subgrupo desativado com sucesso!');
    }

    public function porGrupo($grupoId)
    {
        $subgrupos = $this->subgrupoModel
            ->where('grupo_produtos_id', $grupoId)
            ->where('status', 'ativo')
            ->findAll();

        return $this->response->setJSON($subgrupos);
    }
}
