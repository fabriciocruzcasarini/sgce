<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutosModel extends Model
{
    protected $table            = 'produtos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'usuario_id',
        'fornecedor_id',
        'subgrupo_produtos_id',
        'nome',
        'descricao',
        'sku',
        'created_at',
        'updated_at',
        'status',
        'imagem',
        'observacao'
    ];

    // Timestamp automático
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Regras de validação
    protected $validationRules = [
        'id'                     => 'permit_empty|integer',
        'usuario_id'             => 'required|integer',
        'subgrupo_produtos_id'   => 'required|integer',
        'nome'                   => 'required|min_length[3]|max_length[100]|is_unique[produtos.nome,id,{id}]',
        'descricao'              => 'permit_empty|max_length[150]',
        'sku'                    => 'required|max_length[50]|is_unique[produtos.sku,id,{id}]',
        'imagem'                 => 'max_length[255]',
        'observacao'             => 'permit_empty|max_length[255]',
        'status'                 => 'in_list[ativo,inativo]',
    ];

    // Mensagens de erro personalizadas
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O usuário é obrigatório.',
            'integer'  => 'O ID do usuário deve ser numérico.',
        ],
        'subgrupo_produtos_id' => [
            'required' => 'O subgrupo de produtos é obrigatório.',
            'integer'  => 'O subgrupo deve ser um ID válido.',
        ],
        'nome' => [
            'required'   => 'O nome do produto é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
            'max_length' => 'O nome pode ter no máximo 100 caracteres.',
            'is_unique'  => 'Já existe um produto com esse nome.',
        ],
        'descricao' => [
            'max_length' => 'A descrição pode ter até 150 caracteres.',
        ],
        'sku' => [
            'required'   => 'O campo SKU é obrigatório.',
            'max_length' => 'O SKU pode ter no máximo 50 caracteres.',
            'is_unique'  => 'Já existe um produto com esse SKU.',
        ],
        'imagem' => [
            'valid_url'   => 'O campo imagem deve conter uma URL válida.',
            'max_length'  => 'O caminho da imagem deve ter no máximo 255 caracteres.',
        ],
        'observacao' => [
            'max_length' => 'A observação pode ter no máximo 255 caracteres.',
        ],
        'status' => [
            'in_list' => 'O status deve ser "ativo" ou "inativo".',
        ],
    ];


    protected $skipValidation = false;
}
