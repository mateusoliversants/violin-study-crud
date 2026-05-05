<?php
$this->assign('title', 'Visualizar Sessão');

$portletHead = $this->Html->div(
    'm-portlet__head d-block d-md-flex',

    $this->Html->div(
        'm-portlet__head-caption',

        $this->Html->tag('h3', 'Visualizar Sessão', [
            'class' => 'm-portlet__head-text'
        ])
    ) .

    $this->Html->div(
        'm-portlet__head-tools mt-2 mt-md-0',

        $this->Html->div(
            'm-portlet__head-tools-wrapper',

            $this->Html->link(
                'Editar',
                ['action' => 'edit', $sesso->id],
                ['class' => 'btn btn-warning m-btn btn-sm m-btn--custom mb-2']
            ) . ' ' .

            $this->Form->postLink(
                '',
                ['action' => 'delete', $sesso->id], 
                [
                    'class' => 'btn btn-danger m-btn btn-sm m-btn--custom flaticon-delete-1 mb-2',
                    'confirm' => 'Tem certeza?'
                ]
            ) . ' ' .

            $this->Html->link(
                'Voltar',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary m-btn btn-sm m-btn--custom mb-2']
            )
        )
    )
);

$table = '';

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Nome da Sessão') .
    $this->Html->tag('td', h($sesso->name))
);

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Apostila') .
    $this->Html->tag(
        'td',
        $sesso->has('apostila')
            ? $this->Html->link(
                $sesso->apostila->name,
                ['controller' => 'Apostilas', 'action' => 'view', $sesso->apostila->id]
            )
            : ''
    )
);

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Data') .
    $this->Html->tag('td', h($this->Time->format($sesso->sessao_date, 'dd/MM/yyyy')))
);

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Hora Inicial') .
    $this->Html->tag('td', h($sesso->start_time))
);

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Hora Final') .
    $this->Html->tag('td', h($sesso->end_time))
);

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Duração') .
    $this->Html->tag('td', h($sesso->duracao))
);

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Criado em') .
    $this->Html->tag('td', h($this->Time->format($sesso->created, 'dd/MM/yyyy')))
);

$table = $this->Html->tag(
    'table',
    $table,
    ['class' => 'table m-table m-table--head-bg-brand']
);

$conteudo = $this->Html->div(
    'm-portlet__body',

    $this->Html->tag('h5', 'Conteúdo') .
    $this->Html->tag(
        'p',
        h($sesso->conteudo)
    ) .

    $this->Html->tag('h5', 'Objetivo') .
    $this->Html->tag(
        'p',
        h($sesso->objetivo)
    )
);

$body = $this->Html->div(
    'm-portlet__body',
    $table
) . $conteudo;

$portlet = $this->Html->div(
    'm-portlet',
    $portletHead . $body
);

$content = $this->Html->div(
    'm-content',
    $portlet
);

echo $content;