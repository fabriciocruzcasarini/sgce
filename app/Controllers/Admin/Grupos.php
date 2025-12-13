<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AuthGroupModel;
use App\Models\AuthPermissionModel;

class Grupos extends BaseController
{
    protected $groupModel;
    protected $permissionModel;
    protected $db;

    public function __construct()
    {
        $this->groupModel = new AuthGroupModel();
        $this->permissionModel = new AuthPermissionModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['title'] = 'Grupos';
        $data['grupos'] = $this->groupModel->findAll();
        return view('admin/grupos/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Novo Grupo';
        $data['permissoes'] = $this->permissionModel->findAll();
        return view('admin/grupos/form', $data);
    }

    public function store()
    {
        $post = $this->request->getPost();
        $this->groupModel->insert([
            'name' => $post['name'],
            'description' => $post['description'] ?? '',
        ]);
        $groupId = $this->groupModel->getInsertID();
        // assign permissions if provided
        if (!empty($post['permissions']) && is_array($post['permissions'])) {
            $builder = $this->db->table('auth_groups_permissions');
            foreach ($post['permissions'] as $p) {
                $builder->insert(['group_id' => $groupId, 'permission_id' => (int)$p]);
            }
        }
        return redirect()->to('/admin/grupos')->with('success', 'Grupo criado');
    }

    public function edit($id)
    {
        $grupo = $this->groupModel->find($id);
        if (!$grupo) return redirect()->to('/admin/grupos')->with('error', 'Grupo não encontrado');
        $data['title'] = 'Editar Grupo';
        $data['grupo'] = $grupo;
        $data['permissoes'] = $this->permissionModel->findAll();
        $data['grupo_perms'] = $this->db->table('auth_groups_permissions')->where('group_id', $id)->get()->getResultArray();
        return view('admin/grupos/form', $data);
    }

    public function update($id)
    {
        $post = $this->request->getPost();
        $this->groupModel->update($id, ['name' => $post['name'], 'description' => $post['description'] ?? '']);
        // sync permissions
        $builder = $this->db->table('auth_groups_permissions');
        $builder->where('group_id', $id)->delete();
        if (!empty($post['permissions']) && is_array($post['permissions'])) {
            foreach ($post['permissions'] as $p) {
                $builder->insert(['group_id' => $id, 'permission_id' => (int)$p]);
            }
        }
        return redirect()->to('/admin/grupos')->with('success', 'Grupo atualizado');
    }

    public function delete($id)
    {
        $this->groupModel->delete($id);
        $this->db->table('auth_groups_permissions')->where('group_id', $id)->delete();
        return redirect()->to('/admin/grupos')->with('success', 'Grupo removido');
    }
}
