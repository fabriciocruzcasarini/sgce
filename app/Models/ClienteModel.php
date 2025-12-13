<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nome',
        'email',
        'data_nascimento',
        'status',
        'imagem'
    ];

    // Timestamp automático
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validação (podemos aprimorar depois)
    protected $validationRules = [
        'id'              => 'permit_empty|integer',
        'nome'            => 'required|min_length[3]|max_length[100]|is_unique[clientes.nome,id,{id}]',
        'email'           => 'required|valid_email|max_length[150]|is_unique[clientes.email,id,{id}]',
        'data_nascimento' => 'required|valid_date',
        'status'          => 'in_list[ativo,inativo]',
    ];


    protected $validationMessages = [
        'nome' => [
            'required'   => 'O nome é obrigatório.',
            'min_length' => 'O nome deve ter no mínimo 3 caracteres.',
            'max_length' => 'O nome pode ter no máximo 100 caracteres.',
            'is_unique'  => 'Já existe um cliente com este nome.',
        ],
        'email' => [
            'required'    => 'O e-mail é obrigatório.',
            'valid_email' => 'Forneça um e-mail válido.',
            'max_length'  => 'O e-mail pode ter no máximo 150 caracteres.',
            'is_unique'   => 'Já existe um cliente com este e-mail.',
        ],
        'data_nascimento' => [
            'required'   => 'A data de nascimento é obrigatória.',
            'valid_date' => 'Informe uma data de nascimento válida.',
        ],
        'status' => [
            'in_list' => 'O status deve ser ativo ou inativo.',
        ],
    ];

    protected $skipValidation     = false;
}
