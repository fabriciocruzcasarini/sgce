<?php

namespace App\Validation;

use App\Models\NotasFiscaisModel;

class NotasFiscaisRules
{
    public function checkNotaDuplicada(string $numero, string $fields, array $data = []): bool
    {
        [$idField, $fornecedorField, $serieField] = explode(',', $fields);

        $id = $_POST[$idField] ?? null;
        $fornecedor_id = $_POST[$fornecedorField] ?? null;
        $serie = $_POST[$serieField] ?? null;

        if (!$fornecedor_id || !$serie) {
            return true; // Evita falha na validação se os campos não forem preenchidos
        }

        $model = new \App\Models\NotasFiscaisModel();

        $query = $model
            ->where('numero', $numero)
            ->where('fornecedor_id', $fornecedor_id)
            ->where('serie', $serie);

        if ($id) {
            $query->where('id !=', $id);
        }

        return $query->countAllResults() === 0;
    }
}
