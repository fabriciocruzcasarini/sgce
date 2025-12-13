<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\EstoqueProdutosModel;
use App\Models\MovimentacoesEstoqueModel;
use App\Models\ProdutosModel;
use App\Models\NotasFiscaisModel;

class Estoque extends BaseController
{
    protected $estoqueModel;
    protected $movimentacaoModel;
    protected $produtosModel;
    protected $notasFiscaisModel;

    public function __construct()
    {
        $this->estoqueModel = new EstoqueProdutosModel();
        $this->movimentacaoModel = new MovimentacoesEstoqueModel();
        $this->produtosModel = new ProdutosModel();
        $this->notasFiscaisModel = new NotasFiscaisModel();
    }

    // construtor já declarado abaixo, removido duplicado

    // Relatório de baixas de estoque (saídas FIFO)
    public function relatorioBaixas()
    {
        $produtos = $this->produtosModel->findAll();
        $clientesModel = new \App\Models\ClienteModel();
        $clientes = $clientesModel->findAll();

        // Filtros
        $filtros = [
            'produto_id' => $this->request->getGet('produto_id'),
            'cliente_id' => $this->request->getGet('cliente_id'),
            'data_ini' => $this->request->getGet('data_ini'),
            'data_fim' => $this->request->getGet('data_fim'),
        ];

        $db = \Config\Database::connect();
        $builder = $db->table('movimentacoes_estoque');
        $builder->select('movimentacoes_estoque.*, produtos.nome as nome_produto, clientes.nome as nome_cliente');
        $builder->join('produtos', 'produtos.id = movimentacoes_estoque.produto_id');
        $builder->join('clientes', 'clientes.id = movimentacoes_estoque.cliente_id', 'left');
        $builder->where('movimentacoes_estoque.tipo', 'saida');
        if (!empty($filtros['produto_id'])) {
            $builder->where('movimentacoes_estoque.produto_id', $filtros['produto_id']);
        }
        if (!empty($filtros['cliente_id'])) {
            $builder->where('movimentacoes_estoque.cliente_id', $filtros['cliente_id']);
        }
        if (!empty($filtros['data_ini'])) {
            $builder->where('movimentacoes_estoque.created_at >=', $filtros['data_ini'] . ' 00:00:00');
        }
        if (!empty($filtros['data_fim'])) {
            $builder->where('movimentacoes_estoque.created_at <=', $filtros['data_fim'] . ' 23:59:59');
        }
        $builder->orderBy('movimentacoes_estoque.created_at DESC');
        $baixas = $builder->get()->getResultArray();

        return view('estoque/relatorio_baixas', [
            'produtos' => $produtos,
            'clientes' => $clientes,
            'baixas' => $baixas,
            'filtros' => $filtros,
        ]);
    }

