# Documentação do Banco de Dados - CutPlan

**Data da geração:** 12/08/2025 14:53:22
**Banco de dados:** cutplan
**Total de tabelas:** 46

## 📊 Resumo do Sistema

### Principais Módulos do Sistema

#### Gestão de Clientes
- `clientes`
- `enderecos`
- `contatos`

#### Gestão de Fornecedores
- `fornecedores`
- `contatos`

#### Gestão de Materiais
- `materiais`
- `tipos_materiais`
- `unidades`

#### Gestão de Projetos
- `projetos`
- `etapas_producao`
- `execucoes_etapa_projeto`
- `materiais_projeto`

#### Gestão de Estoque
- `almoxarifados`
- `estoques_materiais`
- `movimentacoes_estoque`

#### Compras e Vendas
- `orcamentos`
- `itens_orcamento`
- `ordens_compra`
- `itens_ordem_compra`
- `recebimentos`
- `itens_recebimento`

#### Logística
- `embarques_logistica`
- `itens_embarque`

#### Manutenção
- `ativos_manutencao`
- `ordens_manutencao`
- `logs_manutencao`

#### Controle de Produção
- `ordens_servico`
- `apontamentos_tempo`
- `metricas_producao`
- `valores_metrica_etapa`

#### Recursos Humanos
- `equipes`
- `membros_equipe`

#### Aprovações e Workflow
- `aprovacoes`
- `pendencias`

#### Sistema
- `users`
- `anexos`
- `contratos`
- `oportunidades`

#### Laravel Framework
- `cache`
- `cache_locks`
- `failed_jobs`
- `jobs`
- `job_batches`
- `migrations`
- `password_reset_tokens`
- `sessions`

### 🔗 Principais Relacionamentos

#### Tabela: `apontamentos_tempo`
- `etapa_id` → `etapas_producao.id`
- `projeto_id` → `projetos.id`

#### Tabela: `aprovacoes`
- `aprovador_contato_id` → `contatos.id`
- `projeto_id` → `projetos.id`

#### Tabela: `ativos_manutencao`
- `fornecedor_id` → `fornecedores.id`

#### Tabela: `clientes`
- `endereco_id` → `enderecos.id`
- `user_id` → `users.id`

#### Tabela: `contatos`
- `cliente_id` → `clientes.id`
- `fornecedor_id` → `fornecedores.id`

#### Tabela: `contratos`
- `projeto_id` → `projetos.id`

#### Tabela: `embarques_logistica`
- `endereco_id` → `enderecos.id`
- `projeto_id` → `projetos.id`

#### Tabela: `estoques_materiais`
- `almoxarifado_id` → `almoxarifados.id`
- `material_id` → `materiais.id`

#### Tabela: `execucoes_etapa_projeto`
- `equipe_id` → `equipes.id`
- `etapa_id` → `etapas_producao.id`
- `projeto_id` → `projetos.id`

#### Tabela: `fornecedores`
- `endereco_id` → `enderecos.id`
- `user_id` → `users.id`

#### Tabela: `itens_embarque`
- `embarque_id` → `embarques_logistica.id`
- `material_id` → `materiais.id`
- `unidade_id` → `unidades.id`

#### Tabela: `itens_orcamento`
- `material_id` → `materiais.id`
- `orcamento_id` → `orcamentos.id`
- `unidade_id` → `unidades.id`

#### Tabela: `itens_ordem_compra`
- `material_id` → `materiais.id`
- `ordem_compra_id` → `ordens_compra.id`
- `unidade_id` → `unidades.id`

#### Tabela: `itens_recebimento`
- `material_id` → `materiais.id`
- `recebimento_id` → `recebimentos.id`

#### Tabela: `logs_manutencao`
- `ativo_id` → `ativos_manutencao.id`
- `ordem_manutencao_id` → `ordens_manutencao.id`

#### Tabela: `materiais`
- `fornecedor_padrao_id` → `fornecedores.id`
- `tipo_id` → `tipos_materiais.id`
- `unidade_id` → `unidades.id`

#### Tabela: `materiais_projeto`
- `material_id` → `materiais.id`
- `projeto_id` → `projetos.id`
- `unidade_id` → `unidades.id`

#### Tabela: `membros_equipe`
- `equipe_id` → `equipes.id`

#### Tabela: `metricas_producao`
- `unidade_id` → `unidades.id`

#### Tabela: `movimentacoes_estoque`
- `almoxarifado_id` → `almoxarifados.id`
- `material_id` → `materiais.id`
- `projeto_id` → `projetos.id`

#### Tabela: `oportunidades`
- `arquiteto_contato_id` → `contatos.id`
- `cliente_id` → `clientes.id`

#### Tabela: `orcamentos`
- `oportunidade_id` → `oportunidades.id`

#### Tabela: `ordens_compra`
- `fornecedor_id` → `fornecedores.id`
- `projeto_id` → `projetos.id`

