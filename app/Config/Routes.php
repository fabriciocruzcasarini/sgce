<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
service('auth')->routes($routes);
$routes->get('/', 'Home::index');

$routes->group('clientes', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Clientes::index');
    $routes->get('create', 'Clientes::create');
    $routes->post('store', 'Clientes::store');
    $routes->get('edit/(:num)', 'Clientes::edit/$1');
    $routes->get('desativar/(:num)', 'Clientes::desativar/$1');
    $routes->get('perfil/(:num)', 'Clientes::perfil/$1');
});

$routes->group('fornecedores', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Fornecedores::index');
    $routes->get('create', 'Fornecedores::create');
    $routes->post('store', 'Fornecedores::store');
    $routes->get('edit/(:num)', 'Fornecedores::edit/$1');
    $routes->get('desativar/(:num)', 'Fornecedores::desativar/$1');
    $routes->get('perfil/(:num)', 'Fornecedores::perfil/$1');
});

$routes->group('grupo-produtos', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'GrupoProdutos::index');
    $routes->get('create', 'GrupoProdutos::create');
    $routes->post('store', 'GrupoProdutos::store');
    $routes->get('edit/(:num)', 'GrupoProdutos::edit/$1');
    $routes->get('desativar/(:num)', 'GrupoProdutos::desativar/$1');
    $routes->get('perfil/(:num)', 'GrupoProdutos::perfil/$1');
});

$routes->group('subgrupo-produtos', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'SubgrupoProdutos::index');
    $routes->get('create', 'SubgrupoProdutos::create');
    $routes->post('store', 'SubgrupoProdutos::store');
    $routes->get('edit/(:num)', 'SubgrupoProdutos::edit/$1');
    $routes->get('desativar/(:num)', 'SubgrupoProdutos::desativar/$1');
    $routes->get('perfil/(:num)', 'SubgrupoProdutos::perfil/$1');
    $routes->get('por-grupo/(:num)', 'SubgrupoProdutos::porGrupo/$1');
});

$routes->group('produtos', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Produtos::index');
    $routes->get('create', 'Produtos::create');
    $routes->post('store', 'Produtos::store');
    $routes->get('edit/(:num)', 'Produtos::edit/$1');
    $routes->get('desativar/(:num)', 'Produtos::desativar/$1');
    $routes->get('perfil/(:num)', 'Produtos::perfil/$1');
});

$routes->group('notas-fiscais', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'NotasFiscais::index');
    $routes->get('create', 'NotasFiscais::create');
    $routes->post('store', 'NotasFiscais::store');
    $routes->get('edit/(:num)', 'NotasFiscais::edit/$1');
    $routes->get('desativar/(:num)', 'NotasFiscais::delete/$1');
    $routes->get('perfil/(:num)', 'NotasFiscais::perfil/$1');
    $routes->post('parse-xml', 'NotasFiscais::parseXml');
    $routes->get('get-itens-nota-fiscal/(:num)', 'NotasFiscais::getItensNotaFiscal/$1');
    $routes->get('consolidar-nota/(:num)', 'NotasFiscais::consolidar/$1');
});

$routes->group('itens-nota-fiscal', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'ItensNotaFiscal::index');
    $routes->get('create/(:num)', 'ItensNotaFiscal::create/$1');
    $routes->post('store', 'ItensNotaFiscal::store');
    $routes->get('edit/(:num)', 'ItensNotaFiscal::edit/$1');
    $routes->get('delete/(:num)', 'ItensNotaFiscal::delete/$1');
});

$routes->group('estoque', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Estoque::index');
    $routes->get('historico/(:num)', 'Estoque::historico/$1');
    $routes->get('produtosPorNota/(:num)', 'Estoque::produtosPorNota/$1');
    $routes->post('registrarSaida', 'Estoque::registrarSaida');
    $routes->post('registrarEntrada', 'Estoque::registrarEntrada');
    $routes->get('entrada-manual-estoque/', 'Estoque::createEntradaManual');
    //$routes->get('entrada-por-nota-fiscal', 'Estoque::createEntradaPorNF');
    $routes->get('saida-estoque/', 'Estoque::createSaidaManual');
    $routes->get('relatorio-baixas', 'Estoque::relatorioBaixas');
    $routes->get('saida-estoque-cliente', 'Estoque::createSaidaCliente');
    $routes->post('registrarSaidaCliente', 'Estoque::registrarSaidaCliente');
});

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'login'], function ($routes) {
    // Usuarios
    $routes->get('/', 'Usuarios::index');
    $routes->get('usuarios/create', 'Usuarios::create');
    $routes->post('usuarios/store', 'Usuarios::store');
    $routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');
    $routes->post('usuarios/update/(:num)', 'Usuarios::update/$1');
    $routes->post('usuarios/delete/(:num)', 'Usuarios::delete/$1');

    // Grupos
    $routes->get('grupos', 'Grupos::index');
    $routes->get('grupos/create', 'Grupos::create');
    $routes->post('grupos/store', 'Grupos::store');
    $routes->get('grupos/edit/(:num)', 'Grupos::edit/$1');
    $routes->post('grupos/update/(:num)', 'Grupos::update/$1');
    $routes->post('grupos/delete/(:num)', 'Grupos::delete/$1');

    // Permissões
    $routes->get('permissoes', 'Permissoes::index');
    $routes->get('permissoes/create', 'Permissoes::create');
    $routes->post('permissoes/store', 'Permissoes::store');
    $routes->get('permissoes/edit/(:num)', 'Permissoes::edit/$1');
    $routes->post('permissoes/update/(:num)', 'Permissoes::update/$1');
    $routes->post('permissoes/delete/(:num)', 'Permissoes::delete/$1');
});
