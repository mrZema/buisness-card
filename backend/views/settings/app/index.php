<?php

use kartik\tabs\TabsX;
use core\entities\settings\Section;

/* @var $backendForm backend\forms\settings\BackendForm */
/* @var $sections Section[]*/
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'App Settings');
$this->params['breadcrumbs'][] = $this->title;

echo TabsX::widget([
    'position' => TabsX::POS_ABOVE,
    'encodeLabels' => false,
    'enableStickyTabs' => true,
    'items' => [
        [
            'label' => '<i class="fas fa-user"></i> Frontend',
            'content' => $this->render('_section', [
                'sections' => $sections,
                'section_name' => 'FrontEnd'
            ]),
            'active' => true,
            'options' => ['id' => 'frontend']
        ],
        [
            'label' => '<i class="fas fa-columns"></i> Backend',
            'content' => $this->render('_section', [
                'sections' => $sections,
                'section_name' => 'BackEnd'
            ]),
            'options' => ['id' => 'backend']
        ],
        [
            'label' => '<i class="fas fa-list-alt"></i> Menu',
            'items' => [
                [
                    'label' => 'Option 1',
                    'encode' => false,
                    'content' => 'Empty string',
                ],
                [
                    'label' => 'Option 2',
                    'encode' => false,
                    'content' => 'Empty string',
                ],
            ],
        ],
        [
            'label' => '<i class="fas fa-times"></i> Disabled',
            'linkOptions' => ['class' => 'disabled']
        ],
    ]
]);
