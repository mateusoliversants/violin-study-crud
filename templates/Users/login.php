<?php
$this->assign('title', 'Login');
$this->extend('MetronicV4.Pages/login3');

$head = $this->Html->div(
    'm-login__head',
    $this->Html->tag('h3', 'Login', ['class' => 'm-login__title'])
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
    'row m-login__form-sub',
    $this->Html->div(
        'col m--align-right m-login__form-right',
        
        $this->Metronic->link('Esqueceu a senha?', [
            'class' => 'm-link',
            'get-url' => $this->Url->build('/users/forgot-password')
        ])
    )
);

$form .= $this->Html->div(
    'm-login__form-action',
    
    $this->Metronic->link('Entrar', [
        'class' => 'btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn',
        'post-url' => $this->Url->build('/users/login')
    ])
);

$form .= $this->Form->end();

$form .= $this->Flash->render('danger');

$signin = $this->Html->div(
    'm-login__signin',
    $head . $form
);

$cadastro = $this->Html->div(
    'm-login__account',
    
    $this->Html->tag('span', 'Ainda não tem uma conta? ', [
        'class' => 'm-login__account-msg',
    ]) .

    $this->Metronic->link('Cadastre-se', [
        'class' => 'm-link m-link--light m-login__account-link',
        'get-url' => $this->Url->build('/users/register')
    ]),
);

$this->assign('form', $signin . $cadastro);
