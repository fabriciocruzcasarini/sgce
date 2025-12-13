<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClienteModel;

class Clientes extends BaseController
{
    protected $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    public function index()
    {
        $data['title'] = 'Clientes';
        $data['clientes'] = $this->clienteModel->where('status', 'ativo')->findAll();
        return view('clientes/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Cadastrar Cliente';
        return view('clientes/form', $data);
    }

    public function store()
    {
        $dadosCliente = $this->request->getPost();
        $imagem = $this->request->getFile('imagem');
        $uploadPath = FCPATH . 'uploads/clientes';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $id = isset($dadosCliente['id']) ? $dadosCliente['id'] : null;
        $imagemBanco = null;
        if ($id) {
            $clienteExistente = $this->clienteModel->find($id);
            $imagemBanco = $clienteExistente['imagem'] ?? null;
        }

        if ($imagem && $imagem->isValid() && !$imagem->hasMoved()) {
            $novoNome = $imagem->getRandomName();
            // Se for edição
            if ($id) {
                if (empty($imagemBanco)) {
                    // Não tem imagem no banco, faz upload normalmente
                    if ($imagem->move($uploadPath, $novoNome)) {
                        $dadosCliente['imagem'] = $novoNome;
                    } else {
                        return redirect()->back()->withInput()->with('errors', ['imagem' => 'Erro ao enviar a imagem. Por favor, tente novamente.']);
                    }
                } else {
                    // Tem imagem no banco
                    if ($imagemBanco === $imagem->getName()) {
                        // Mesmo nome, não faz upload
                        $dadosCliente['imagem'] = $imagemBanco;
                    } else {
                        // Nome diferente, faz upload e remove antiga
                        if ($imagem->move($uploadPath, $novoNome)) {
                            $dadosCliente['imagem'] = $novoNome;
                            $imagemAntigaPath = $uploadPath . DIRECTORY_SEPARATOR . $imagemBanco;
                            if (is_file($imagemAntigaPath)) {
                                @unlink($imagemAntigaPath);
                            }
                        } else {
                            return redirect()->back()->withInput()->with('errors', ['imagem' => 'Erro ao enviar a nova imagem. Por favor, tente novamente.']);
                        }
                    }
                }
            } else {
                // Cadastro novo
                if ($imagem->move($uploadPath, $novoNome)) {
                    $dadosCliente['imagem'] = $novoNome;
                } else {
                    return redirect()->back()->withInput()->with('errors', ['imagem' => 'Erro ao enviar a imagem. Por favor, tente novamente.']);
                }
            }
        } else if ($id && $imagemBanco) {
            // Se não enviou nova imagem e está editando, mantém a imagem antiga
            $dadosCliente['imagem'] = $imagemBanco;
        }

        // Inicia a transação
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Valida e salva cliente
            if (!$this->clienteModel->save($dadosCliente)) {
                throw new \Exception('Erro ao salvar cliente: ' . implode(', ', $this->clienteModel->errors()));
            }

            // ID do cliente (para novo ou edição)
            $clienteId = $this->clienteModel->getInsertID() ?: $dadosCliente['id'] ?? null;

            // ----- Telefones -----
            $telefones = $this->request->getPost('telefones');
            $telefoneModel = model('TelefoneClienteModel');

            if ($clienteId && is_array($telefones)) {
                $telefoneModel->where('cliente_id', $clienteId)->delete();

                foreach ($telefones as $t) {
                    if (!empty($t['numero'])) {
                        $telefoneModel->save([
                            'cliente_id' => $clienteId,
                            'numero'     => $t['numero'],
                            'tipo'       => $t['tipo'] ?? 'celular',
                        ]);
                    }
                }
            }

            // ----- Endereços -----
            $enderecos = $this->request->getPost('enderecos');
            $enderecoModel = model('EnderecoClienteModel');

            if ($clienteId && is_array($enderecos)) {
                $enderecoModel->where('cliente_id', $clienteId)->delete();

                foreach ($enderecos as $e) {
                    if (!empty($e['logradouro']) && !empty($e['numero']) && !empty($e['bairro'])) {
                        $enderecoModel->save([
                            'cliente_id'  => $clienteId,
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

            // Confirma a transação
            $db->transCommit();

            return redirect()->to('/clientes')->with('success', 'Cliente salvo com sucesso!');
        } catch (\Exception $e) {
            // Reverte a transação em caso de erro
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('errors', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Editar Cliente';
        $data['telefones'] = model('TelefoneClienteModel')->where('cliente_id', $id)->findAll();
        $data['enderecos'] = model('EnderecoClienteModel')->where('cliente_id', $id)->findAll();
        $data['cliente'] = $this->clienteModel->find($id);
        return view('clientes/form', $data);
    }

    public function desativar($id)
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente) {
            return redirect()->back()->with('error', 'Cliente não encontrado.');
        }

        $this->clienteModel->update($id, ['status' => 'inativo']);
        return redirect()->to('/clientes')->with('success', 'Cliente desativado com sucesso!');
    }

    public function perfil($id)
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente) {
            return redirect()->to('/clientes')->with('error', 'Cliente não encontrado.');
        }

        $data = [
            'title'     => 'Perfil do Cliente',
            'cliente'   => $cliente,
            'telefones' => model('TelefoneClienteModel')->where('cliente_id', $id)->findAll(),
            'enderecos' => model('EnderecoClienteModel')->where('cliente_id', $id)->findAll(),
        ];

        return view('clientes/perfil', $data);
    }
}
