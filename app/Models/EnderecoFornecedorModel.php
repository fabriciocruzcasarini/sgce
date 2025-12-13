<?php

namespace App\Models;

use CodeIgniter\Model;

class EnderecoFornecedorModel extends Model
{
    protected $table            = 'endereco_fornecedores';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'fornecedor_id',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'observacao'
    ];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'logradouro' => 'required',
        'numero'     => 'required',
        'bairro'     => 'required',
        'cidade'     => 'required',
        'estado'     => 'required|exact_length[2]',
        'cep'        => 'required|min_length[8]|max_length[9]',
    ];
}
