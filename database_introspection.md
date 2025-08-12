# Documentação do Banco de Dados - CutPlan

Gerado em: 2025-08-12 14:16:00

## Tabela: `almoxarifados`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| nome | varchar | - | - |
| localizacao | varchar | - | - |
| padrao | tinyint | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `anexos`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| attachable_type | varchar | - | - |
| attachable_id | bigint | - | - |
| arquivo_url | varchar | - | - |
| nome_arquivo | varchar | - | - |
| mime_type | varchar | - | - |
| tamanho_bytes | bigint | - | - |
| uploaded_by_user_id | bigint | - | - |
| created_at | timestamp | - | - |

## Tabela: `apontamentos_tempo`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| projeto_id | bigint | - | - |
| etapa_id | bigint | - | - |
| user_id | bigint | - | - |
| atividade | varchar | - | - |
| inicio | datetime | - | - |
| fim | datetime | - | - |
| duracao_min | int | - | - |
| notas | text | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `aprovacoes`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| projeto_id | bigint | - | - |
| contexto | varchar | - | - |
| solicitado_por | bigint | - | - |
| aprovador_user_id | bigint | - | - |
| aprovador_contato_id | bigint | - | - |
| status | varchar | - | - |
| aprovado_em | datetime | - | - |
| observacoes | text | - | - |
| arquivo_url | varchar | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `ativos_manutencao`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| codigo | varchar | - | - |
| nome | varchar | - | - |
| tipo | varchar | - | - |
| numero_serie | varchar | - | - |
| data_compra | date | - | - |
| fornecedor_id | bigint | - | - |
| localizacao | varchar | - | - |
| status | varchar | - | - |
| observacoes | text | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `cache`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| key | varchar | - | - |
| value | mediumtext | - | - |
| expiration | int | - | - |

## Tabela: `cache_locks`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| key | varchar | - | - |
| owner | varchar | - | - |
| expiration | int | - | - |

## Tabela: `categories`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| name | varchar | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `clientes`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| nome | varchar | - | - |
| documento | varchar | - | - |
| email | varchar | - | - |
| telefone | varchar | - | - |
| endereco_id | bigint | - | - |
| observacoes | text | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |
| user_id | bigint | - | - |

## Tabela: `contatos`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| nome | varchar | - | - |
| email | varchar | - | - |
| telefone | varchar | - | - |
| cargo | varchar | - | - |
| cliente_id | bigint | - | - |
| fornecedor_id | bigint | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `contratos`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| projeto_id | bigint | - | - |
| status | varchar | - | - |
| assinado_em | date | - | - |
| prazo_dias | int | - | - |
| descricao | longtext | - | - |
| valor_total | decimal | - | - |
| arquivo_url | varchar | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `embarques_logistica`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| projeto_id | bigint | - | - |
| tipo | varchar | - | - |
| destino_tipo | varchar | - | - |
| endereco_id | bigint | - | - |
| agendado_em | datetime | - | - |
| status | varchar | - | - |
| transportadora | varchar | - | - |
| documento_numero | varchar | - | - |
| observacoes | text | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `enderecos`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| linha1 | varchar | - | - |
| linha2 | varchar | - | - |
| cidade | varchar | - | - |
| estado | varchar | - | - |
| cep | varchar | - | - |
| pais | varchar | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `equipes`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| nome | varchar | - | - |
| tipo | varchar | - | - |
| lider_user_id | bigint | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `estoques_materiais`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| material_id | bigint | - | - |
| almoxarifado_id | bigint | - | - |
| saldo | decimal | - | - |
| reservado | decimal | - | - |

## Tabela: `etapas_producao`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| codigo | varchar | - | - |
| nome | varchar | - | - |
| sequencia | int | - | - |
| ativa | tinyint | - | - |

## Tabela: `execucoes_etapa_projeto`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| projeto_id | bigint | - | - |
| etapa_id | bigint | - | - |
| status | varchar | - | - |
| inicio_previsto | datetime | - | - |
| fim_previsto | datetime | - | - |
| inicio_real | datetime | - | - |
| fim_real | datetime | - | - |
| equipe_id | bigint | - | - |
| notas | text | - | - |
| concluido_por | bigint | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `failed_jobs`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| uuid | varchar | - | - |
| connection | text | - | - |
| queue | text | - | - |
| payload | longtext | - | - |
| exception | longtext | - | - |
| failed_at | timestamp | - | - |

