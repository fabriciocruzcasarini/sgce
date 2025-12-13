<?php

namespace App\Models;

use CodeIgniter\Model;

class NotasFiscaisModel extends Model
{
    protected $table            = 'notas_fiscais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id',
        'numero',
        'serie',
        'data_emissao',
        'data_entrada',
        'chave_acesso',
        'tipo_operacao',
        'natureza_operacao',
        'fornecedor_id',
        'valor_total',
        'valor_icms_st',
        'base_calculo_icms',
        'valor_icms',
        'base_calculo_ipi',
        'valor_ipi',
        'base_calculo_icms_st',
        'valor_icms_st',
        'outros_valores',
        'forma_pagamento',
        'observacoes',
        'numero_pedido',
        'created_at',
        'updated_at',
        'usuario_id',
        'status',
    ];

    // Timestamp automático
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Regras de validação
    protected $validationRules = [
        'id' => 'permit_empty|integer',
        'numero' => [
            'rules' => 'required|checkNotaDuplicada[id,fornecedor_id,serie]',
            'errors' => [
                'checkNotaDuplicada' => 'Já existe uma nota com esse número, série e fornecedor.'
            ]
        ],
        //'serie'               => 'required|max_length[10]',
        'data_emissao'        => 'required|valid_date',
        'data_entrada'        => 'required|valid_date',
        //'chave_acesso'        => 'required|exact_length[44]|is_unique[notas_fiscais.chave_acesso,id,{id}]',
        'tipo_operacao'       => 'required|in_list[entrada,saida]',
        //'natureza_operacao'   => 'required|max_length[100]',
        'fornecedor_id'       => 'required|integer|is_not_unique[fornecedores.id]',
        'valor_total'         => 'required|decimal',
        //'base_calculo_icms'   => 'permit_empty|decimal',
        //'valor_icms'          => 'permit_empty|decimal',
        //'base_calculo_ipi'    => 'permit_empty|decimal',
        //'valor_ipi'           => 'permit_empty|decimal',
        //'base_calculo_icms_st' => 'permit_empty|decimal',
        //'valor_icms_st'       => 'permit_empty|decimal',
        //'outros_valores'      => 'permit_empty|decimal',
        'forma_pagamento'     => 'permit_empty|max_length[50]',
        'observacoes'         => 'permit_empty|max_length[255]',
        'numero_pedido'       => 'permit_empty|max_length[255]',
        'created_at'          => 'permit_empty|valid_date',
        'updated_at'          => 'permit_empty|valid_date',
        'usuario_id'          => 'required|integer|is_not_unique[users.id]',
        'status'              => 'required|in_list[ativo,inativo,consolidada]',
        //'xml'                 => 'permit_empty|max_length[255]',
    ];

    // Mensagens de erro personalizadas
    protected $validationMessages = [
        'id' => [
            'required'   => 'O número da nota fiscal é obrigatório.',
            'max_length' => 'O número da nota fiscal pode ter no máximo 20 caracteres.',
        ],
        'numero' => [
            'required'   => 'O número da nota fiscal é obrigatório.',
            'max_length' => 'O número da nota fiscal pode ter no máximo 20 caracteres.',
        ],
        /*'serie' => [
            'required'   => 'A série da nota fiscal é obrigatória.',
            'max_length' => 'A série pode ter no máximo 10 caracteres.',
        ],*/
        'data_emissao' => [
            'required'   => 'A data de emissão é obrigatória.',
            'valid_date' => 'A data de emissão deve ser uma data válida.',
        ],
        'data_entrada' => [
            'required'   => 'A data de entrada é obrigatória.',
            'valid_date' => 'A data de entrada deve ser uma data válida.',
        ],
        /*'chave_acesso' => [
            'required'     => 'A chave de acesso é obrigatória.',
            'exact_length' => 'A chave de acesso deve ter exatamente 44 caracteres.',
            'is_unique'    => 'Já existe uma nota fiscal com esta chave de acesso.',
        ],*/
        'tipo_operacao' => [
            'required' => 'O tipo de operação é obrigatório.',
            'in_list'  => 'O tipo de operação deve ser "entrada" ou "saida".',
        ],
        'natureza_operacao' => [
            'required'   => 'A natureza da operação é obrigatória.',
            'max_length' => 'A natureza da operação pode ter no máximo 100 caracteres.',
        ],
        'fornecedor_id' => [
            'required'      => 'O fornecedor é obrigatório.',
            'integer'       => 'O ID do fornecedor deve ser numérico.',
            'is_not_unique' => 'O fornecedor informado não existe.',
        ],
        'valor_total' => [
            'required' => 'O valor total é obrigatório.',
            'decimal'  => 'O valor total deve ser um número decimal.',
        ],
        /*'base_calculo_icms' => [
            'decimal' => 'A base de cálculo do ICMS deve ser um número decimal.',
        ],
        /*'valor_icms' => [
            'decimal' => 'O valor do ICMS deve ser um número decimal.',
        ],
        /*'base_calculo_ipi' => [
            'decimal' => 'A base de cálculo do IPI deve ser um número decimal.',
        ],
        /*'valor_ipi' => [
            'decimal' => 'O valor do IPI deve ser um número decimal.',
        ],
        /*'base_calculo_icms_st' => [
            'decimal' => 'A base de cálculo do ICMS ST deve ser um número decimal.',
        ],
        /*'valor_icms_st' => [
            'decimal' => 'O valor do ICMS ST deve ser um número decimal.',
        ],
        /*'outros_valores' => [
            'decimal' => 'Os outros valores devem ser um número decimal.',
        ],*/
        'forma_pagamento' => [
            'max_length' => 'A forma de pagamento pode ter no máximo 50 caracteres.',
        ],
        'observacoes' => [
            'max_length' => 'As observações podem ter no máximo 255 caracteres.',
        ],
        'created_at' => [
            'valid_date' => 'A data de criação deve ser uma data válida.',
        ],
        'updated_at' => [
            'valid_date' => 'A data de atualização deve ser uma data válida.',
        ],
        'usuario_id' => [
            'required'      => 'O usuário é obrigatório.',
            'integer'       => 'O ID do usuário deve ser numérico.',
            'is_not_unique' => 'O usuário informado não existe.',
        ],
        'status' => [
            'required' => 'O status é obrigatório.',
            'in_list'  => 'O status deve ser "ativo" ou "inativo".',
        ],
    ];


    protected $skipValidation = false;
}