#### Tabela: `ordens_manutencao`
- `ativo_id` → `ativos_manutencao.id`
- `equipe_id` → `equipes.id`

#### Tabela: `ordens_servico`
- `projeto_id` → `projetos.id`

#### Tabela: `pendencias`
- `projeto_id` → `projetos.id`

#### Tabela: `projetos`
- `cliente_id` → `clientes.id`
- `endereco_instalacao_id` → `enderecos.id`
- `oportunidade_id` → `oportunidades.id`

#### Tabela: `recebimentos`
- `almoxarifado_id` → `almoxarifados.id`
- `fornecedor_id` → `fornecedores.id`
- `ordem_compra_id` → `ordens_compra.id`

#### Tabela: `valores_metrica_etapa`
- `execucao_etapa_id` → `execucoes_etapa_projeto.id`
- `metrica_id` → `metricas_producao.id`

### 📈 Estatísticas das Tabelas

| Tabela | Total de Colunas | Chaves Estrangeiras | Índices |
|--------|------------------|---------------------|----------|
| `almoxarifados` | 6 | 0 | 2 |
| `anexos` | 9 | 0 | 3 |
| `apontamentos_tempo` | 11 | 2 | 3 |
| `aprovacoes` | 12 | 2 | 3 |
| `ativos_manutencao` | 13 | 1 | 3 |
| `cache` | 3 | 0 | 1 |
| `cache_locks` | 3 | 0 | 1 |
| `categories` | 4 | 0 | 1 |
| `clientes` | 11 | 2 | 3 |
| `contatos` | 10 | 2 | 3 |
| `contratos` | 10 | 1 | 2 |
| `embarques_logistica` | 12 | 2 | 3 |
| `enderecos` | 10 | 0 | 1 |
| `equipes` | 7 | 0 | 1 |
| `estoques_materiais` | 5 | 2 | 4 |
| `etapas_producao` | 5 | 0 | 2 |
| `execucoes_etapa_projeto` | 13 | 3 | 5 |
| `failed_jobs` | 7 | 0 | 2 |
| `fornecedores` | 11 | 2 | 3 |
| `itens_embarque` | 6 | 3 | 4 |
| `itens_orcamento` | 9 | 3 | 4 |
| `itens_ordem_compra` | 9 | 3 | 4 |
| `itens_recebimento` | 5 | 2 | 3 |
| `job_batches` | 10 | 0 | 1 |
| `jobs` | 7 | 0 | 2 |
| `logs_manutencao` | 7 | 2 | 3 |
| `materiais` | 13 | 3 | 5 |
| `materiais_projeto` | 8 | 3 | 5 |
| `membros_equipe` | 7 | 1 | 3 |
| `metricas_producao` | 5 | 1 | 3 |
| `migrations` | 3 | 0 | 1 |
| `movimentacoes_estoque` | 14 | 3 | 4 |
| `oportunidades` | 11 | 2 | 3 |
| `orcamentos` | 15 | 1 | 2 |
| `ordens_compra` | 16 | 2 | 4 |
| `ordens_manutencao` | 11 | 2 | 3 |
| `ordens_servico` | 8 | 1 | 3 |
| `password_reset_tokens` | 3 | 0 | 1 |
| `pendencias` | 12 | 1 | 2 |
| `projetos` | 15 | 3 | 5 |
| `recebimentos` | 8 | 3 | 4 |
| `sessions` | 6 | 0 | 3 |
| `tipos_materiais` | 2 | 0 | 2 |
| `unidades` | 4 | 0 | 2 |
| `users` | 8 | 0 | 2 |
| `valores_metrica_etapa` | 7 | 2 | 4 |


## Tabela: `almoxarifados`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `nome` | varchar(100) | NO | UNI |  |  |
| `localizacao` | varchar(150) | YES |  |  |  |
| `padrao` | tinyint | NO |  | 0 |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `nome` | `nome` | Sim | BTREE |

---

## Tabela: `anexos`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `attachable_type` | varchar(100) | NO | MUL |  |  |
| `attachable_id` | bigint unsigned | NO |  |  |  |
| `arquivo_url` | varchar(255) | NO |  |  |  |
| `nome_arquivo` | varchar(150) | YES |  |  |  |
| `mime_type` | varchar(100) | YES |  |  |  |
| `tamanho_bytes` | bigint unsigned | YES |  |  |  |
| `uploaded_by_user_id` | bigint unsigned | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `idx_attachable` | `attachable_type` | Não | BTREE |
| `idx_attachable` | `attachable_id` | Não | BTREE |

---

