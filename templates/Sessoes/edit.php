<?php
$this->assign('title', 'Editar Sessão');

$portletHead = $this->Html->div(
    'm-portlet__head',

    $this->Html->div(
        'm-portlet__head-caption',
        
        $this->Html->tag('h3', 'Editar Sessão', [
            'class' => 'm-portlet__head-text'
        ])
    )
);

$form = $this->Metronic->formCreate($sesso, [
    'class' => 'm-form m-form--fit m-form--label-align-right'
]);

$form .= $this->Metronic->input('name', [
    'label' => 'Nome da Sessão',
    'class' => 'form-control m-input',
]);

$form .= $this->Metronic->input('apostila_id', [
    'label' => 'Apostila',
    'options' => $apostilas,
    'empty' => 'Selecione...',
    'class' => 'form-control m-select2'
]);

$form .= $this->Metronic->input('sessao_date', [
    'label' => 'Data',
    'type' => 'text',
    'class' => 'form-control m-input w-100 datepicker',
    'placeholder' => 'Selecione a data',
    'data-provide' => 'datepicker',
    'data-date-autoclose' => 1,
    'data-date-language' => 'pt-BR',
    'data-date-format' => "dd/mm/yyyy",
    'data-date-today-highlight' => 1
]);

$form .= $this->Metronic->input('start_time', [
    'label' => 'Hora Inicial',
    'type' => 'text',
    'class' => 'form-control m-input',
    'data-inputmask' => "'alias': 'hh:mm'"
]);

$form .= $this->Metronic->input('end_time', [
    'label' => 'Hora Final',
    'type' => 'text',
    'class' => 'form-control m-input',
    'data-inputmask' => "'alias': 'hh:mm'"
]);

$form .= $this->Metronic->input('conteudo', [
    'label' => 'Conteúdo',
    'class' => 'form-control m-input',
    'type' => 'textarea',
    'escape' => false
]);

$form .= $this->Metronic->input('objetivo', [
    'label' => 'Objetivo',
    'class' => 'form-control m-input',
    'type' => 'textarea',
    'escape' => false
]);

$form .= $this->Html->div(
    'm-form__actions',

    $this->Metronic->link('Atualizar', [
        'class' => 'btn btn-primary m-btn m-btn--custom',
        'post-url' => $this->Url->build(['action' => 'edit', $sesso->id])
    ]) . ' ' .

    $this->Metronic->link('Cancelar', [
        'class' => 'btn btn-secondary m-btn m-btn--custom',
        'post-url' => $this->Url->build('/sessoes/index')
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