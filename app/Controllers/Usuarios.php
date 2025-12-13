<?php

namespace App\Controllers;

use CodeIgniter\Shield\Models\UserModel;

class Usuarios extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['title'] = 'Gerenciamento de Usuários';
        $data['usuarios'] = $this->userModel->findAll();

        return view('usuarios/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Criar Novo Usuário';
        return view('usuarios/form', $data);
    }

    public function store()
    {
        $dadosUsuario = $this->request->getPost();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[8]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->userModel->insert([
            'email' => $dadosUsuario['email'],
            'username' => $dadosUsuario['username'],
            'password' => $dadosUsuario['password'],
            'active' => true,
        ]);

        return redirect()->to('/usuarios')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit($id)
    {
        $data['title'] = 'Editar Usuário';
        $data['usuario'] = $this->userModel->find($id);

        if (!$data['usuario']) {
            return redirect()->to('/usuarios')->with('error', 'Usuário não encontrado.');
        }

        return view('usuarios/form', $data);
    }

    public function update($id)
    {
        $dadosUsuario = $this->request->getPost();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'username' => "required|min_length[3]|is_unique[users.username,id,{$id}]",
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->userModel->update($id, [
            'email' => $dadosUsuario['email'],
            'username' => $dadosUsuario['username'],
        ]);

        return redirect()->to('/usuarios')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function delete($id)
    {
        $usuario = $this->userModel->find($id);

        if (!$usuario) {
            return redirect()->to('/usuarios')->with('error', 'Usuário não encontrado.');
        }

        $this->userModel->delete($id);

        return redirect()->to('/usuarios')->with('success', 'Usuário excluído com sucesso!');
    }
}
