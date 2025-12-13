<?php

namespace App\Controllers;

use App\Models\NotasFiscaisModel;
use App\Models\FornecedoresModel;
use App\Models\ItensNotaFiscalModel;

class NotasFiscais extends BaseController
{
    protected $notasFiscaisModel;
    protected $fornecedoresModel;
    protected $itensNotaFiscalModel;

    public function __construct()
    {
        $this->notasFiscaisModel = new NotasFiscaisModel();
        $this->fornecedoresModel = new FornecedoresModel();
        $this->itensNotaFiscalModel = new ItensNotaFiscalModel();
    }

    public function index()
    {
        $data['title'] = 'Lista de Notas Fiscais Consolidadas 09';
        $data['notasFiscais'] = $this->notasFiscaisModel
            ->select('notas_fiscais.*, fornecedores.nome_fantasia as nome_fornecedor')
            ->join('fornecedores', 'fornecedores.id = notas_fiscais.fornecedor_id')
            ->where('notas_fiscais.status', 'ativo')
            ->findAll();

        $data['notasFiscais_consolidadas'] = $this->notasFiscaisModel
            ->select('notas_fiscais.*, fornecedores.nome_fantasia as nome_fornecedor')
            ->join('fornecedores', 'fornecedores.id = notas_fiscais.fornecedor_id')
            ->where('notas_fiscais.status', 'consolidada')
            ->findAll();

        return view('notas_fiscais/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Nova Nota Fiscal';
        $data['fornecedores'] = $this->fornecedoresModel->where('status', '1')->findAll();
        return view('notas_fiscais/form', $data);
    }

    public function store()
    {
        $dadosNotaFiscal = $this->request->getPost();
        $dadosNotaFiscal['usuario_id'] = auth()->id();

        if (!isset($dadosNotaFiscal['id'])) {

            $xml_nota = $this->request->getFile('xml');

            // Inicia a transação
            $db = \Config\Database::connect();
            $db->transBegin();

            try {
                if ($xml_nota && $xml_nota->isValid() && !$xml_nota->hasMoved()) {
                    $novoNome = $xml_nota->getRandomName();
                    $xml_nota->move(WRITEPATH . '../public/uploads/xml_notasFiscais', $novoNome);
                    $dadosNotaFiscal['xml'] = $novoNome;
                }

                if (!$this->notasFiscaisModel->save($dadosNotaFiscal)) {
                    throw new \Exception('Erro ao salvar Nota Fiscal: ' . implode(', ', $this->notasFiscaisModel->errors()));
                }

                // Confirma a transação
                $db->transCommit();

                return redirect()->to('/notas-fiscais')->with('success', 'Nota Fiscal salva com sucesso!');
            } catch (\Exception $e) {
                // Reverte a transação em caso de erro
                $db->transRollback();

                return redirect()->back()
                    ->withInput()
                    ->with('errors', [$e->getMessage()]);
            }
        } else {
            // Inicia a transação
            $db = \Config\Database::connect();
            $db->transBegin();
            try {
                if ($dadosNotaFiscal['status'] === 'consolidada') {
                    throw new \Exception('Nota Fiscal Consolidada: Não é possivel alterar.');
                }
                if (!$this->notasFiscaisModel->save($dadosNotaFiscal)) {
                    throw new \Exception('Erro ao salvar Nota Fiscal: ' . implode(', ', $this->notasFiscaisModel->errors()));
                }

                // Confirma a transação
                $db->transCommit();

                return redirect()->to('/notas-fiscais')->with('success', 'Nota Fiscal salva com sucesso!');
            } catch (\Exception $e) {
                // Reverte a transação em caso de erro
                $db->transRollback();

                return redirect()->back()
                    ->withInput()
                    ->with('errors', [$e->getMessage()]);
            }
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Editar Nota Fiscal';
        $data['notaFiscal'] = $this->notasFiscaisModel->find($id);
        $data['fornecedores'] = $this->fornecedoresModel->where('status', '1')->findAll();

        if (!$data['notaFiscal']) {
            return redirect()->to('/notas-fiscais')->with('error', 'Nota Fiscal não encontrada.');
        }
        if ($data['notaFiscal']['status'] === 'consolidada') {
            $data['title'] =  'Nota Fiscal Consolidada';
        }
        $itens = $this->itensNotaFiscalModel
            ->select('itens_nota_fiscal.*, notas_fiscais.numero as numero_nota, notas_fiscais.data_emissao, fornecedores.nome_fantasia as fornecedor, produtos.nome as produto, (itens_nota_fiscal.quantidade * itens_nota_fiscal.valor_unitario) as valor_total_item')
            ->join('notas_fiscais', 'notas_fiscais.id = itens_nota_fiscal.nota_id')
            ->join('fornecedores', 'fornecedores.id = notas_fiscais.fornecedor_id')
            ->join('produtos', 'produtos.id = itens_nota_fiscal.produto_id')
            ->where('itens_nota_fiscal.nota_id', $id)
            ->findAll();
        $data['itens'] = $itens;

        return view('notas_fiscais/form', $data);
    }

    public function update($id)
    {
        $dadosNotaFiscal = $this->request->getPost();

        if (!$this->notasFiscaisModel->update($id, $dadosNotaFiscal)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->notasFiscaisModel->errors());
        }

        return redirect()->to('/notas-fiscais')->with('success', 'Nota Fiscal atualizada com sucesso!');
    }

    public function delete($id)
    {
        if (!$this->notasFiscaisModel->update($id, ['status' => 'inativo'])) {
            return redirect()->back()->with('error', 'Erro ao excluir a Nota Fiscal.');
        }

        return redirect()->to('/notas-fiscais')->with('success', 'Nota Fiscal excluída com sucesso!');
    }

    public function parseXml()
    {
        try {
            $xmlFile = $this->request->getFile('xml');

            if (!$xmlFile->isValid() || $xmlFile->getExtension() !== 'xml') {
                throw new \Exception('Arquivo inválido');
            }

            $xmlPath = WRITEPATH . 'uploads/xml_nota_fiscal/' . $xmlFile->getRandomName();
            $xmlFile->move(WRITEPATH . 'uploads/xml_nota_fiscal', basename($xmlPath));

            $xml = simplexml_load_file($xmlPath);
            if (!$xml || !isset($xml->NFe->infNFe->emit)) {
                throw new \Exception('Estrutura do XML inválida ou incompleta.');
            }

            $cnpjFornecedor = (string) $xml->NFe->infNFe->emit->CNPJ ?? '';
            $cnpjFornecedor = preg_replace('/\D/', '', $cnpjFornecedor);

            $fornecedor = $this->fornecedoresModel->where('cnpj', $cnpjFornecedor)->first();

            if (!$fornecedor) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Fornecedor não encontrado ou cadastrado.',
                    'fornecedor_id' => null,
                ]);
            }