## Tabela: `apontamentos_tempo`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `projeto_id` | bigint unsigned | NO | MUL |  |  |
| `etapa_id` | bigint unsigned | YES | MUL |  |  |
| `user_id` | bigint unsigned | YES |  |  |  |
| `atividade` | varchar(50) | YES |  |  |  |
| `inicio` | datetime | NO |  |  |  |
| `fim` | datetime | YES |  |  |  |
| `duracao_min` | int | YES |  |  |  |
| `notas` | text | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `etapa_id` | `etapas_producao` | `id` | fk_apo_etapa |
| `projeto_id` | `projetos` | `id` | fk_apo_proj |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_apo_proj` | `projeto_id` | Não | BTREE |
| `fk_apo_etapa` | `etapa_id` | Não | BTREE |

---

## Tabela: `aprovacoes`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `projeto_id` | bigint unsigned | NO | MUL |  |  |
| `contexto` | varchar(32) | NO |  |  |  |
| `solicitado_por` | bigint unsigned | YES |  |  |  |
| `aprovador_user_id` | bigint unsigned | YES |  |  |  |
| `aprovador_contato_id` | bigint unsigned | YES | MUL |  |  |
| `status` | varchar(16) | NO |  | pending |  |
| `aprovado_em` | datetime | YES |  |  |  |
| `observacoes` | text | YES |  |  |  |
| `arquivo_url` | varchar(255) | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `aprovador_contato_id` | `contatos` | `id` | fk_aprov_contato |
| `projeto_id` | `projetos` | `id` | fk_aprov_proj |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_aprov_proj` | `projeto_id` | Não | BTREE |
| `fk_aprov_contato` | `aprovador_contato_id` | Não | BTREE |

---

## Tabela: `ativos_manutencao`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `codigo` | varchar(50) | YES | UNI |  |  |
| `nome` | varchar(150) | NO |  |  |  |
| `tipo` | varchar(20) | NO |  |  |  |
| `numero_serie` | varchar(100) | YES |  |  |  |
| `data_compra` | date | YES |  |  |  |
| `fornecedor_id` | bigint unsigned | YES | MUL |  |  |
| `localizacao` | varchar(150) | YES |  |  |  |
| `status` | varchar(20) | NO |  | ativo |  |
| `observacoes` | text | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `fornecedor_id` | `fornecedores` | `id` | fk_am_fornec |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `codigo` | `codigo` | Sim | BTREE |
| `fk_am_fornec` | `fornecedor_id` | Não | BTREE |

---

## Tabela: `cache`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `key` | varchar(255) | NO | PRI |  |  |
| `value` | mediumtext | NO |  |  |  |
| `expiration` | int | NO |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `key` | Sim | BTREE |

---

## Tabela: `cache_locks`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `key` | varchar(255) | NO | PRI |  |  |
| `owner` | varchar(255) | NO |  |  |  |
| `expiration` | int | NO |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `key` | Sim | BTREE |

---

## Tabela: `categories`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `name` | varchar(255) | NO |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |

---

## Tabela: `clientes`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `nome` | varchar(150) | NO |  |  |  |
| `documento` | varchar(32) | YES |  |  |  |
| `email` | varchar(150) | YES |  |  |  |
| `telefone` | varchar(50) | YES |  |  |  |
| `endereco_id` | bigint unsigned | YES | MUL |  |  |
| `observacoes` | text | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |
| `user_id` | bigint unsigned | YES | MUL |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `endereco_id` | `enderecos` | `id` | fk_clientes_endereco |
| `user_id` | `users` | `id` | fk_clientes_user |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_clientes_endereco` | `endereco_id` | Não | BTREE |
| `fk_clientes_user` | `user_id` | Não | BTREE |

---

## Tabela: `contatos`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `nome` | varchar(150) | NO |  |  |  |
| `email` | varchar(150) | YES |  |  |  |
| `telefone` | varchar(50) | YES |  |  |  |
| `cargo` | varchar(100) | YES |  |  |  |
| `cliente_id` | bigint unsigned | YES | MUL |  |  |
| `fornecedor_id` | bigint unsigned | YES | MUL |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `cliente_id` | `clientes` | `id` | fk_contatos_cliente |
| `fornecedor_id` | `fornecedores` | `id` | fk_contatos_fornecedor |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_contatos_cliente` | `cliente_id` | Não | BTREE |
| `fk_contatos_fornecedor` | `fornecedor_id` | Não | BTREE |

---

## Tabela: `contratos`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `projeto_id` | bigint unsigned | NO | UNI |  |  |
| `status` | varchar(16) | NO |  | draft |  |
| `assinado_em` | date | YES |  |  |  |
| `prazo_dias` | int | YES |  |  |  |
| `descricao` | longtext | YES |  |  |  |
| `valor_total` | decimal(14,4) | YES |  |  |  |
| `arquivo_url` | varchar(255) | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `projeto_id` | `projetos` | `id` | fk_contrato_proj |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `projeto_id` | `projeto_id` | Sim | BTREE |

---