## Tabela: `fornecedores`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| nome | varchar | - | - |
| documento | varchar | - | - |
| email | varchar | - | - |
| telefone | varchar | - | - |
| endereco_id | bigint | - | - |
| user_id | bigint | - | - |
| observacoes | text | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `itens_embarque`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| embarque_id | bigint | - | - |
| material_id | bigint | - | - |
| descricao | varchar | - | - |
| quantidade | decimal | - | - |
| unidade_id | bigint | - | - |

## Tabela: `itens_orcamento`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| orcamento_id | bigint | - | - |
| material_id | bigint | - | - |
| descricao | varchar | - | - |
| quantidade | decimal | - | - |
| unidade_id | bigint | - | - |
| preco_unitario | decimal | - | - |
| total | decimal | - | - |
| eh_servico | tinyint | - | - |

## Tabela: `itens_ordem_compra`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| ordem_compra_id | bigint | - | - |
| material_id | bigint | - | - |
| descricao | varchar | - | - |
| quantidade | decimal | - | - |
| unidade_id | bigint | - | - |
| preco_unitario | decimal | - | - |
| total | decimal | - | - |
| recebido_qtd | decimal | - | - |

## Tabela: `itens_recebimento`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| recebimento_id | bigint | - | - |
| material_id | bigint | - | - |
| quantidade | decimal | - | - |
| custo_unitario | decimal | - | - |

## Tabela: `job_batches`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | varchar | - | - |
| name | varchar | - | - |
| total_jobs | int | - | - |
| pending_jobs | int | - | - |
| failed_jobs | int | - | - |
| failed_job_ids | longtext | - | - |
| options | mediumtext | - | - |
| cancelled_at | int | - | - |
| created_at | int | - | - |
| finished_at | int | - | - |

## Tabela: `jobs`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| queue | varchar | - | - |
| payload | longtext | - | - |
| attempts | tinyint | - | - |
| reserved_at | int | - | - |
| available_at | int | - | - |
| created_at | int | - | - |

## Tabela: `logs_manutencao`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| ativo_id | bigint | - | - |
| ordem_manutencao_id | bigint | - | - |
| realizado_em | datetime | - | - |
| descricao | text | - | - |
| custo | decimal | - | - |
| created_at | timestamp | - | - |

## Tabela: `materiais`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| sku | varchar | - | - |
| nome | varchar | - | - |
| tipo_id | bigint | - | - |
| unidade_id | bigint | - | - |
| fornecedor_padrao_id | bigint | - | - |
| preco_custo | decimal | - | - |
| estoque_minimo | decimal | - | - |
| controla_estoque | tinyint | - | - |
| ativo | tinyint | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `materiais_projeto`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| projeto_id | bigint | - | - |
| material_id | bigint | - | - |
| quantidade_requerida | decimal | - | - |
| unidade_id | bigint | - | - |
| quantidade_reservada | decimal | - | - |
| quantidade_baixada | decimal | - | - |
| observacoes | text | - | - |

## Tabela: `membros_equipe`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| equipe_id | bigint | - | - |
| user_id | bigint | - | - |
| papel | varchar | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `metricas_producao`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| codigo | varchar | - | - |
| nome | varchar | - | - |
| unidade_id | bigint | - | - |
| descricao | varchar | - | - |

## Tabela: `migrations`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | int | - | - |
| migration | varchar | - | - |
| batch | int | - | - |

## Tabela: `movimentacoes_estoque`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| material_id | bigint | - | - |
| almoxarifado_id | bigint | - | - |
| projeto_id | bigint | - | - |
| tipo | varchar | - | - |
| origem | varchar | - | - |
| quantidade | decimal | - | - |
| custo_unitario | decimal | - | - |
| total_custo | decimal | - | - |
| referencia_tipo | varchar | - | - |
| referencia_id | bigint | - | - |
| observacoes | text | - | - |
| criado_por | bigint | - | - |
| created_at | timestamp | - | - |

