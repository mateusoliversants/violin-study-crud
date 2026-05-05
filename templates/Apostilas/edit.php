<?php
$this->assign('title', 'Editar Apostila');

$portletHead = $this->Html->div(
    'm-portlet__head',

    $this->Html->div(
        'm-portlet__head-caption',
        
        $this->Html->tag('h3', 'Editar Apostila', [
            'class' => 'm-portlet__head-text'
        ])
    )
);

$form = $this->Metronic->formCreate($apostila, [
    'type' => 'file',
    'class' => 'm-form m-form--fit m-form--label-align-right'
]);

$form .= $this->Metronic->input('name', [
    'label' => 'Nome da Apostila',
    'class' => 'form-control m-input',
]);

$form .= $this->Metronic->input('nivel', [
    'label' => 'Dificuldade',
    'class' => 'form-control m-select2',
    'type' => 'select',
    'options' => [
        'iniciante' => 'Iniciante',
        'intermediario' => 'Intermediário',
        'avancado' => 'Avançado'
    ]
]);

$form .= $this->Html->div(
    'form-group m-form__group',

    $this->Html->tag('label', 'Arquivo (PDF)', [
        'class' => 'form-control-label'
    ]) .

    (!empty($apostila->arquivo) ?
        $this->Html->div(
            'mb-3',
            'Arquivo atual: ' .
            $this->Html->link(
                $apostila->arquivo,
                '/uploads/apostilas/' . $apostila->arquivo,
                ['target' => '_blank']
            )
        )
    : '') .

    $this->Html->div('col-md-12',
        $this->element('apostila_dropzone')
    )
);

$form .= $this->Html->div(
    'm-form__actions',

    $this->Metronic->link('Atualizar', [
        'class' => 'btn btn-primary m-btn m-btn--custom',
        'post-url' => $this->Url->build(['action' => 'edit', $apostila->id])
    ]) . ' ' .

    $this->Metronic->link('Cancelar', [
        'class' => 'btn btn-secondary m-btn m-btn--custom',
        'post-url' => $this->Url->build('/apostilas/index')
    ])
);

$form .= $this->Form->end();

$body = $this->Html->div(
    'm-portlet__body',
    $form
);

$portlet = $this->Html->div(
    'm-portlet',
    $portletHead . $body
);

$content = $this->Html->div(
    'm-content',
    $portlet
);

echo $content;