## Tabela: `embarques_logistica`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `projeto_id` | bigint unsigned | NO | MUL |  |  |
| `tipo` | varchar(16) | NO |  |  |  |
| `destino_tipo` | varchar(16) | NO |  |  |  |
| `endereco_id` | bigint unsigned | YES | MUL |  |  |
| `agendado_em` | datetime | YES |  |  |  |
| `status` | varchar(16) | NO |  | scheduled |  |
| `transportadora` | varchar(100) | YES |  |  |  |
| `documento_numero` | varchar(50) | YES |  |  |  |
| `observacoes` | text | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `endereco_id` | `enderecos` | `id` | fk_emb_end |
| `projeto_id` | `projetos` | `id` | fk_emb_proj |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_emb_proj` | `projeto_id` | Não | BTREE |
| `fk_emb_end` | `endereco_id` | Não | BTREE |

---

## Tabela: `enderecos`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `linha1` | varchar(150) | NO |  |  |  |
| `linha2` | varchar(150) | YES |  |  |  |
| `cidade` | varchar(100) | NO |  |  |  |
| `estado` | varchar(100) | NO |  |  |  |
| `cep` | varchar(20) | YES |  |  |  |
| `pais` | varchar(100) | NO |  | Brasil |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |

---

## Tabela: `equipes`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `nome` | varchar(100) | NO |  |  |  |
| `tipo` | varchar(20) | NO |  |  |  |
| `lider_user_id` | bigint unsigned | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |

---

## Tabela: `estoques_materiais`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `material_id` | bigint unsigned | NO | MUL |  |  |
| `almoxarifado_id` | bigint unsigned | NO | MUL |  |  |
| `saldo` | decimal(18,4) | NO |  | 0.0000 |  |
| `reservado` | decimal(18,4) | NO |  | 0.0000 |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `almoxarifado_id` | `almoxarifados` | `id` | fk_estoques_almox |
| `material_id` | `materiais` | `id` | fk_estoques_material |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `uq_mat_alm` | `material_id` | Sim | BTREE |
| `uq_mat_alm` | `almoxarifado_id` | Sim | BTREE |
| `fk_estoques_almox` | `almoxarifado_id` | Não | BTREE |

---

## Tabela: `etapas_producao`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `codigo` | varchar(50) | NO | UNI |  |  |
| `nome` | varchar(100) | NO |  |  |  |
| `sequencia` | int unsigned | NO |  |  |  |
| `ativa` | tinyint(1) | NO |  | 1 |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `codigo` | `codigo` | Sim | BTREE |

---

## Tabela: `execucoes_etapa_projeto`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `projeto_id` | bigint unsigned | NO | MUL |  |  |
| `etapa_id` | bigint unsigned | NO | MUL |  |  |
| `status` | varchar(16) | NO |  | pending |  |
| `inicio_previsto` | datetime | YES |  |  |  |
| `fim_previsto` | datetime | YES |  |  |  |
| `inicio_real` | datetime | YES |  |  |  |
| `fim_real` | datetime | YES |  |  |  |
| `equipe_id` | bigint unsigned | YES | MUL |  |  |
| `notas` | text | YES |  |  |  |
| `concluido_por` | bigint unsigned | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `equipe_id` | `equipes` | `id` | fk_eep_equipe |
| `etapa_id` | `etapas_producao` | `id` | fk_eep_etapa |
| `projeto_id` | `projetos` | `id` | fk_eep_proj |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `uq_proj_etapa` | `projeto_id` | Sim | BTREE |
| `uq_proj_etapa` | `etapa_id` | Sim | BTREE |
| `fk_eep_etapa` | `etapa_id` | Não | BTREE |
| `fk_eep_equipe` | `equipe_id` | Não | BTREE |

---

## Tabela: `failed_jobs`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `uuid` | varchar(255) | NO | UNI |  |  |
| `connection` | text | NO |  |  |  |
| `queue` | text | NO |  |  |  |
| `payload` | longtext | NO |  |  |  |
| `exception` | longtext | NO |  |  |  |
| `failed_at` | timestamp | NO |  | CURRENT_TIMESTAMP | DEFAULT_GENERATED |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `failed_jobs_uuid_unique` | `uuid` | Sim | BTREE |

---

## Tabela: `fornecedores`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `nome` | varchar(150) | NO |  |  |  |
| `documento` | varchar(32) | YES |  |  |  |
| `email` | varchar(150) | YES |  |  |  |
| `telefone` | varchar(50) | YES |  |  |  |
| `endereco_id` | bigint unsigned | YES | MUL |  |  |
| `user_id` | bigint unsigned | YES | MUL |  |  |
| `observacoes` | text | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `endereco_id` | `enderecos` | `id` | fk_fornecedores_endereco |
| `user_id` | `users` | `id` | fk_fornecedores_user |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_fornecedores_endereco` | `endereco_id` | Não | BTREE |
| `fk_fornecedores_user` | `user_id` | Não | BTREE |

---

