<?php
$dropzone = $this->Html->div(
    'apostila-dropzone text-center',
    $this->Html->div(
        'dz-message',
        $this->Html->tag('h6', 'Arraste o PDF ou clique aqui')
    ) .
    $this->Html->div('dz-previews', '')
);

$dropzone .= $this->Metronic->dropzone('.apostila-dropzone', [
    'url' => $this->Url->build('/apostilas/upload'),
    'clickable' => '.apostila-dropzone',
    'previewsContainer' => ".apostila-dropzone .dz-previews",
    'parallelUploads' => 1,
    'maxFilesize' => 30,
    'acceptedFiles' => ".pdf,.PDF",
    'maxFiles' => 1,
    'headers' => [
        'X-CSRF-Token' => $this->request->getAttribute('csrfToken')
    ]
]);

echo $dropzone;