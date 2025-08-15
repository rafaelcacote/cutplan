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
        <div class="orcamento-info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 0 24px; margin-bottom: 30px; padding: 15px; background-color: #f8f9fa; border: 1px solid #ddd;">
            <div>
                <h3 style="font-size: 14px; color: #2c5282; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Cliente</h3>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td style="font-weight: bold; width: 70px;">Nome:</td>
                        <td>{{ $orcamento->cliente->nome }}</td>
                    </tr>
                    @if ($orcamento->cliente->documento)
                    <tr>
                        <td style="font-weight: bold;">Documento:</td>
                        <td>{{ $orcamento->cliente->documento }}</td>
                    </tr>
                    @endif
                    @if ($orcamento->cliente->email)
                    <tr>
                        <td style="font-weight: bold;">Email:</td>
                        <td>{{ $orcamento->cliente->email }}</td>
                    </tr>
                    @endif
                    @if ($orcamento->cliente->telefone)
                    <tr>
                        <td style="font-weight: bold;">Telefone:</td>
                        <td>{{ $orcamento->cliente->telefone }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div>
                <h3 style="font-size: 14px; color: #2c5282; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Orçamento</h3>
                <table style="width: 100%; font-size: 12px;">
                    <tr>
                        <td style="font-weight: bold; width: 80px;">Número:</td>
                        <td>#{{ $orcamento->id }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Data:</td>
                        <td>{{ $orcamento->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @if ($orcamento->validade)
                    <tr>
                        <td style="font-weight: bold;">Validade:</td>
                        <td>{{ \Carbon\Carbon::parse($orcamento->validade)->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-weight: bold;">Status:</td>
                        <td>
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
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Serviços -->
        <div class="services-section">
            <h2>Serviços e Itens</h2>
            @if (is_array($orcamento->servicos) && count($orcamento->servicos) > 0)
            <table style="width:100%; border-collapse:collapse; margin-bottom:30px;">
                <thead>
                    <tr style="background:#153e8a; color:#fff;">
                        <th style="padding:8px; border:1px solid #153e8a; font-size:13px; text-align:left;">SERVIÇO</th>
                        <th style="padding:8px; border:1px solid #153e8a; font-size:13px; text-align:left;">DESCRIÇÃO</th>
                        <th style="padding:8px; border:1px solid #153e8a; font-size:13px; text-align:right;">VALOR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orcamento->servicos as $servico)
                    <tr>
                        <td style="padding:10px 8px; border:1px solid #ddd; vertical-align:top; width:25%;">
                            {{ $servico['nome'] ?? 'Serviço sem nome' }}
                        </td>
                        <td style="padding:10px 8px; border:1px solid #ddd; vertical-align:top; width:55%;">
                            @if (isset($servico['itens']) && is_array($servico['itens']) && count($servico['itens']) > 0)
                                <ul style="margin:0 0 0 18px; padding:0; list-style:disc;">
                                    @foreach ($servico['itens'] as $item)
                                        <li style="margin-bottom:2px;">{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @if (!empty($servico['observacoes']))
                                <div style="margin-top:6px; font-size:11px; color:#444;">
                                    <strong>Obs.:</strong> {{ $servico['observacoes'] }}
                                </div>
                            @endif
                        </td>
                        <td style="padding:10px 8px; border:1px solid #ddd; vertical-align:top; text-align:right; width:20%; white-space:nowrap; font-weight:bold;">
                            R$ {{ number_format($servico['valor_total'] ?? 0, 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
