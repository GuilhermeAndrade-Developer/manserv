<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Classis\Web'], function () {
    Route::get('/login', 'HomeController@login')->name('login');
    Route::post('/authenticate', 'AuthController@authenticate')->name('authenticate');

    Route::middleware('auth')->group(function() {
        Route::get('/logout', 'AuthController@logout')->name('logout');

        Route::get('/', 'HomeController@index')->name('home');

        Route::get('/requisicao-veiculo/create', 'RequisicaoVeiculosController@create')->name('requisicao-veiculo.create');
        Route::get('/cadastro/associar-usuario-ut','AssociarUsuarioUtController@index')->name('lista-associados.index');
        Route::get('/cadastro/cadastrar-condutor','CondutorController@index')->name('lista-condutor.index');
    });
});

//Auth::routes();