## Tabela: `itens_embarque`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `embarque_id` | bigint unsigned | NO | MUL |  |  |
| `material_id` | bigint unsigned | YES | MUL |  |  |
| `descricao` | varchar(255) | NO |  |  |  |
| `quantidade` | decimal(18,4) | NO |  |  |  |
| `unidade_id` | bigint unsigned | YES | MUL |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `embarque_id` | `embarques_logistica` | `id` | fk_ie_emb |
| `material_id` | `materiais` | `id` | fk_ie_mat |
| `unidade_id` | `unidades` | `id` | fk_ie_unid |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_ie_emb` | `embarque_id` | Não | BTREE |
| `fk_ie_mat` | `material_id` | Não | BTREE |
| `fk_ie_unid` | `unidade_id` | Não | BTREE |

---

## Tabela: `itens_orcamento`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `orcamento_id` | bigint unsigned | NO | MUL |  |  |
| `material_id` | bigint unsigned | YES | MUL |  |  |
| `descricao` | varchar(255) | NO |  |  |  |
| `quantidade` | decimal(18,4) | NO |  |  |  |
| `unidade_id` | bigint unsigned | YES | MUL |  |  |
| `preco_unitario` | decimal(14,4) | NO |  |  |  |
| `total` | decimal(14,4) | NO |  |  |  |
| `eh_servico` | tinyint(1) | NO |  | 0 |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `material_id` | `materiais` | `id` | fk_item_orc_mat |
| `orcamento_id` | `orcamentos` | `id` | fk_item_orc_orc |
| `unidade_id` | `unidades` | `id` | fk_item_orc_unid |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_item_orc_orc` | `orcamento_id` | Não | BTREE |
| `fk_item_orc_mat` | `material_id` | Não | BTREE |
| `fk_item_orc_unid` | `unidade_id` | Não | BTREE |

---

## Tabela: `itens_ordem_compra`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `ordem_compra_id` | bigint unsigned | NO | MUL |  |  |
| `material_id` | bigint unsigned | YES | MUL |  |  |
| `descricao` | varchar(255) | NO |  |  |  |
| `quantidade` | decimal(18,4) | NO |  |  |  |
| `unidade_id` | bigint unsigned | YES | MUL |  |  |
| `preco_unitario` | decimal(14,4) | NO |  |  |  |
| `total` | decimal(14,4) | NO |  |  |  |
| `recebido_qtd` | decimal(18,4) | NO |  | 0.0000 |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `material_id` | `materiais` | `id` | fk_ioc_mat |
| `ordem_compra_id` | `ordens_compra` | `id` | fk_ioc_oc |
| `unidade_id` | `unidades` | `id` | fk_ioc_unid |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_ioc_oc` | `ordem_compra_id` | Não | BTREE |
| `fk_ioc_mat` | `material_id` | Não | BTREE |
| `fk_ioc_unid` | `unidade_id` | Não | BTREE |

---

## Tabela: `itens_recebimento`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `recebimento_id` | bigint unsigned | NO | MUL |  |  |
| `material_id` | bigint unsigned | NO | MUL |  |  |
| `quantidade` | decimal(18,4) | NO |  |  |  |
| `custo_unitario` | decimal(12,4) | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `material_id` | `materiais` | `id` | fk_ir_mat |
| `recebimento_id` | `recebimentos` | `id` | fk_ir_rec |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_ir_rec` | `recebimento_id` | Não | BTREE |
| `fk_ir_mat` | `material_id` | Não | BTREE |

---

## Tabela: `job_batches`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | varchar(255) | NO | PRI |  |  |
| `name` | varchar(255) | NO |  |  |  |
| `total_jobs` | int | NO |  |  |  |
| `pending_jobs` | int | NO |  |  |  |
| `failed_jobs` | int | NO |  |  |  |
| `failed_job_ids` | longtext | NO |  |  |  |
| `options` | mediumtext | YES |  |  |  |
| `cancelled_at` | int | YES |  |  |  |
| `created_at` | int | NO |  |  |  |
| `finished_at` | int | YES |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |

---

## Tabela: `jobs`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `queue` | varchar(255) | NO | MUL |  |  |
| `payload` | longtext | NO |  |  |  |
| `attempts` | tinyint unsigned | NO |  |  |  |
| `reserved_at` | int unsigned | YES |  |  |  |
| `available_at` | int unsigned | NO |  |  |  |
| `created_at` | int unsigned | NO |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `jobs_queue_index` | `queue` | Não | BTREE |

---

