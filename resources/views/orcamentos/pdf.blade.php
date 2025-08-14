<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento #{{ $orcamento->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #2c5282;
            margin-bottom: 10px;
        }

        .header .company-info {
            font-size: 14px;
            color: #666;
        }

        .orcamento-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }

        .orcamento-info .left,
        .orcamento-info .right {
            width: 48%;
        }

        .orcamento-info h3 {
            font-size: 14px;
            color: #2c5282;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .orcamento-info p {
            margin-bottom: 5px;
        }

        .orcamento-info strong {
            color: #333;
        }

        .services-section {
            margin-bottom: 30px;
        }

        .services-section h2 {
            font-size: 16px;
            color: #2c5282;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #2c5282;
        }

        .service {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .service-header {
            background-color: #2c5282;
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .service-header h3 {
            font-size: 14px;
            margin: 0;
        }

        .service-header .price {
            font-size: 16px;
            font-weight: bold;
        }

        .service-content {
            padding: 15px;
        }

        .service-items {
            margin-bottom: 10px;
        }

        .service-items h4 {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }

        .service-items ul {
            list-style: none;
            padding-left: 0;
        }

        .service-items li {
            padding: 3px 0;
            padding-left: 15px;
            position: relative;
        }

        .service-items li:before {
            content: "•";
            color: #2c5282;
            position: absolute;
            left: 0;
        }

        .service-obs {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .service-obs h4 {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .totals {
            margin-top: 30px;
            border: 2px solid #2c5282;
            border-radius: 5px;
            overflow: hidden;
        }

        .totals-header {
            background-color: #2c5282;
            color: white;
            padding: 10px 15px;
            text-align: center;
        }

        .totals-header h2 {
            font-size: 16px;
            margin: 0;
        }

        .totals-content {
            padding: 15px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px dotted #ddd;
        }

        .totals-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #2c5282;
        }

        .observacoes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .observacoes h3 {
            font-size: 14px;
            color: #2c5282;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-draft {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-sent {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-approved {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .status-expired {
            background-color: #f3f4f6;
            color: #374151;
        }

        @media print {
            .container {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>ORÇAMENTO</h1>
            <div class="company-info">
                <strong>CutPlan - Planejados e Móveis</strong><br>
                <!-- Adicione aqui os dados da sua empresa -->
                <span>Rua Example, 123 - Bairro - Cidade/UF - CEP: 00000-000</span><br>
                <span>Telefone: (00) 0000-0000 | Email: contato@cutplan.com</span>
            </div>
        </div>

        <!-- Informações do Orçamento -->
        <div class="orcamento-info">
            <div class="left">
                <h3>Dados do Cliente</h3>
                <p><strong>Nome:</strong> {{ $orcamento->cliente->nome }}</p>
                @if ($orcamento->cliente->documento)
                    <p><strong>Documento:</strong> {{ $orcamento->cliente->documento }}</p>
                @endif
                @if ($orcamento->cliente->email)
                    <p><strong>Email:</strong> {{ $orcamento->cliente->email }}</p>
                @endif
                @if ($orcamento->cliente->telefone)
                    <p><strong>Telefone:</strong> {{ $orcamento->cliente->telefone }}</p>
                @endif
            </div>
            <div class="right">
                <h3>Dados do Orçamento</h3>
                <p><strong>Número:</strong> #{{ $orcamento->id }}</p>
                <p><strong>Data:</strong> {{ $orcamento->created_at->format('d/m/Y') }}</p>
                @if ($orcamento->validade)
                    <p><strong>Validade:</strong> {{ \Carbon\Carbon::parse($orcamento->validade)->format('d/m/Y') }}</p>
                @endif
                <p><strong>Status:</strong>
                    <span class="status-badge status-{{ $orcamento->status }}">
                        @switch($orcamento->status)
                            @case('draft')
                                Rascunho
                            @break

                            @case('sent')
                                Enviado
                            @break

                            @case('approved')
                                Aprovado
                            @break

                            @case('rejected')
                                Rejeitado
                            @break

                            @case('expired')
                                Expirado
                            @break

                            @default
                                {{ $orcamento->status }}
                        @endswitch
                    </span>
                </p>
            </div>
        </div>

        <!-- Serviços -->
        <div class="services-section">
            <h2>Serviços e Itens</h2>

            @if (is_array($orcamento->servicos) && count($orcamento->servicos) > 0)
                @foreach ($orcamento->servicos as $servico)
                    <div class="service">
                        <div class="service-header">
                            <h3>{{ $servico['nome'] ?? 'Serviço sem nome' }}</h3>
                            <div class="price">R$ {{ number_format($servico['valor_total'] ?? 0, 2, ',', '.') }}</div>
                        </div>
                        <div class="service-content">
                            @if (isset($servico['itens']) && is_array($servico['itens']) && count($servico['itens']) > 0)
                                <div class="service-items">
                                    <h4>Itens inclusos:</h4>
                                    <ul>
                                        @foreach ($servico['itens'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (!empty($servico['observacoes']))
                                <div class="service-obs">
                                    <h4>Observações:</h4>
                                    <p>{{ $servico['observacoes'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p>Nenhum serviço encontrado para este orçamento.</p>
            @endif
        </div>

        <!-- Totais -->
        <div class="totals">
            <div class="totals-header">
                <h2>Resumo Financeiro</h2>
            </div>
            <div class="totals-content">
                @php
                    $subtotal = 0;
                    if (is_array($orcamento->servicos)) {
                        $subtotal = collect($orcamento->servicos)->sum('valor_total');
                    }
                    $desconto = floatval($orcamento->desconto ?? 0);
                    $impostos = floatval($orcamento->impostos ?? 0);
                    $total = $subtotal - $desconto + $impostos;
                @endphp

                <div class="totals-row">
                    <span>Subtotal:</span>
                    <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                </div>

                @if ($desconto > 0)
                    <div class="totals-row">
                        <span>Desconto:</span>
                        <span>- R$ {{ number_format($desconto, 2, ',', '.') }}</span>
                    </div>
                @endif

                @if ($impostos > 0)
                    <div class="totals-row">
                        <span>Impostos:</span>
                        <span>+ R$ {{ number_format($impostos, 2, ',', '.') }}</span>
                    </div>
                @endif

                <div class="totals-row">
                    <span>TOTAL GERAL:</span>
                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Observações -->
        @if (isset($orcamento->observacoes_originais) && $orcamento->observacoes_originais)
            <div class="observacoes">
                <h3>Observações Gerais</h3>
                <p>{{ $orcamento->observacoes_originais }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Este orçamento foi gerado em {{ now()->format('d/m/Y H:i') }}</p>
            <p>Orçamento válido conforme condições apresentadas</p>
        </div>
    </div>
</body>

</html>
