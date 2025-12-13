<?php

namespace App\Models;

use CodeIgniter\Model;

class SubgrupoProdutosModel extends Model
{
    protected $table            = 'subgrupo_produtos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'grupo_produtos_id',
        'usuario_id',
        'nome',
        'descricao',
        'status',
        'created_at',
        'updated_at'
    ];

    // Timestamp automático
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validação (podemos aprimorar depois)
    protected $validationRules = [
        'id'                => 'permit_empty|integer',
        'grupo_produtos_id' => 'required|is_not_unique[grupo_produtos.id]',
        'usuario_id'       => 'required|integer',
        'nome'            => 'required|min_length[3]|max_length[100]|is_unique[subgrupo_produtos.nome,id,{id}]',
        'descricao'       => 'required|max_length[150]|is_unique[subgrupo_produtos.descricao,id,{id}]',
        'status'          => 'in_list[ativo,inativo]',
    ];


    protected $validationMessages = [
        'nome' => [
            'required'   => 'O nome é obrigatório.',
            'min_length' => 'O nome deve ter no mínimo 3 caracteres.',
            'max_length' => 'O nome pode ter no máximo 100 caracteres.',
            'is_unique'  => 'Já existe um Grupo de Produtos com este nome.',
        ],
        'descricao' => [
            'required'   => 'A descrição é obrigatória.',
            'max_length' => 'A descrição pode ter no máximo 150 caracteres.',
            'is_unique'  => 'Já existe um Grupo de Produtos com esta descrição.',
        ],
        'status' => [
            'in_list' => 'O status deve ser ativo ou inativo.',
        ],
        'grupo_produtos_id' => [
            'required' => 'O grupo de produtos é obrigatório.',
            'integer'  => 'O grupo de produtos deve ser um registro válido.',
        ],
        'usuario_id' => [
            'required' => 'O usuário é obrigatório.',
            'integer'  => 'O usuário deve ser um registro válido.',
        ],
    ];

    protected $skipValidation     = false;
}