    public function createSaidaManual()
    {
        $data['title'] = 'Registrar Saída de Estoque (Acerto de estoque)';

        $clientesModel = new \App\Models\ClienteModel();
        $data['clientes'] = $clientesModel->findAll();

        // ** LÓGICA CORRIGIDA: Buscar Saldo REAL Agrupado por Lote/Validade (Entradas - Saídas) **
        // Utiliza agregação condicional (CASE) para somar entradas e subtrair saídas.
        $lotesDisponiveis = $this->movimentacaoModel
            ->select("
                movimentacoes_estoque.produto_id, 
                produtos.nome as nome_produto, 
                movimentacoes_estoque.lote, 
                movimentacoes_estoque.validade,
                movimentacoes_estoque.origem_id, 
                SUM(CASE 
                    WHEN movimentacoes_estoque.tipo = 'entrada' THEN movimentacoes_estoque.quantidade 
                    ELSE -movimentacoes_estoque.quantidade 
                END) as saldo_lote
            ")
            ->join('produtos', 'produtos.id = movimentacoes_estoque.produto_id')
            // Removemos o where('tipo', 'entrada')
            ->groupBy('movimentacoes_estoque.produto_id, produtos.nome, movimentacoes_estoque.lote, movimentacoes_estoque.validade')
            ->having('saldo_lote >', 0) // Só mostra lotes com saldo positivo
            ->orderBy('produtos.nome', 'ASC')
            ->orderBy('movimentacoes_estoque.validade', 'ASC') // Ordem para ajudar a visualização FIFO (mais perto do vencimento primeiro)
            ->orderBy('movimentacoes_estoque.lote', 'ASC')
            ->findAll();

        // Esta variável será usada na View
        $data['lotes_disponiveis'] = $lotesDisponiveis;

        return view('estoque/saida_manual', $data);
    }
    // Exibe o formulário de saída manual FIFO
    /*
    public function createSaidaManual()
    {
        $data['title'] = 'Registrar Saída de Estoque (FIFO)';
        $data['produtos'] = $this->produtosModel->findAll();
        $clientesModel = new \App\Models\ClienteModel();
        $data['clientes'] = $clientesModel->findAll();
        return view('estoque/saida_manual', $data);
    }
    */

    // Exibe o saldo de todos os produtos agrupando por lote e validade (FIFO)
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('movimentacoes_estoque');

        // Consulta principal para calcular o saldo de cada produto por lote e validade
        $builder->select('
            movimentacoes_estoque.produto_id,
            produtos.nome as nome_produto,
            movimentacoes_estoque.lote,
            movimentacoes_estoque.validade,
            SUM(CASE WHEN movimentacoes_estoque.tipo = "entrada" THEN movimentacoes_estoque.quantidade ELSE 0 END) as total_entrada,
            SUM(CASE WHEN movimentacoes_estoque.tipo = "saida" THEN movimentacoes_estoque.quantidade ELSE 0 END) as total_saida
        ');
        $builder->join('produtos', 'produtos.id = movimentacoes_estoque.produto_id');
        $builder->groupBy('movimentacoes_estoque.produto_id, movimentacoes_estoque.lote, movimentacoes_estoque.validade, produtos.nome');
        $builder->orderBy('produtos.nome ASC, movimentacoes_estoque.validade ASC, movimentacoes_estoque.lote ASC');
        $result = $builder->get()->getResultArray();

        // Calcula o saldo final de cada grupo
        $estoque = [];
        foreach ($result as $row) {
            $saldo = ($row['total_entrada'] ?? 0) - ($row['total_saida'] ?? 0);
            $estoque[] = [
                'produto_id' => $row['produto_id'],
                'nome_produto' => $row['nome_produto'],
                'lote' => $row['lote'],
                'validade' => $row['validade'],
                'quantidade' => $saldo
            ];
        }

        // Variáveis para os cálculos adicionais
        $dataAtual = date('Y-m-d');
        $dataLimite = date('Y-m-d', strtotime('+30 days'));

        $saldoPositivo = 0;
        $saldoNegativo = 0;
        $saldoZerado = 0;
        $validadeVencida = 0;
        $validadeProxima = 0;

        foreach ($estoque as $item) {
            $quantidade = $item['quantidade'];
            $validade = $item['validade'];

            if ($quantidade > 0) {
                $saldoPositivo++;
            } elseif ($quantidade < 0) {
                $saldoNegativo++;
            } else {
                $saldoZerado++;
            }

            if (!empty($validade)) {
                if ($validade < $dataAtual) {
                    $validadeVencida++;
                } elseif ($validade >= $dataAtual && $validade <= $dataLimite) {
                    $validadeProxima++;
                }
            }
        }

        // Consulta para obter a quantidade total de produtos cadastrados
        $totalProdutos = $this->produtosModel->countAllResults();

        // Consulta para obter a quantidade de itens com saída para cliente
        $builder = $db->table('movimentacoes_estoque');
        $builder->where('tipo', 'saida');
        $builder->where('cliente_id IS NOT NULL');
        $itensComSaidaParaCliente = $builder->countAllResults();

        // Consulta para saídas de produtos por mês
        $builder = $db->table('movimentacoes_estoque');
        $builder->select('MONTH(created_at) as mes, YEAR(created_at) as ano, SUM(quantidade) as total_saidas');
        $builder->where('tipo', 'saida');
        $builder->groupBy('ano, mes');
        $builder->orderBy('ano ASC, mes ASC');
        $saidasPorMes = $builder->get()->getResultArray();

        // Formatar os dados para o Chart.js
        $chartLabels = [];
        $chartData = [];
        foreach ($saidasPorMes as $saida) {
            $chartLabels[] = date('F Y', strtotime($saida['ano'] . '-' . $saida['mes'] . '-01')); // Nome do mês e ano
            $chartData[] = $saida['total_saidas'];
        }

        // Consulta para entradas e saídas de produtos por mês
        $builder = $db->table('movimentacoes_estoque');
        $builder->select('MONTH(created_at) as mes, YEAR(created_at) as ano, 
                      SUM(CASE WHEN tipo = "entrada" THEN quantidade ELSE 0 END) as total_entradas,
                      SUM(CASE WHEN tipo = "saida" THEN quantidade ELSE 0 END) as total_saidas');
        $builder->groupBy('ano, mes');
        $builder->orderBy('ano ASC, mes ASC');
        $movimentacoesPorMes = $builder->get()->getResultArray();

        // Formatar os dados para o Chart.js
        $chartEntradas = [];
        $chartSaidas = [];
        foreach ($movimentacoesPorMes as $movimentacao) {
            $chartEntradas[] = $movimentacao['total_entradas'];
            $chartSaidas[] = $movimentacao['total_saidas'];
        }

        // Consulta para entradas e saídas de produtos por dia
        $builder = $db->table('movimentacoes_estoque');
        $builder->select('DATE(created_at) as dia, 
                      SUM(CASE WHEN tipo = "entrada" THEN quantidade ELSE 0 END) as total_entradas,
                      SUM(CASE WHEN tipo = "saida" THEN quantidade ELSE 0 END) as total_saidas');
        $builder->groupBy('dia');
        $builder->orderBy('dia ASC');
        $movimentacoesPorDia = $builder->get()->getResultArray();

        // Formatar os dados para o Chart.js
        $chartLabels = [];
        $chartEntradas = [];
        $chartSaidas = [];
        foreach ($movimentacoesPorDia as $movimentacao) {
            $chartLabels[] = date('d/m/Y', strtotime($movimentacao['dia'])); // Formata a data para o formato dia/mês/ano
            $chartEntradas[] = $movimentacao['total_entradas'];
            $chartSaidas[] = $movimentacao['total_saidas'];
        }

        $data['title'] = 'Estoque Atual dos Produtos (Agrupado por Lote e Validade)';
        $data['estoque'] = $estoque;
        $data['saldoPositivo'] = $saldoPositivo;
        $data['saldoNegativo'] = $saldoNegativo;
        $data['saldoZerado'] = $saldoZerado;
        $data['validadeVencida'] = $validadeVencida;
        $data['validadeProxima'] = $validadeProxima;
        $data['totalProdutos'] = $totalProdutos;
        $data['itensComSaidaParaCliente'] = $itensComSaidaParaCliente;
        $data['chartLabels'] = $chartLabels;
        $data['chartEntradas'] = $chartEntradas;
        $data['chartSaidas'] = $chartSaidas;

        return view('estoque/index', $data);
    }

    // Exibe o histórico de movimentações de um produto
    /* Metodo original mantido como comentário
    public function historico($produto_id)
    {
        $data['title'] = 'Histórico de Movimentações';
        $data['movimentacoes'] = $this->movimentacaoModel
            ->select('movimentacoes_estoque.*, notas_fiscais.numero as numero_nf')
            ->join('notas_fiscais', 'notas_fiscais.id = movimentacoes_estoque.origem_id', 'left')
            ->where('produto_id', $produto_id)
            ->orderBy('origem_id', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->findAll();
        $data['produto'] = $this->produtosModel->find($produto_id);
        return view('estoque/historico', $data);
    }
    */
    public function historico($produto_id)
    {
        $produto = $this->produtosModel->find($produto_id);
        if (!$produto) {
            return redirect()->back()->with('error', 'Produto não encontrado.');
        }

        $movimentacoes = $this->movimentacaoModel
            ->select('movimentacoes_estoque.*')
            ->where('produto_id', $produto_id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $movimentacoes_agrupadas = [];

        // Busca dados de origem (NF) e agrupa as movimentações por Origem e ID
        foreach ($movimentacoes as $mov) {
            $numero_nf = '';
            $origem_label = '';
            // Cria uma chave única baseada no tipo de origem e seu ID
            $origem_key = $mov['origem'] . '-' . ($mov['origem_id'] ?? '0');

            if ($mov['origem'] == 'nf' && $mov['origem_id']) {
                $nota = $this->notasFiscaisModel->find($mov['origem_id']);
                $numero_nf = $nota['numero'] ?? 'NF não encontrada';
                $origem_label = "Nota Fiscal (NF #" . $numero_nf . ")";
            } elseif ($mov['origem'] == 'entrada_manual') {
                $origem_label = "Entrada Manual";
            } elseif ($mov['origem'] == 'saida_manual') {
                $origem_label = "Saída Manual";
            } else {
                $origem_label = ucfirst($mov['origem']); // Para qualquer outra origem
            }

            // Agrupa as movimentações pela chave de origem
            if (!isset($movimentacoes_agrupadas[$origem_key])) {
                $movimentacoes_agrupadas[$origem_key] = [
                    'origem_label' => $origem_label,
                    'items' => [],
                ];
            }
            $movimentacoes_agrupadas[$origem_key]['items'][] = $mov;
        }


        $data = [
            'title' => 'Histórico de Movimentações',
            'produto' => $produto,
            // Passa o array agrupado para a view
            'movimentacoes_agrupadas' => $movimentacoes_agrupadas,
        ];

        return view('estoque/historico', $data);
    }

    // Registra uma saída de estoque
    public function registrarSaida()
    {
        $produto_id = $this->request->getPost('produto_id');
        $quantidade = floatval($this->request->getPost('quantidade'));
        $origem = $this->request->getPost('origem') ?? 'acerto_estoque';
        $origem_id = $this->request->getPost('origem_id') ?? null;

        // Buscar todos os lotes/validade com saldo, ordenando pelo vencimento mais próximo (FIFO)
        $db = \Config\Database::connect();
        $builder = $db->table('movimentacoes_estoque');
        $builder->select('lote, validade, 
            SUM(CASE WHEN tipo = "entrada" THEN quantidade ELSE 0 END) as total_entrada, 
            SUM(CASE WHEN tipo = "saida" THEN quantidade ELSE 0 END) as total_saida');
        $builder->where('produto_id', $produto_id);
        $builder->groupBy('lote, validade');
        $builder->having('(SUM(CASE WHEN tipo = "entrada" THEN quantidade ELSE 0 END) - SUM(CASE WHEN tipo = "saida" THEN quantidade ELSE 0 END)) >', 0);
        $builder->orderBy('validade ASC, lote ASC');
        $lotes = $builder->get()->getResultArray();

        $quantidadeRestante = $quantidade;
        $movimentacoes = [];
        foreach ($lotes as $lote) {
            $saldoLote = ($lote['total_entrada'] ?? 0) - ($lote['total_saida'] ?? 0);
            if ($saldoLote <= 0) continue;
            $qtdSaida = min($quantidadeRestante, $saldoLote);
            $movimentacoes[] = [
                'produto_id' => $produto_id,
                'tipo' => 'saida',
                'quantidade' => $qtdSaida,
                'lote' => $lote['lote'],
                'validade' => $lote['validade'],
                'origem' => $origem,
                'origem_id' => $origem_id,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $quantidadeRestante -= $qtdSaida;
            if ($quantidadeRestante <= 0) break;
        }

        if ($quantidadeRestante > 0) {
            return redirect()->back()->with('error', 'Estoque insuficiente para saída.');
        }

        // Registrar as movimentações de saída
        foreach ($movimentacoes as $mov) {
            $this->movimentacaoModel->insert($mov);
        }

        // Atualizar saldo total do produto na tabela estoque_produtos
        $estoque = $this->estoqueModel->where('produto_id', $produto_id)->first();
        if ($estoque) {
            $this->estoqueModel->update($estoque['id'], [
                'quantidade' => $estoque['quantidade'] - $quantidade
            ]);
        }

        return redirect()->back()->with('success', 'Saída de estoque registrada com sucesso!');
    }

    // Registra uma entrada manual de estoque
    public function registrarEntrada()
    {
        $produto_id = $this->request->getPost('produto_id');
        $quantidade = $this->request->getPost('quantidade');
        $origem = $this->request->getPost('origem') ?? 'entrada_manual';
        $origem_id = $this->request->getPost('origem_id') ?? null;
        $lote = $this->request->getPost('lote');
        $validade = $this->request->getPost('validade');

        $estoque = $this->estoqueModel->where('produto_id', $produto_id)->first();
        if ($estoque) {
            $this->estoqueModel->update($estoque['id'], [
                'quantidade' => $estoque['quantidade'] + $quantidade
            ]);
        } else {
            $this->estoqueModel->insert([
                'produto_id' => $produto_id,
                'quantidade' => $quantidade
            ]);
        }

        $this->movimentacaoModel->insert([
            'produto_id' => $produto_id,
            'tipo' => 'entrada',
            'quantidade' => $quantidade,
            'lote' => $lote,
            'validade' => $validade,
            'origem' => $origem,
            'origem_id' => $origem_id,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Entrada de estoque registrada com sucesso!');
    }

    public function createEntradaManual()
    {
        $data['title'] = 'Cadastrar Entrada Manual de Estoque';
        $data['produtos'] = $this->produtosModel->findAll();
        return view('estoque/entrada_manual', $data);
    }

    /*
        * Exibe o formulário de entrada por nota fiscal
        Essa Entrada por nota fiscal já foi implementada na consolidação da Nota fiscal
    public function createEntradaPorNF()
    {
        $data['title'] = 'Cadastrar Entrada por Nota Fiscal';
        $notasFiscais = $this->notasFiscaisModel
            ->select('notas_fiscais.id, notas_fiscais.numero, notas_fiscais.fornecedor_id,
            fornecedores.nome_fantasia')
            ->join('fornecedores', 'fornecedores.id = notas_fiscais.fornecedor_id')
            ->where('notas_fiscais.status <>', 'consolidada')
            ->findAll();
        $data['notasFiscais'] = $notasFiscais;
        $data['produtos'] = $this->produtosModel->findAll();
        return view('estoque/entrada_por_nf', $data);
    }
    */

    // Retorna os produtos (itens) de uma nota fiscal em JSON para uso em AJAX
    public function produtosPorNota($nota_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('itens_nota_fiscal');
        $builder->select('itens_nota_fiscal.produto_id, produtos.nome');
        $builder->join('produtos', 'produtos.id = itens_nota_fiscal.produto_id');
        $builder->where('itens_nota_fiscal.nota_id', $nota_id);
        $builder->where('itens_nota_fiscal.status <>', 'baixado');
        $builder->groupBy('itens_nota_fiscal.produto_id');
        $result = $builder->get()->getResultArray();
        return $this->response->setJSON($result);
    }

    public function createSaidaCliente()
    {
        $data['title'] = 'Registrar Saída de Estoque para Cliente (FIFO)';

        $clientesModel = new \App\Models\ClienteModel();
        $data['clientes'] = $clientesModel->findAll();

        // ** LÓGICA CORRIGIDA: Buscar Saldo REAL Agrupado por Lote/Validade (Entradas - Saídas) **
        // Utiliza agregação condicional (CASE) para somar entradas e subtrair saídas.
        $lotesDisponiveis = $this->movimentacaoModel
            ->select("
                movimentacoes_estoque.produto_id, 
                produtos.nome as nome_produto, 
                movimentacoes_estoque.lote, 
                movimentacoes_estoque.validade,
                movimentacoes_estoque.origem_id, 
                SUM(CASE 
                    WHEN movimentacoes_estoque.tipo = 'entrada' THEN movimentacoes_estoque.quantidade 
                    ELSE -movimentacoes_estoque.quantidade 
                END) as saldo_lote
            ")
            ->join('produtos', 'produtos.id = movimentacoes_estoque.produto_id')
            // Removemos o where('tipo', 'entrada')
            ->groupBy('movimentacoes_estoque.produto_id, produtos.nome, movimentacoes_estoque.lote, movimentacoes_estoque.validade')
            ->having('saldo_lote >', 0) // Só mostra lotes com saldo positivo
            ->orderBy('produtos.nome', 'ASC')
            ->orderBy('movimentacoes_estoque.validade', 'ASC') // Ordem para ajudar a visualização FIFO (mais perto do vencimento primeiro)
            ->orderBy('movimentacoes_estoque.lote', 'ASC')
            ->findAll();

        // Esta variável será usada na View
        $data['lotes_disponiveis'] = $lotesDisponiveis;

        return view('estoque/saida_cliente', $data);
    }

    /* Registra uma saída de estoque para cliente
    public function registrarSaidaCliente()
    {
        $produto_id = $this->request->getPost('produto_id');
        $cliente_id = $this->request->getPost('cliente_id');
        $quantidade = floatval($this->request->getPost('quantidade'));
        $observacao = $this->request->getPost('observacao') ?? null;

        // Buscar todos os lotes/validade com saldo, ordenando pelo vencimento mais próximo (FIFO)
        $db = \Config\Database::connect();
        $builder = $db->table('movimentacoes_estoque');
        $builder->select('lote, validade, 
            SUM(CASE WHEN tipo = "entrada" THEN quantidade ELSE 0 END) as total_entrada, 
            SUM(CASE WHEN tipo = "saida" THEN quantidade ELSE 0 END) as total_saida');
        $builder->where('produto_id', $produto_id);
        $builder->groupBy('lote, validade');
        $builder->having('(SUM(CASE WHEN tipo = "entrada" THEN quantidade ELSE 0 END) - SUM(CASE WHEN tipo = "saida" THEN quantidade ELSE 0 END)) >', 0);
        $builder->orderBy('validade ASC, lote ASC');
        $lotes = $builder->get()->getResultArray();

        $quantidadeRestante = $quantidade;
        $movimentacoes = [];
        foreach ($lotes as $lote) {
            $saldoLote = ($lote['total_entrada'] ?? 0) - ($lote['total_saida'] ?? 0);
            if ($saldoLote <= 0) continue;
            $qtdSaida = min($quantidadeRestante, $saldoLote);
            $movimentacoes[] = [
                'produto_id' => $produto_id,
                'cliente_id' => $cliente_id,
                'tipo' => 'saida',
                'quantidade' => $qtdSaida,
                'lote' => $lote['lote'],
                'validade' => $lote['validade'],
                'origem' => 'saida_cliente',
                'origem_id' => null,
                'observacao' => $observacao,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $quantidadeRestante -= $qtdSaida;
            if ($quantidadeRestante <= 0) break;
        }

        if ($quantidadeRestante > 0) {
            return redirect()->back()->with('error', 'Estoque insuficiente para saída.');
        }

        // Registrar as movimentações de saída
        foreach ($movimentacoes as $mov) {
            $this->movimentacaoModel->insert($mov);
        }

        // Atualizar saldo total do produto na tabela estoque_produtos
        $estoque = $this->estoqueModel->where('produto_id', $produto_id)->first();
        if ($estoque) {
            $this->estoqueModel->update($estoque['id'], [
                'quantidade' => $estoque['quantidade'] - $quantidade
            ]);
        }

        return redirect()->to('/estoque')->with('success', 'Saída de estoque registrada com sucesso!');
    } */
    public function registrarSaidaCliente()
    {
        $produto_id = $this->request->getPost('produto_id');
        $cliente_id = $this->request->getPost('cliente_id');
        $quantidade = floatval($this->request->getPost('quantidade'));
        $origem = $this->request->getPost('origem') ?? 'saída_cliente';
        $origem_id = $this->request->getPost('origem_id') ?? null;

        // Buscar todos os lotes/validade com saldo, ordenando pelo vencimento mais próximo (FIFO)
        $db = \Config\Database::connect();
        $builder = $db->table('movimentacoes_estoque');
        $builder->select('lote, validade, data_entrada,
            SUM(CASE WHEN tipo = "entrada" THEN quantidade ELSE 0 END) as total_entrada, 
            SUM(CASE WHEN tipo = "saida" THEN quantidade ELSE 0 END) as total_saida');
        $builder->where('produto_id', $produto_id);
        $builder->groupBy('lote, validade');
        $builder->having('(SUM(CASE WHEN tipo = "entrada" THEN quantidade ELSE 0 END) - SUM(CASE WHEN tipo = "saida" THEN quantidade ELSE 0 END)) >', 0);
        $builder->orderBy('validade ASC, lote ASC');
        $lotes = $builder->get()->getResultArray();

        $quantidadeRestante = $quantidade;
        $movimentacoes = [];
        foreach ($lotes as $lote) {
            $saldoLote = ($lote['total_entrada'] ?? 0) - ($lote['total_saida'] ?? 0);
            if ($saldoLote <= 0) continue;
            $qtdSaida = min($quantidadeRestante, $saldoLote);
            $movimentacoes[] = [
                'produto_id' => $produto_id,
                'cliente_id' => $cliente_id,
                'tipo' => 'saida',
                'quantidade' => $qtdSaida,
                'data_saida' => $lote['data_entrada'],
                'lote' => $lote['lote'],
                'validade' => $lote['validade'],
                'origem' => $origem,
                'origem_id' => $origem_id,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $quantidadeRestante -= $qtdSaida;
            if ($quantidadeRestante <= 0) break;
        }

        if ($quantidadeRestante > 0) {
            return redirect()->back()->with('error', 'Estoque insuficiente para saída.');
        }

        // Registrar as movimentações de saída
        foreach ($movimentacoes as $mov) {
            $this->movimentacaoModel->insert($mov);
        }

        // Atualizar saldo total do produto na tabela estoque_produtos
        $estoque = $this->estoqueModel->where('produto_id', $produto_id)->first();
        if ($estoque) {
            $this->estoqueModel->update($estoque['id'], [
                'quantidade' => $estoque['quantidade'] - $quantidade
            ]);
        }

        return redirect()->back()->with('success', 'Saída de estoque registrada com sucesso!');
    }
}
