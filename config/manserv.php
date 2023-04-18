<?php
return [
    'menu' => [
        [
            'text' => 'Cadastros',
            'icon' => '',
            'label' => '',
            'submenu' => [
                [
                    'text' => 'Cadastrar Preposto',
                    'url' => '/cadastro/cadastrar-preposto',
                    'icon' => '',
                    'label' => 'Cadastrar Preposto',
                    'can' => 'cadastrar_preposto'
                ],
                [
                    'text' => 'Cadastrar Locadora',
                    'url' => '/cadastro/cadastrar-locadora',
                    'icon' => '',
                    'label' => 'Cadastrar Locadora',
                    'can' => 'cadastrar_locadora'
                ],
                [
                    'text' => 'Cadastrar Condutor',
                    'url' => '/cadastro/cadastrar-condutor',
                    'icon' => '',
                    'label' => 'Cadastrar Condutor',
                    'can' => 'cadastrar_condutor'
                ],
                [
                    'text' => 'Associar Usuário à UT',
                    'url' => '/cadastro/associar-usuario-ut',
                    'icon' => '',
                    'label' => 'Associar Usuário à UT',
                    'can' => 'associar_usuario_ut'
                ],
                [
                    'text' => 'Cadastrar Tipo de Manutenção',
                    'url' => '/cadastro/cadastrar-manutencao',
                    'icon' => '',
                    'label' => 'Cadastrar Tipo de Manutenção',
                    'can' => 'cadastrar_tipo_manutencao'
                ],
                [
                    'text' => 'Ger. de Permissões',
                    'url' => '/cadastro/cadastrar-permissao',
                    'icon' => '',
                    'label' => 'Ger. de Permissões',
                    'can' => 'permissao'
                ],
            ]
        ],
        [
            'text' => 'Gestão de Requisição de Veículos',
            'icon' => '',
            'label' => '',
            'submenu' => [
                [
                    'text' => 'Requisição de Veículo',
                    'url' => '/requisicao-veiculo/create',
                    'icon' => '',
                    'label' => 'Requisição de Veículo',
                    'can' => 'requisitar_veiculo'
                ],
                [
                    'text' => 'Consultar Requisição de Veículo',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Consultar Requisição de Veículo',
                    'can' => 'consultar_requisitar_veiculo'
                ],
                [
                    'text' => 'Aprovação de Requisição de Veículo',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Aprovação de Requisição de Veículo',
                    'can' => 'aprovar_requisitar_veiculo'
                ],
                [
                    'text' => 'Consultar Requisição Pendente de Atendimento',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Consultar Requisição Pendente de Atendimento',
                    'can' => 'consultar_requisicao_pendente_atendimento'
                ],
                [
                    'text' => 'Consultar Requisição Fleet em Negociação',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Consultar Requisição Fleet em Negociação',
                    'can' => 'consultar_requisicao_fleet_negociacao'
                ],
                [
                    'text' => 'Registrar Voucher',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Registrar Voucher',
                    'can' => 'registrar_voucher'
                ],
                [
                    'text' => 'Solicitar Retirada de Veículo Locadora',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Solicitar Retirada de Veículo Locadora',
                    'can' => 'solicitar_retirada_veiculo_locadora'
                ],
                [
                    'text' => 'Liberar Uso de Veículo ao Condutor',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Liberar Uso de Veículo ao Condutor',
                    'can' => 'liberar_usu_veiculo_condutor'
                ],
                [
                    'text' => 'Solicitar Devolução de Veículo para Locadora',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Solicitar Devolução de Veículo para Locadora',
                    'can' => 'sol_dev_veiculo_locadora'
                ],
                [
                    'text' => 'Informar Reparos a Veículos Devolvidos',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Informar Reparos a Veículos Devolvidos',
                    'can' => 'informar_reparos_veiculos_devolvidos'
                ],
            ]
        ],
        [
            'text' => 'Gestão de Veículos',
            'icon' => '',
            'label' => '',
            'submenu' => [
                [
                    'text' => 'Cadastrar Veículo Próprio',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Cadastrar Veículo Próprio',
                    'can' => 'cadastrar_veiculo_proprio'
                ],
                [
                    'text' => 'Cadastrar Despesas do Veículo',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Cadastrar Despesas do Veículo',
                    'can' => 'cadastrar_despesas_veiculo'
                ],
                [
                    'text' => 'Registrar Multas',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Registrar Multas',
                    'can' => 'registrar_multas'
                ],
                [
                    'text' => 'Registrar Manutenção Preventiva',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Registrar Manutenção Preventiva',
                    'can' => 'registrar_manutencao_preventiva'
                ],
                [
                    'text' => 'Consultar Cartão de Combustível',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Consultar Cartão de Combustível',
                    'can' => 'consultar_cartao_combustivel'
                ],
                [
                    'text' => 'Consultar Dados Gerais do Veículo',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Consultar Dados Gerais do Veículo',
                    'can' => 'consultar_dados_gerais_veiculo'
                ],
                [
                    'text' => 'Consultar Fatura do Sem Parar',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Consultar Fatura do Sem Parar',
                    'can' => 'consultar_fatura_sem_parar'
                ],
                [
                    'text' => 'Registrar Dados de Acidente',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Registrar Dados de Acidente',
                    'can' => 'registrar_dados_acidente'
                ],
                [
                    'text' => 'Informar Tranferência de Centro de Custo',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Informar Tranferência de Centro de Custo',
                    'can' => 'informar_transferencia_centro_custo'
                ],
            ]
        ],
        [
            'text' => 'Relatórios',
            'icon' => '',
            'label' => '',
            'submenu' => [
                [
                    'text' => 'Rel. Lista de Veículos Alugados',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Lista de Veículos Alugados',
                    'can' => 'rel_lista_veiculos_alugados_proprios'
                ],
                [
                    'text' => 'Rel.Consumo Km Percorrido',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel.Consumo Km Percorrido',
                    'can' => 'rel_cons_km_percorrido'
                ],
                [
                    'text' => 'Rel. Lista de Multas',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Lista de Multas',
                    'can' => 'rel_lista_multas'
                ],
                [
                    'text' => 'Rel. Consulta de Acidentes',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Consulta de Acidentes',
                    'can' => 'rel_consulta_acidentes'
                ],
                [
                    'text' => 'Rel. Consulta de Despesas de Veículo',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Consulta de Despesas de Veículo',
                    'can' => 'rel_consulta_despesas_veiculos'
                ],
                [
                    'text' => 'Rel. Consulta de Condutores de Veículos',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Consulta de Condutores de Veículos',
                    'can' => 'rel_consulta_condutores_veiculos'
                ],
                [
                    'text' => 'Rel. Consulta de Abastecimento',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Consulta de Abastecimento',
                    'can' => 'rel_consulta_abastecimento'
                ],
                [
                    'text' => 'Rel. Consulta de Consumo de Veículo',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Consulta de Consumo de Veículo',
                    'can' => 'rel_consulta_consumo_veiculo'
                ],
                [
                    'text' => 'Rel. Consulta Resumo de Motoristas',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Consulta Resumo de Motoristas',
                    'can' => 'rel_consulta_resumo_motorista'
                ],
                [
                    'text' => 'Rel. Consulta de Despesas de Veículos Pós Entrega',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Consulta de Despesas de Veículos Pós Entrega',
                    'can' => 'rel_consulta_desp_veiculo_pos_entrega'
                ],
                [
                    'text' => 'Rel. Listagem de Motoristas',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Listagem de Motoristas',
                    'can' => 'rel_listagem_motoristas'
                ],
                [
                    'text' => 'Rel. Consulta de Manutenção Preventiva',
                    'url' => '',
                    'icon' => '',
                    'label' => 'Rel. Consulta de Manutenção Preventiva',
                    'can' => 'rel_consulta_manutencao_preventiva'
                ],
            ]
        ],
    ]
];