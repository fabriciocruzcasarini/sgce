<?php

namespace App\Controllers;

use App\Models\NotasFiscaisModel;
use App\Models\ItensNotaFiscalModel;
use App\Models\FornecedoresModel;
use App\Models\ProdutosModel;

class ItensNotaFiscal extends BaseController
{

    protected $notasFiscaisModel;
    protected $itensNotaFiscalModel;
    protected $fornecedoresModel;
    protected $produtosModel;

    public function __construct()
    {
        $this->notasFiscaisModel = new NotasFiscaisModel();
        $this->itensNotaFiscalModel = new ItensNotaFiscalModel();
        $this->fornecedoresModel = new FornecedoresModel();
        $this->produtosModel = new ProdutosModel();
    }

    public function index()
    {
        return view('layouts/dashboard');
    }

    public function create($id_nota_fiscal)
    {
        $data['title'] = 'Adicionar Itens na Nota Fiscal';
        $data['notaFiscal'] = $this->notasFiscaisModel
            ->select('notas_fiscais.*, fornecedores.nome_fantasia, fornecedores.cnpj')
            ->join('fornecedores', 'fornecedores.id = notas_fiscais.fornecedor_id')
            ->find($id_nota_fiscal);
        $data['produtos'] = $this->produtosModel->where('status', 'ativo')->findAll();

        $itens = $this->itensNotaFiscalModel
            ->select('itens_nota_fiscal.*, notas_fiscais.numero as numero_nota, notas_fiscais.data_emissao, fornecedores.nome_fantasia as fornecedor, produtos.nome as produto, (itens_nota_fiscal.quantidade * itens_nota_fiscal.valor_unitario) as valor_total_item')
            ->join('notas_fiscais', 'notas_fiscais.id = itens_nota_fiscal.nota_id')
            ->join('fornecedores', 'fornecedores.id = notas_fiscais.fornecedor_id')
            ->join('produtos', 'produtos.id = itens_nota_fiscal.produto_id')
            ->where('itens_nota_fiscal.nota_id', $id_nota_fiscal)
            ->findAll();
        $data['itens'] = $itens;

        $total = $this->itensNotaFiscalModel
            ->select('SUM(quantidade * valor_unitario) as total')
            ->where('nota_id', $id_nota_fiscal)
            ->get()
            ->getRowArray();
        $data['total'] = $total['total'] ?? 0;

        // Retorna apenas o valor total (pode ser adaptado para JSON, view, etc)
        //return $total['total'] ?? 0;

        if (!$data['notaFiscal']) {
            return redirect()->back()->with('errors', ['Nota Fiscal não encontrada.']);
        }

        return view('itens_nota_fiscal/form', $data);
    }

    public function store()
    {
        $dadosItem = $this->request->getPost();
        $dadosItem['usuario_id'] = auth()->id();

        // Salva o item
        if (!$this->itensNotaFiscalModel->save($dadosItem)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->itensNotaFiscalModel->errors());
        }

        // Após salvar, verifica o total dos itens x valor total da nota
        $nota = $this->notasFiscaisModel->find($dadosItem['nota_id']);
        $totalItens = $this->itensNotaFiscalModel
            ->select('SUM(quantidade * valor_unitario) as total')
            ->where('nota_id', $dadosItem['nota_id'])
            ->get()
            ->getRowArray();
        $valorTotalItens = (float)($totalItens['total'] ?? 0);
        $valorTotalNota = (float)($nota['valor_total'] ?? 0);

        if ($valorTotalItens > $valorTotalNota) {
            return redirect()->to('/itens-nota-fiscal/create/' . $dadosItem['nota_id'])
                ->with('alert', 'Atenção: O valor total dos itens (' . number_format($valorTotalItens, 2, ',', '.') . ') é maior que o valor total da nota fiscal (' . number_format($valorTotalNota, 2, ',', '.') . ').');
        }

        return redirect()->to('/itens-nota-fiscal/create/' . $dadosItem['nota_id'])->with('success', 'Item adicionado com sucesso.');
    }

    public function delete($id)
    {
        if (!$this->itensNotaFiscalModel->delete($id)) {
            return redirect()->back()->with('error', 'Erro ao excluir o item da Nota Fiscal.');
        }

        return redirect()->to('/itens-nota-fiscal/create/' . $id)->with('success', 'Item excluído com sucesso!');
    }
}
