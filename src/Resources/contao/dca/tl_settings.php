<?php

// Palettes
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(
    'allowedTags',
    'allowedTags,passwordless_ttl,passwordless_emailFrom,passwordless_emailFromName,passwordless_emailSubject',
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);

// Fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['passwordless_ttl'] = [
    'inputType' => 'text',
    'eval' => ['rgxp'=>'natural', 'minval'=>1, 'nospace'=>true, 'tl_class'=>'w50']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['passwordless_emailFrom'] = [
    'inputType' => 'text',
    'eval' => ['mandatory'=>true, 'rgxp'=>'email', 'tl_class'=>'w50']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['passwordless_emailFromName'] = [
    'inputType' => 'text',
    'eval' => ['tl_class'=>'clear w50']
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['passwordless_emailSubject'] = [
    'inputType' => 'text',
    'eval' => ['tl_class'=>'w50']
];