## Tabela: `logs_manutencao`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `ativo_id` | bigint unsigned | NO | MUL |  |  |
| `ordem_manutencao_id` | bigint unsigned | YES | MUL |  |  |
| `realizado_em` | datetime | NO |  |  |  |
| `descricao` | text | NO |  |  |  |
| `custo` | decimal(14,4) | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `ativo_id` | `ativos_manutencao` | `id` | fk_lm_ativo |
| `ordem_manutencao_id` | `ordens_manutencao` | `id` | fk_lm_om |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_lm_ativo` | `ativo_id` | Não | BTREE |
| `fk_lm_om` | `ordem_manutencao_id` | Não | BTREE |

---

## Tabela: `materiais`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `sku` | varchar(64) | NO | UNI |  |  |
| `nome` | varchar(150) | NO |  |  |  |
| `tipo_id` | bigint unsigned | NO | MUL |  |  |
| `unidade_id` | bigint unsigned | NO | MUL |  |  |
| `fornecedor_padrao_id` | bigint unsigned | YES | MUL |  |  |
| `preco_custo` | decimal(12,4) | YES |  |  |  |
| `estoque_minimo` | decimal(16,4) | YES |  |  |  |
| `controla_estoque` | tinyint | NO |  | 1 |  |
| `ativo` | tinyint | NO |  | 1 |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `fornecedor_padrao_id` | `fornecedores` | `id` | fk_materiais_fornecedor_padrao |
| `tipo_id` | `tipos_materiais` | `id` | fk_materiais_tipo |
| `unidade_id` | `unidades` | `id` | fk_materiais_unidade |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `sku` | `sku` | Sim | BTREE |
| `fk_materiais_tipo` | `tipo_id` | Não | BTREE |
| `fk_materiais_unidade` | `unidade_id` | Não | BTREE |
| `fk_materiais_fornecedor_padrao` | `fornecedor_padrao_id` | Não | BTREE |

---

## Tabela: `materiais_projeto`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `projeto_id` | bigint unsigned | NO | MUL |  |  |
| `material_id` | bigint unsigned | NO | MUL |  |  |
| `quantidade_requerida` | decimal(18,4) | NO |  |  |  |
| `unidade_id` | bigint unsigned | NO | MUL |  |  |
| `quantidade_reservada` | decimal(18,4) | NO |  | 0.0000 |  |
| `quantidade_baixada` | decimal(18,4) | NO |  | 0.0000 |  |
| `observacoes` | text | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `material_id` | `materiais` | `id` | fk_mp_mat |
| `projeto_id` | `projetos` | `id` | fk_mp_proj |
| `unidade_id` | `unidades` | `id` | fk_mp_unid |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `uq_proj_mat` | `projeto_id` | Sim | BTREE |
| `uq_proj_mat` | `material_id` | Sim | BTREE |
| `fk_mp_mat` | `material_id` | Não | BTREE |
| `fk_mp_unid` | `unidade_id` | Não | BTREE |

---

## Tabela: `membros_equipe`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `equipe_id` | bigint unsigned | NO | MUL |  |  |
| `user_id` | bigint unsigned | NO |  |  |  |
| `papel` | varchar(50) | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `equipe_id` | `equipes` | `id` | fk_me_equipe |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `uq_equipe_user` | `equipe_id` | Sim | BTREE |
| `uq_equipe_user` | `user_id` | Sim | BTREE |

---

## Tabela: `metricas_producao`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `codigo` | varchar(50) | NO | UNI |  |  |
| `nome` | varchar(100) | NO |  |  |  |
| `unidade_id` | bigint unsigned | YES | MUL |  |  |
| `descricao` | varchar(255) | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `unidade_id` | `unidades` | `id` | fk_met_unid |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `codigo` | `codigo` | Sim | BTREE |
| `fk_met_unid` | `unidade_id` | Não | BTREE |

---

## Tabela: `migrations`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | int unsigned | NO | PRI |  | auto_increment |
| `migration` | varchar(255) | NO |  |  |  |
| `batch` | int | NO |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |

---

## Tabela: `movimentacoes_estoque`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `material_id` | bigint unsigned | NO | MUL |  |  |
| `almoxarifado_id` | bigint unsigned | NO | MUL |  |  |
| `projeto_id` | bigint unsigned | YES | MUL |  |  |
| `tipo` | varchar(16) | NO |  |  |  |
| `origem` | varchar(32) | NO |  |  |  |
| `quantidade` | decimal(18,4) | NO |  |  |  |
| `custo_unitario` | decimal(12,4) | YES |  |  |  |
| `total_custo` | decimal(14,4) | YES |  |  |  |
| `referencia_tipo` | varchar(64) | YES |  |  |  |
| `referencia_id` | bigint unsigned | YES |  |  |  |
| `observacoes` | text | YES |  |  |  |
| `criado_por` | bigint unsigned | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `almoxarifado_id` | `almoxarifados` | `id` | fk_mov_alm |
| `material_id` | `materiais` | `id` | fk_mov_mat |
| `projeto_id` | `projetos` | `id` | fk_mov_proj |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_mov_mat` | `material_id` | Não | BTREE |
| `fk_mov_alm` | `almoxarifado_id` | Não | BTREE |
| `fk_mov_proj` | `projeto_id` | Não | BTREE |

---

