<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use App\Models\AuthGroupModel;
use App\Models\AuthPermissionModel;

class Usuarios extends BaseController
{
    protected $userModel;
    protected $groupModel;
    protected $permissionModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new AuthGroupModel();
        $this->permissionModel = new AuthPermissionModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['title'] = 'Usuários';
        $data['usuarios'] = $this->userModel->findAll();
        return view('admin/usuarios/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Criar Usuário';
        $data['grupos'] = $this->groupModel->findAll();
        $data['permissoes'] = $this->permissionModel->findAll();
        return view('admin/usuarios/form', $data);
    }

    public function store()
    {
        $post = $this->request->getPost();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[8]',
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        // save user (Shield UserModel handles hashing)
        $this->userModel->insert([
            'email' => $post['email'],
            'username' => $post['username'],
            'password' => $post['password'],
            'active' => $post['active'] ?? 1,
        ]);
        $userId = $this->userModel->getInsertID();

        // assign groups
        if (!empty($post['groups']) && is_array($post['groups'])) {
            $builder = $this->db->table('auth_groups_users');
            foreach ($post['groups'] as $g) {
                $builder->insert(['user_id' => $userId, 'group_id' => (int)$g]);
            }
        }

        return redirect()->to('/admin/usuarios')->with('success', 'Usuário criado');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to('/admin/usuarios')->with('error', 'Usuário não encontrado');
        $data['title'] = 'Editar Usuário';
        $data['usuario'] = $user;
        $data['grupos'] = $this->groupModel->findAll();
        $data['permissoes'] = $this->permissionModel->findAll();
        // load user's groups
        $data['usuario_groups'] = $this->db->table('auth_groups_users')->where('user_id', $id)->get()->getResultArray();
        return view('admin/usuarios/form', $data);
    }

    public function update($id)
    {
        $post = $this->request->getPost();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'username' => "required|min_length[3]|is_unique[users.username,id,{$id}]",
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $data = [
            'email' => $post['email'],
            'username' => $post['username'],
        ];
        if (!empty($post['password'])) $data['password'] = $post['password'];

        $this->userModel->update($id, $data);

        // sync groups: remove old, insert new
        $builder = $this->db->table('auth_groups_users');
        $builder->where('user_id', $id)->delete();
        if (!empty($post['groups']) && is_array($post['groups'])) {
            foreach ($post['groups'] as $g) {
                $builder->insert(['user_id' => $id, 'group_id' => (int)$g]);
            }
        }

        return redirect()->to('/admin/usuarios')->with('success', 'Usuário atualizado');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        // cleanup associations
        $this->db->table('auth_groups_users')->where('user_id', $id)->delete();
        return redirect()->to('/admin/usuarios')->with('success', 'Usuário removido');
    }
}
