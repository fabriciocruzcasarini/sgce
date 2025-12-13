<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FornecedoresModel;

class Fornecedores extends BaseController
{
    protected $fornecedorModel;

    public function __construct()
    {
        $this->fornecedorModel = new FornecedoresModel();
    }

    public function index()
    {
        $data['title'] = 'Fornecedores';
        $data['fornecedores'] = $this->fornecedorModel->where('status', '1')->findAll();
        return view('fornecedores/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Cadastrar Fornecedor';
        return view('fornecedores/form', $data);
    }

    public function store()
    {
        $dadosFornecedor = $this->request->getPost();
        $imagem = $this->request->getFile('imagem');

        if ($imagem && $imagem->isValid() && !$imagem->hasMoved()) {
            $novoNome = $imagem->getRandomName();
            $imagem->move(WRITEPATH . '../public/uploads/fornecedores', $novoNome);
            $dadosFornecedor['imagem'] = $novoNome;
        }

        // Valida e salva fornecedor
        if (!$this->fornecedorModel->save($dadosFornecedor)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->fornecedorModel->errors());
        }

        // ID do fornecedor (para novo ou edição)
        $fornecedorId = $this->fornecedorModel->getInsertID() ?: $dadosFornecedor['id'] ?? null;

        // ----- Telefones -----
        $telefones = $this->request->getPost('telefones');
        $telefoneModel = model('TelefoneFornecedorModel');

        if ($fornecedorId && is_array($telefones)) {
            // Remove antigos e salva os novos
            $telefoneModel->where('fornecedor_id', $fornecedorId)->delete();

            foreach ($telefones as $t) {
                if (!empty($t['numero'])) {
                    $telefoneModel->save([
                        'fornecedor_id' => $fornecedorId,
                        'numero'     => $t['numero'],
                        'tipo'       => $t['tipo'] ?? 'celular',
                        'usuario_id'    => user_id(),
                    ]);
                }
            }
        }

        // ----- Endereços -----
        $enderecos = $this->request->getPost('enderecos');
        $enderecoModel = model('EnderecoFornecedorModel');

        if ($fornecedorId && is_array($enderecos)) {
            $enderecoModel->where('fornecedor_id', $fornecedorId)->delete();

            foreach ($enderecos as $e) {
                if (!empty($e['logradouro']) && !empty($e['numero']) && !empty($e['bairro'])) {
                    $enderecoModel->save([
                        'fornecedor_id'  => $fornecedorId,
                        'logradouro'  => $e['logradouro'],
                        'numero'      => $e['numero'],
                        'complemento' => $e['complemento'] ?? null,
                        'bairro'      => $e['bairro'],
                        'cidade'      => $e['cidade'] ?? '',
                        'estado'      => $e['estado'] ?? '',
                        'cep'         => $e['cep'] ?? '',
                    ]);
                }
            }
        }

        return redirect()->to('/fornecedores')->with('success', 'Fornecedor salvo com sucesso!');
    }

    public function edit($id)
    {
        $data['title'] = 'Editar Fornecedor';
        $data['telefones'] = model('TelefoneFornecedorModel')->where('fornecedor_id', $id)->findAll();
        $data['enderecos'] = model('EnderecoFornecedorModel')->where('fornecedor_id', $id)->findAll();
        $data['fornecedor'] = $this->fornecedorModel->find($id);
        return view('fornecedores/form', $data);
    }

    public function desativar($id)
    {
        $fornecedor = $this->fornecedorModel->find($id);

        if (!$fornecedor) {
            return redirect()->back()->with('error', 'Fornecedor não encontrado.');
        }

        $this->fornecedorModel->update($id, ['status' => 'inativo']);
        return redirect()->to('/fornecedores')->with('success', 'Fornecedor desativado com sucesso!');
    }

    public function perfil($id)
    {
        $fornecedor = $this->fornecedorModel->find($id);

        if (!$fornecedor) {
            return redirect()->to('/fornecedores')->with('error', 'Fornecedor não encontrado.');
        }

        $data = [
            'title'     => 'Perfil do Fornecedor',
            'fornecedor'   => $fornecedor,
            'telefones' => model('TelefoneFornecedorModel')->where('fornecedor_id', $id)->findAll(),
            'enderecos' => model('EnderecoFornecedorModel')->where('fornecedor_id', $id)->findAll(),
        ];

        return view('fornecedores/perfil', $data);
    }
}