## Tabela: `oportunidades`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `cliente_id` | bigint unsigned | NO | MUL |  |  |
| `arquiteto_contato_id` | bigint unsigned | YES | MUL |  |  |
| `vendedor_user_id` | bigint unsigned | YES |  |  |  |
| `titulo` | varchar(150) | NO |  |  |  |
| `descricao` | text | YES |  |  |  |
| `origem` | varchar(50) | YES |  |  |  |
| `stage` | varchar(20) | NO |  | lead |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `arquiteto_contato_id` | `contatos` | `id` | fk_opp_arquiteto |
| `cliente_id` | `clientes` | `id` | fk_opp_cliente |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_opp_cliente` | `cliente_id` | Não | BTREE |
| `fk_opp_arquiteto` | `arquiteto_contato_id` | Não | BTREE |

---

## Tabela: `orcamentos`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `oportunidade_id` | bigint unsigned | NO | MUL |  |  |
| `versao` | int unsigned | NO |  | 1 |  |
| `status` | varchar(16) | NO |  | draft |  |
| `moeda` | varchar(8) | NO |  | BRL |  |
| `subtotal` | decimal(14,4) | NO |  | 0.0000 |  |
| `desconto` | decimal(14,4) | NO |  | 0.0000 |  |
| `impostos` | decimal(14,4) | NO |  | 0.0000 |  |
| `total` | decimal(14,4) | NO |  | 0.0000 |  |
| `validade` | date | YES |  |  |  |
| `criado_por` | bigint unsigned | YES |  |  |  |
| `observacoes` | text | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `oportunidade_id` | `oportunidades` | `id` | fk_orc_opp |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_orc_opp` | `oportunidade_id` | Não | BTREE |

---

## Tabela: `ordens_compra`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `fornecedor_id` | bigint unsigned | NO | MUL |  |  |
| `projeto_id` | bigint unsigned | YES | MUL |  |  |
| `codigo` | varchar(50) | YES | UNI |  |  |
| `status` | varchar(16) | NO |  | draft |  |
| `data_pedido` | date | YES |  |  |  |
| `data_prevista` | date | YES |  |  |  |
| `moeda` | varchar(8) | NO |  | BRL |  |
| `subtotal` | decimal(14,4) | NO |  | 0.0000 |  |
| `desconto` | decimal(14,4) | NO |  | 0.0000 |  |
| `impostos` | decimal(14,4) | NO |  | 0.0000 |  |
| `total` | decimal(14,4) | NO |  | 0.0000 |  |
| `observacoes` | text | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `fornecedor_id` | `fornecedores` | `id` | fk_oc_fornec |
| `projeto_id` | `projetos` | `id` | fk_oc_proj |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `codigo` | `codigo` | Sim | BTREE |
| `fk_oc_fornec` | `fornecedor_id` | Não | BTREE |
| `fk_oc_proj` | `projeto_id` | Não | BTREE |

---

## Tabela: `ordens_manutencao`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `ativo_id` | bigint unsigned | NO | MUL |  |  |
| `tipo` | varchar(16) | NO |  |  |  |
| `status` | varchar(16) | NO |  | aberta |  |
| `prioridade` | varchar(16) | YES |  |  |  |
| `aberto_em` | datetime | NO |  |  |  |
| `fechado_em` | datetime | YES |  |  |  |
| `descricao` | text | YES |  |  |  |
| `equipe_id` | bigint unsigned | YES | MUL |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `ativo_id` | `ativos_manutencao` | `id` | fk_om_ativo |
| `equipe_id` | `equipes` | `id` | fk_om_equipe |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_om_ativo` | `ativo_id` | Não | BTREE |
| `fk_om_equipe` | `equipe_id` | Não | BTREE |

---

## Tabela: `ordens_servico`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `projeto_id` | bigint unsigned | NO | MUL |  |  |
| `codigo` | varchar(50) | YES | UNI |  |  |
| `descricao` | text | YES |  |  |  |
| `status` | varchar(16) | NO |  | aberta |  |
| `criado_por_user_id` | bigint unsigned | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `projeto_id` | `projetos` | `id` | fk_os_proj |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `codigo` | `codigo` | Sim | BTREE |
| `fk_os_proj` | `projeto_id` | Não | BTREE |

---

## Tabela: `password_reset_tokens`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `email` | varchar(255) | NO | PRI |  |  |
| `token` | varchar(255) | NO |  |  |  |
| `created_at` | timestamp | YES |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `email` | Sim | BTREE |

---

## Tabela: `pendencias`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `projeto_id` | bigint unsigned | NO | MUL |  |  |
| `titulo` | varchar(150) | NO |  |  |  |
| `descricao` | text | YES |  |  |  |
| `severidade` | varchar(16) | NO |  | media |  |
| `status` | varchar(16) | NO |  | aberta |  |
| `reportado_por_user_id` | bigint unsigned | YES |  |  |  |
| `responsavel_user_id` | bigint unsigned | YES |  |  |  |
| `prazo` | date | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `projeto_id` | `projetos` | `id` | fk_pend_proj |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_pend_proj` | `projeto_id` | Não | BTREE |

---

