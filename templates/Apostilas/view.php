<?php
$this->assign('title', 'Visualizar Apostila');

$portletHead = $this->Html->div(
    'm-portlet__head d-block d-md-flex',

    $this->Html->div(
        'm-portlet__head-caption',

        $this->Html->tag('h3', 'Visualizar Apostila', [
            'class' => 'm-portlet__head-text'
        ])
    ) .

    $this->Html->div(
        'm-portlet__head-tools mt-2 mt-md-0',

        $this->Html->div(
            'm-portlet__head-tools-wrapper',

            $this->Html->link(
                'Editar',
                ['action' => 'edit', $apostila->id],
                ['class' => 'btn btn-warning m-btn btn-sm m-btn--custom mb-2']
            ) . ' ' .

            $this->Form->postLink(
                '',
                ['action' => 'delete', $apostila->id],
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
    $this->Html->tag('th', 'Nome da Apostila') .
    $this->Html->tag('td', h($apostila->name))
);

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Nível') .
    $this->Html->tag('td', h($apostila->nivel))
);

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Arquivo') .
    $this->Html->tag(
        'td',
        $apostila->arquivo
            ? $this->Html->link(
                $apostila->arquivo,
                '/uploads/apostilas/' . $apostila->arquivo,
                ['target' => '_blank']
            )
            : 'Nenhum arquivo'
    )
);

$table .= $this->Html->tag(
    'tr',
    $this->Html->tag('th', 'Criado em') .
    $this->Html->tag('td', h($this->Time->format($apostila->created, 'dd/MM/yyyy')))
);

$table = $this->Html->tag(
    'table',
    $table,
    ['class' => 'table m-table m-table--head-bg-brand']
);

$body = $this->Html->div(
    'm-portlet__body',
    $table
);


$sessoesTable = '';

if (!empty($apostila->sessoes)) {

    $thead = $this->Html->tag(
        'thead',
        $this->Html->tag(
            'tr',
            $this->Html->tag('th', 'Nome') .
            $this->Html->tag('th', 'Data') .
            $this->Html->tag('th', 'Ações')
        )
    );

    $tbody = '';

    foreach ($apostila->sessoes as $sesso) {

        $actions =
            $this->Html->link('Ver', ['controller' => 'Sessoes', 'action' => 'view', $sesso->id], [
                'class' => 'btn btn-info m-btn btn-sm m-btn--custom'
            ]) . ' ' .

            $this->Html->link('Editar', ['controller' => 'Sessoes', 'action' => 'edit', $sesso->id], [
                'class' => 'btn btn-warning m-btn btn-sm m-btn--custom'
            ]);

        $tbody .= $this->Html->tag(
            'tr',
            $this->Html->tag('td', h($sesso->name)) .
            $this->Html->tag('td', $this->Time->format($sesso->sessao_date, 'dd/MM/yyyy')) .
            $this->Html->tag('td', $actions)
        );
    }

    $tbody = $this->Html->tag('tbody', $tbody);

    $sessoesTable = $this->Html->div(
        'm-portlet__body',

        $this->Html->tag('h4', 'Sessões Relacionadas') .

        $this->Html->div(
            'table-responsive',
            $this->Html->tag(
                'table',
                $thead . $tbody,
                ['class' => 'table m-table m-table--head-bg-brand']
            )
        )
    );
}

$portlet = $this->Html->div(
    'm-portlet',
    $portletHead . $body . $sessoesTable
);

$content = $this->Html->div(
    'm-content',
    $portlet
);

echo $content;
