<?php
$this->assign('title', 'Cadastro');
$this->extend('MetronicV4.Pages/login3');

$head = $this->Html->div(
    'm-login__head',

    $this->Html->tag('h3', 'Cadastro', ['class' => 'm-login__title'])
);

$form = $this->Metronic->formCreate(null, [
    'class' => 'm-login__form m-form'
]);

$form .= $this->Metronic->input('email', [
    'templates' => [
        'formGroup' => '{{input}}',
    ],
    'placeholder' => 'Email',
]);

$form .= $this->Metronic->input('password', [
    'templates' => [
        'formGroup' => '{{input}}',
    ],
    'type' => 'password',
    'placeholder' => 'Senha',
]);

$form .= $this->Html->div(
    'm-login__form-action',
    
    $this->Metronic->link('Confirmar', [
        'class' => 'btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn',
        'post-url' => $this->Url->build('/users/register')
    ])
);

$form .= $this->Form->end();

$signin = $this->Html->div(
    'm-login__signin',
    $head . $form
);

$this->assign('form', $signin);
