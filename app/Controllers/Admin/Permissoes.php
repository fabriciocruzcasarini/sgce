<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuthPermissionModel;

class Permissoes extends BaseController
{
    protected $permModel;

    public function __construct()
    {
        $this->permModel = new AuthPermissionModel();
    }

    public function index()
    {
        $data['title'] = 'Permissões';
        $data['permissoes'] = $this->permModel->findAll();
        return view('admin/permissoes/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Nova Permissão';
        return view('admin/permissoes/form', $data);
    }

    public function store()
    {
        $post = $this->request->getPost();
        $this->permModel->insert(['name' => $post['name'], 'description' => $post['description'] ?? '']);
        return redirect()->to('/admin/permissoes')->with('success', 'Permissão criada');
    }

    public function edit($id)
    {
        $data['permissao'] = $this->permModel->find($id);
        if (!$data['permissao']) return redirect()->to('/admin/permissoes')->with('error', 'Permissão não encontrada');
        $data['title'] = 'Editar Permissão';
        return view('admin/permissoes/form', $data);
    }

    public function update($id)
    {
        $post = $this->request->getPost();
        $this->permModel->update($id, ['name' => $post['name'], 'description' => $post['description'] ?? '']);
        return redirect()->to('/admin/permissoes')->with('success', 'Permissão atualizada');
    }

    public function delete($id)
    {
        $this->permModel->delete($id);
        return redirect()->to('/admin/permissoes')->with('success', 'Permissão excluída');
    }
}
