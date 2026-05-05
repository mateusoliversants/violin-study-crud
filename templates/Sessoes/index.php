<?php
$this->assign('title', 'Sessões');

$breadcrumb = $this->Html->div(
    'm-subheader',

    $this->Html->div(
        'd-flex align-items-center',

        $this->Html->div(
            'mr-auto',

            $this->Html->tag('h3', 'Sessões', [
                'class' => 'm-subheader__title m-subheader__title--separator'
            ]) .

            $this->Html->tag(
                'ul',
                
                $this->Html->tag(
                    'li',

                    $this->Html->tag(
                        'span',

                        $this->Html->tag('span', 'Sessões', [
                            'class' => 'm-nav__link-text'
                        ]),

                        ['class' => 'm-nav__link']
                    ),

                    ['class' => 'm-nav__item m-nav__item--active']
                ) .

                $this->Html->tag(
                    'li',
                    '-', ['class' => 'm-nav__separator']
                ) .

                $this->Html->tag(
                    'li',

                    $this->Html->link(
                        $this->Html->tag('span', 'Apostilas', [
                            'class' => 'm-nav__link-text'
                        ]),
                        ['controller' => 'Apostilas', 'action' => 'index'],
                        [
                            'class' => 'm-nav__link',
                            'escape' => false
                        ]
                    ),

                    ['class' => 'm-nav__item']
                ) .

                $this->Html->tag(
                    'li',
                    '-', ['class' => 'm-nav__separator']
                ) .

                $this->Html->tag(
                    'li',

                    $this->Html->link(
                        $this->Html->tag('span', 'Logout', [
                            'class' => 'm-nav__link-text'
                        ]),
                        ['controller' => 'Users', 'action' => 'logout'],
                        [
                            'class' => 'm-nav__link',
                            'escape' => false
                        ]
                    ),

                    ['class' => 'm-nav__item']
                ),

                ['class' => 'm-subheader__breadcrumbs m-nav m-nav--inline']
            )
        )
    )
);

$portletHead = $this->Html->div(
    'm-portlet__head',

    $this->Html->div(
        'm-portlet__head-caption',
        $this->Html->tag('h3', 'Lista de Sessões', [
            'class' => 'm-portlet__head-text'
        ])
    ) .

    $this->Html->div(
        'm-portlet__head-tools',

        $this->Metronic->addButton('add')
    )
);

$thead = $this->Html->tag(
    'thead',

    $this->Html->tag(
        'tr',
        $this->Html->tag('th', 'Nome') .
        $this->Html->tag('th', 'Apostila') .
        $this->Html->tag('th', 'Arquivo') .
        $this->Html->tag('th', 'Ações')
    )
);

$tbody = '';

foreach ($sessoes as $sesso) {

    $actions =
        $this->Html->link('Visualizar', ['action' => 'view', $sesso->id], [
            'class' => 'btn btn-info m-btn m-btn--custom'
        ]) . ' ' .

        $this->Html->link('Editar', ['action' => 'edit', $sesso->id], [
            'class' => 'btn btn-warning m-btn m-btn--custom'
        ]). ' ' .

        $this->Metronic->deleteButton([
            'post-url' => $this->Url->build(['action' => 'delete', $sesso->id])
        ]);

    $tbody .= $this->Html->tag(
        'tr',

        $this->Html->tag('td', h($sesso->name)) .
        $this->Html->tag(
            'td',

                $this->Html->link(
                    $sesso->apostila->name,
                    ['controller' => 'Apostilas', 'action' => 'view', $sesso->apostila->id]
                )
        ) .
        $this->Html->tag('td', $this->Time->format($sesso->sessao_date, 'dd/MM/yyyy')) .
        $this->Html->tag('td', $actions)
    );
}

$tbody = $this->Html->tag('tbody', $tbody);

$table = $this->Html->tag(
    'table',
    $thead . $tbody,
    ['class' => 'table m-table m-table--head-bg-brand']
);

$body = $this->Html->div(
    'm-portlet__body',
    $this->Html->div('table-responsive', $table)
);

$portlet = $this->Html->div(
    'm-portlet',
    $portletHead . $body
);

$content = $this->Html->div(
    'm-content',
    $portlet
);

echo $breadcrumb . $content;
