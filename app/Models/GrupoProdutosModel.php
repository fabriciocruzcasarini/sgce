<?php

namespace App\Models;

use CodeIgniter\Model;

class GrupoProdutosModel extends Model
{
    protected $table            = 'grupo_produtos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nome',
        'descricao',
        'status',
        'created_at',
        'updated_at',
        'usuario_id',
    ];

    // Timestamp automático
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validação (podemos aprimorar depois)
    protected $validationRules = [
        'id'              => 'permit_empty|integer',
        'nome'            => 'required|min_length[3]|max_length[100]|is_unique[grupo_produtos.nome,id,{id}]',
        'descricao'      => 'required|max_length[150]|is_unique[grupo_produtos.descricao,id,{id}]',
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
    ];

    protected $skipValidation     = false;
}
