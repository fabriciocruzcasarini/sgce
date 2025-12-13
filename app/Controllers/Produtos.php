<?php

namespace App\Controllers;

use App\Models\ProdutosModel;
use App\Models\SubgrupoProdutosModel;
use App\Models\GrupoProdutosModel;
use App\Models\FornecedoresModel;

class Produtos extends BaseController
{
    protected $produtoModel;
    protected $subgrupoModel;
    protected $grupoModel;
    protected $fornecedorModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutosModel();
        $this->subgrupoModel = new SubgrupoProdutosModel();
        $this->grupoModel = new GrupoProdutosModel();
        $this->fornecedorModel = new FornecedoresModel();
    }

    public function index()
    {
        $data['title'] = 'Lista de Produtos Ativos';
        $data['produtos'] = $this->produtoModel
            ->select('
                produtos.*,
                subgrupo_produtos.nome as nome_subgrupo,
                grupo_produtos.nome as nome_grupo,
                fornecedores.nome_fantasia as nome_fornecedor
            ')
            ->join('fornecedores', 'fornecedores.id = produtos.fornecedor_id')
            ->join('subgrupo_produtos', 'subgrupo_produtos.id = produtos.subgrupo_produtos_id')
            ->join('grupo_produtos', 'grupo_produtos.id = subgrupo_produtos.grupo_produtos_id') // Adicione este join
            ->where('produtos.status', 'ativo')
            ->orderBy('produtos.nome', 'ASC')
            ->findAll();

        return view('produtos/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Novo Cadastro de Produto';
        $data['grupos'] = $this->grupoModel->where('status', 'ativo')->findAll();
        $data['subgrupos'] = $this->subgrupoModel->where('status', 'ativo')->findAll();
        $data['fornecedores'] = $this->fornecedorModel->where('status', '1')->findAll();
        return view('produtos/form', $data);
    }

    public function store()
    {
        $dadosProduto = $this->request->getPost();
        $imagem = $this->request->getFile('imagem');
        $uploadPath = FCPATH . 'uploads/produtos';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $id = isset($dadosProduto['id']) ? $dadosProduto['id'] : null;
        $imagemBanco = null;
        if ($id) {
            $produtoExistente = $this->produtoModel->find($id);
            $imagemBanco = $produtoExistente['imagem'] ?? null;
        }

        if ($imagem && $imagem->isValid() && !$imagem->hasMoved()) {
            $novoNome = $imagem->getRandomName();
            // Se for edição
            if ($id) {
                if (empty($imagemBanco)) {
                    // Não tem imagem no banco, faz upload normalmente
                    if ($imagem->move($uploadPath, $novoNome)) {
                        $dadosProduto['imagem'] = $novoNome;
                    } else {
                        return redirect()->back()->withInput()->with('errors', ['imagem' => 'Erro ao enviar a imagem. Por favor, tente novamente.']);
                    }
                } else {
                    // Tem imagem no banco
                    if ($imagemBanco === $imagem->getName()) {
                        // Mesmo nome, não faz upload
                        $dadosProduto['imagem'] = $imagemBanco;
                    } else {
                        // Nome diferente, faz upload e remove antiga
                        if ($imagem->move($uploadPath, $novoNome)) {
                            $dadosProduto['imagem'] = $novoNome;
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
                    $dadosProduto['imagem'] = $novoNome;
                } else {
                    return redirect()->back()->withInput()->with('errors', ['imagem' => 'Erro ao enviar a imagem. Por favor, tente novamente.']);
                }
            }
        } else if ($id && $imagemBanco) {
            // Se não enviou nova imagem e está editando, mantém a imagem antiga
            $dadosProduto['imagem'] = $imagemBanco;
        }

        $dadosProduto['usuario_id'] = auth()->id();

        if (!$this->produtoModel->save($dadosProduto)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->produtoModel->errors());
        }

        return redirect()->to('/produtos')->with('success', 'Produto salvo com sucesso!');
    }

    public function edit($id)
    {
        $data['title'] = 'Editar Produto';
        $data['produto'] = $this->produtoModel->find($id);
        $subgrupo = $this->subgrupoModel->find($data['produto']['subgrupo_produtos_id']);
        $data['produto']['grupo_produtos_id'] = $subgrupo['grupo_produtos_id'] ?? null;
        $data['subgrupos'] = $this->subgrupoModel->where('status', 'ativo')->findAll();
        $data['grupos'] = $this->grupoModel->where('status', 'ativo')->findAll();
        $data['fornecedores'] = $this->fornecedorModel->where('status', 'ativo')->findAll();

        if (!$data['produto']) {
            return redirect()->to('/produtos')->with('error', 'Produto não encontrado.');
        }

        return view('produtos/form', $data);
    }

    public function desativar($id)
    {
        $produto = $this->produtoModel->find($id);

        if (!$produto) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        $this->produtoModel->update($id, ['status' => 'inativo']);

        return redirect()->to('/produtos')->with('success', 'Produto desativado com sucesso!');
    }

    public function perfil($id = null)
    {
        if (empty($id) || !is_numeric($id)) {
            return redirect()->to('/produtos')->with('error', 'Produto não especificado.');
        }

        $data['title'] = 'Dados do Produto';

        $produto = $this->produtoModel
            ->select('
            produtos.*,
            subgrupo_produtos.nome as nome_subgrupo,
            grupo_produtos.nome as nome_grupo,
            fornecedores.nome_fantasia as nome_fornecedor
        ')
            ->join('fornecedores', 'fornecedores.id = produtos.fornecedor_id', 'left')
            ->join('subgrupo_produtos', 'subgrupo_produtos.id = produtos.subgrupo_produtos_id', 'left')
            ->join('grupo_produtos', 'grupo_produtos.id = subgrupo_produtos.grupo_produtos_id', 'left')
            ->where('produtos.id', $id)
            ->get()
            ->getRowArray();

        if (empty($produto)) {
            return redirect()->to('/produtos')->with('error', 'Produto não encontrado.');
        }

        // Consulta para obter o saldo em estoque separado por lote
        $db = \Config\Database::connect();
        $builder = $db->table('movimentacoes_estoque');
        $builder->select('
            lote,
            validade,
            SUM(CASE WHEN tipo = "entrada" THEN quantidade ELSE 0 END) as total_entrada,
            SUM(CASE WHEN tipo = "saida" THEN quantidade ELSE 0 END) as total_saida
        ');
        $builder->where('produto_id', $id);
        $builder->groupBy('lote, validade');
        $builder->having('(total_entrada - total_saida) >', 0); // Apenas lotes com saldo positivo
        $builder->orderBy('validade', 'ASC');
        $builder->orderBy('lote', 'ASC');
        $saldosPorLote = $builder->get()->getResultArray();

        $produto['estoque_por_lote'] = $saldosPorLote;

        // Consulta para obter todas as notas fiscais que contêm o produto
        $builder = $db->table('itens_nota_fiscal');
        $builder->select('
            notas_fiscais.id,
            notas_fiscais.numero,
            notas_fiscais.data_emissao,
            fornecedores.nome_fantasia as fornecedor,
            itens_nota_fiscal.quantidade,
            itens_nota_fiscal.valor_unitario
        ');
        $builder->join('notas_fiscais', 'notas_fiscais.id = itens_nota_fiscal.nota_id');
        $builder->join('fornecedores', 'fornecedores.id = notas_fiscais.fornecedor_id');
        $builder->where('itens_nota_fiscal.produto_id', $id);
        $builder->orderBy('notas_fiscais.data_emissao', 'DESC');
        $notasFiscais = $builder->get()->getResultArray();

        $produto['notas_fiscais'] = $notasFiscais;

        $data['produtos'] = $produto;

        return view('produtos/perfil', $data);
    }

    /*
    public function perfil($id)
    {
        $data['title'] = 'Dados do Produto';
        $data['produtos'] = $this->produtoModel
            ->select('
                produtos.*,
                subgrupo_produtos.nome as nome_subgrupo,
                grupo_produtos.nome as nome_grupo,
                fornecedores.nome_fantasia as nome_fornecedor
            ')
            ->join('fornecedores', 'fornecedores.id = produtos.fornecedor_id')
            ->join('subgrupo_produtos', 'subgrupo_produtos.id = produtos.subgrupo_produtos_id')
            ->join('grupo_produtos', 'grupo_produtos.id = subgrupo_produtos.grupo_produtos_id') // Adicione este join
            ->where('produtos.status', 'ativo')
            ->orderBy('produtos.nome', 'ASC')
            ->find($id);

        if (!$data['produtos']) {
            return redirect()->to('/produtos')->with('error', 'Produto não encontrado.');
        }

        return view('produtos/perfil', $data);
    }

    
    public function store()
    {
        $dadosProduto = $this->request->getPost();
        $imagem = $this->request->getFile('imagem');

        $uploadPath = FCPATH . 'uploads/produtos';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if ($imagem && $imagem->isValid() && !$imagem->hasMoved()) {
            $novoNome = $imagem->getRandomName();
            if ($imagem->move($uploadPath, $novoNome)) {
                $dadosProduto['imagem'] = $novoNome;
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['imagem' => 'Erro ao enviar a imagem. Por favor, tente novamente.']);
            }
        }

        $dadosProduto['usuario_id'] = auth()->id();

        if (!$this->produtoModel->save($dadosProduto)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->produtoModel->errors());
        }

        return redirect()->to('/produtos')->with('success', 'Produto salvo com sucesso!');
    } */
}