## Tabela: `oportunidades`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| cliente_id | bigint | - | - |
| arquiteto_contato_id | bigint | - | - |
| vendedor_user_id | bigint | - | - |
| titulo | varchar | - | - |
| descricao | text | - | - |
| origem | varchar | - | - |
| stage | varchar | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `orcamentos`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| oportunidade_id | bigint | - | - |
| versao | int | - | - |
| status | varchar | - | - |
| moeda | varchar | - | - |
| subtotal | decimal | - | - |
| desconto | decimal | - | - |
| impostos | decimal | - | - |
| total | decimal | - | - |
| validade | date | - | - |
| criado_por | bigint | - | - |
| observacoes | text | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `ordens_compra`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| fornecedor_id | bigint | - | - |
| projeto_id | bigint | - | - |
| codigo | varchar | - | - |
| status | varchar | - | - |
| data_pedido | date | - | - |
| data_prevista | date | - | - |
| moeda | varchar | - | - |
| subtotal | decimal | - | - |
| desconto | decimal | - | - |
| impostos | decimal | - | - |
| total | decimal | - | - |
| observacoes | text | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `ordens_manutencao`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| ativo_id | bigint | - | - |
| tipo | varchar | - | - |
| status | varchar | - | - |
| prioridade | varchar | - | - |
| aberto_em | datetime | - | - |
| fechado_em | datetime | - | - |
| descricao | text | - | - |
| equipe_id | bigint | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `ordens_servico`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| projeto_id | bigint | - | - |
| codigo | varchar | - | - |
| descricao | text | - | - |
| status | varchar | - | - |
| criado_por_user_id | bigint | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `password_reset_tokens`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| email | varchar | - | - |
| token | varchar | - | - |
| created_at | timestamp | - | - |

## Tabela: `pendencias`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| projeto_id | bigint | - | - |
| titulo | varchar | - | - |
| descricao | text | - | - |
| severidade | varchar | - | - |
| status | varchar | - | - |
| reportado_por_user_id | bigint | - | - |
| responsavel_user_id | bigint | - | - |
| prazo | date | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `projetos`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| codigo | varchar | - | - |
| oportunidade_id | bigint | - | - |
| cliente_id | bigint | - | - |
| nome | varchar | - | - |
| status | varchar | - | - |
| data_inicio | date | - | - |
| data_entrega_prevista | date | - | - |
| data_entrega_real | date | - | - |
| gerente_user_id | bigint | - | - |
| endereco_instalacao_id | bigint | - | - |
| observacoes | text | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |
| deleted_at | timestamp | - | - |

## Tabela: `recebimentos`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| ordem_compra_id | bigint | - | - |
| fornecedor_id | bigint | - | - |
| almoxarifado_id | bigint | - | - |
| recebido_em | datetime | - | - |
| recebido_por | bigint | - | - |
| observacoes | text | - | - |
| created_at | timestamp | - | - |

## Tabela: `sessions`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | varchar | - | - |
| user_id | bigint | - | - |
| ip_address | varchar | - | - |
| user_agent | text | - | - |
| payload | longtext | - | - |
| last_activity | int | - | - |

## Tabela: `tipos_materiais`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| nome | varchar | - | - |

## Tabela: `unidades`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| codigo | varchar | - | - |
| nome | varchar | - | - |
| precisao | tinyint | - | - |

## Tabela: `users`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| name | varchar | - | - |
| email | varchar | - | - |
| email_verified_at | timestamp | - | - |
| password | varchar | - | - |
| remember_token | varchar | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

## Tabela: `valores_metrica_etapa`

| Coluna | Tipo | Nullable | Default |
|--------|------|----------|----------|
| id | bigint | - | - |
| execucao_etapa_id | bigint | - | - |
| metrica_id | bigint | - | - |
| valor_num | decimal | - | - |
| valor_texto | varchar | - | - |
| created_at | timestamp | - | - |
| updated_at | timestamp | - | - |