            $data = [
                'numero' => (string) $xml->NFe->infNFe->ide->nNF ?? '',
                'serie' => (string) $xml->NFe->infNFe->ide->serie ?? '',
                'data_emissao' => date('Y-m-d', strtotime((string) $xml->NFe->infNFe->ide->dhEmi ?? '')),
                'valor_total' => (string) $xml->NFe->infNFe->total->ICMSTot->vNF ?? '',
                'fornecedor_id' => $fornecedor['id'],
                'chave_nfe' => (string) $xml->protNFe->infProt->chNFe ?? '',
                'natureza_operacao' => (string) $xml->NFe->infNFe->ide->natOp ?? '',
                'base_calculo_icms' => (string) $xml->NFe->infNFe->total->ICMSTot->vBC ?? '',
                'valor_icms' => (string) $xml->NFe->infNFe->total->ICMSTot->vICMS ?? '',
                'base_calculo_ipi' => (string) $xml->NFe->infNFe->total->ICMSTot->vIPI ?? '',
                'valor_ipi' => (string) $xml->NFe->infNFe->total->ICMSTot->vIPIDevol ?? '',
            ];

            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Marca a nota fiscal como consolidada (status = 'consolidado')
     */
    public function consolidar($id_nota_fiscal)
    {
        $nota_fiscal = $this->notasFiscaisModel->find($id_nota_fiscal);
        $data_entrada_nf = $nota_fiscal['data_entrada'];

        if (!$nota_fiscal) {
            return redirect()->back()->with('error', 'Nota Fiscal não encontrada.');
        }

        // Atualiza status da nota
        $this->notasFiscaisModel->update($id_nota_fiscal, ['status' => 'consolidada']);

        // Atualiza estoque e registra movimentação
        $itens = $this->itensNotaFiscalModel->where('nota_id', $id_nota_fiscal)->findAll();
        $estoqueModel = new \App\Models\EstoqueProdutosModel();
        $movimentacaoModel = new \App\Models\MovimentacoesEstoqueModel();

        foreach ($itens as $item) {
            // Atualiza saldo do produto
            $estoque = $estoqueModel->where('produto_id', $item['produto_id'])->first();
            if ($estoque) {
                $estoqueModel->update($estoque['id'], [
                    'quantidade' => $estoque['quantidade'] + $item['quantidade']
                ]);
            } else {
                $estoqueModel->insert([
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade']
                ]);
            }

            // Registra movimentação
            $movimentacaoModel->insert([
                'produto_id' => $item['produto_id'],
                'tipo' => 'entrada',
                'lote' => $item['lote'],
                'validade' => $item['validade'],
                'quantidade' => $item['quantidade'],
                'data_entrada' => $data_entrada_nf,
                'origem' => 'nota_fiscal',
                'origem_id' => $id_nota_fiscal,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to('/notas-fiscais')->with('success', 'Nota Fiscal consolidada e estoque atualizado!');
    }
}