## Tabela: `projetos`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `codigo` | varchar(50) | YES | UNI |  |  |
| `oportunidade_id` | bigint unsigned | YES | MUL |  |  |
| `cliente_id` | bigint unsigned | NO | MUL |  |  |
| `nome` | varchar(150) | NO |  |  |  |
| `status` | varchar(20) | NO |  | em_planejamento |  |
| `data_inicio` | date | YES |  |  |  |
| `data_entrega_prevista` | date | YES |  |  |  |
| `data_entrega_real` | date | YES |  |  |  |
| `gerente_user_id` | bigint unsigned | YES |  |  |  |
| `endereco_instalacao_id` | bigint unsigned | YES | MUL |  |  |
| `observacoes` | text | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |
| `deleted_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `cliente_id` | `clientes` | `id` | fk_proj_cli |
| `endereco_instalacao_id` | `enderecos` | `id` | fk_proj_end_inst |
| `oportunidade_id` | `oportunidades` | `id` | fk_proj_opp |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `codigo` | `codigo` | Sim | BTREE |
| `fk_proj_opp` | `oportunidade_id` | Não | BTREE |
| `fk_proj_cli` | `cliente_id` | Não | BTREE |
| `fk_proj_end_inst` | `endereco_instalacao_id` | Não | BTREE |

---

## Tabela: `recebimentos`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `ordem_compra_id` | bigint unsigned | YES | MUL |  |  |
| `fornecedor_id` | bigint unsigned | NO | MUL |  |  |
| `almoxarifado_id` | bigint unsigned | NO | MUL |  |  |
| `recebido_em` | datetime | NO |  |  |  |
| `recebido_por` | bigint unsigned | YES |  |  |  |
| `observacoes` | text | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `almoxarifado_id` | `almoxarifados` | `id` | fk_rec_alm |
| `fornecedor_id` | `fornecedores` | `id` | fk_rec_fornec |
| `ordem_compra_id` | `ordens_compra` | `id` | fk_rec_oc |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `fk_rec_oc` | `ordem_compra_id` | Não | BTREE |
| `fk_rec_fornec` | `fornecedor_id` | Não | BTREE |
| `fk_rec_alm` | `almoxarifado_id` | Não | BTREE |

---

## Tabela: `sessions`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | varchar(255) | NO | PRI |  |  |
| `user_id` | bigint unsigned | YES | MUL |  |  |
| `ip_address` | varchar(45) | YES |  |  |  |
| `user_agent` | text | YES |  |  |  |
| `payload` | longtext | NO |  |  |  |
| `last_activity` | int | NO | MUL |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `sessions_user_id_index` | `user_id` | Não | BTREE |
| `sessions_last_activity_index` | `last_activity` | Não | BTREE |

---

## Tabela: `tipos_materiais`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `nome` | varchar(100) | NO | UNI |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `nome` | `nome` | Sim | BTREE |

---

## Tabela: `unidades`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `codigo` | varchar(16) | NO | UNI |  |  |
| `nome` | varchar(64) | NO |  |  |  |
| `precisao` | tinyint unsigned | NO |  | 3 |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `codigo` | `codigo` | Sim | BTREE |

---

## Tabela: `users`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `name` | varchar(255) | NO |  |  |  |
| `email` | varchar(255) | NO | UNI |  |  |
| `email_verified_at` | timestamp | YES |  |  |  |
| `password` | varchar(255) | NO |  |  |  |
| `remember_token` | varchar(100) | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `users_email_unique` | `email` | Sim | BTREE |

---

## Tabela: `valores_metrica_etapa`

### Colunas

| Coluna | Tipo | Null | Chave | Padrão | Extra |
|--------|------|------|-------|--------|-------|
| `id` | bigint unsigned | NO | PRI |  | auto_increment |
| `execucao_etapa_id` | bigint unsigned | NO | MUL |  |  |
| `metrica_id` | bigint unsigned | NO | MUL |  |  |
| `valor_num` | decimal(18,4) | YES |  |  |  |
| `valor_texto` | varchar(255) | YES |  |  |  |
| `created_at` | timestamp | YES |  |  |  |
| `updated_at` | timestamp | YES |  |  |  |

### Chaves Estrangeiras

| Coluna Local | Tabela Referenciada | Coluna Referenciada | Nome da Constraint |
|--------------|---------------------|---------------------|--------------------|
| `execucao_etapa_id` | `execucoes_etapa_projeto` | `id` | fk_vme_exec |
| `metrica_id` | `metricas_producao` | `id` | fk_vme_metrica |

### Índices

| Nome do Índice | Coluna | Único | Tipo |
|----------------|--------|-------|------|
| `PRIMARY` | `id` | Sim | BTREE |
| `uq_exec_metrica` | `execucao_etapa_id` | Sim | BTREE |
| `uq_exec_metrica` | `metrica_id` | Sim | BTREE |
| `fk_vme_metrica` | `metrica_id` | Não | BTREE |

---

