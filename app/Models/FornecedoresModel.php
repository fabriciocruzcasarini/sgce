<?php

namespace App\Models;

use CodeIgniter\Model;

class FornecedoresModel extends Model
{
    protected $table            = 'fornecedores';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'observacao',
        'status',
        'imagem'
    ];

    // Timestamp automático
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validação (podemos aprimorar depois)
    protected $validationRules = [
        'id'                => 'permit_empty|integer',
        'razao_social'      => 'required|min_length[3]|max_length[100]|is_unique[fornecedores.razao_social,id,{id}]',
        'nome_fantasia'     => 'required|is_unique[fornecedores.nome_fantasia,id,{id}]',
        'cnpj'              => 'required|is_unique[fornecedores.cnpj,id,{id}]',
        'status'            => 'in_list[ativo,inativo]',
    ];


    protected $validationMessages = [
        'razao_social' => [
            'required'   => 'A razão social é obrigatória.',
            'min_length' => 'A razão social deve ter no mínimo 3 caracteres.',
            'max_length' => 'A razão social pode ter no máximo 100 caracteres.',
            'is_unique'  => 'Já existe um fornecedor com esta razão social.',
        ],
        'nome_fantasia' => [
            'required'    => 'O nome fantasia é obrigatório.',
            'max_length'  => 'O nome fantasia pode ter no máximo 150 caracteres.',
            'is_unique'   => 'Já existe um fornecedor com este nome fantasia.',
        ],
        'cnpj' => [
            'required'   => 'O CNPJ é obrigatório.',
            'is_unique'  => 'Já existe um fornecedor com este CNPJ.',
        ],
        'status' => [
            'in_list' => 'O status deve ser ativo ou inativo.',
        ],
    ];

    protected $skipValidation     = false;
}
