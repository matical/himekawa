<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2' => true,

    'array_syntax' => ['syntax' => 'short'],
    'no_multiline_whitespace_before_semicolons' => true,
    'no_short_echo_tag' => true,
    'not_operator_with_successor_space' => true,
    'concat_space' => ['spacing' => 'one'],
    'binary_operator_spaces' => ['align_double_arrow' => true],
    'single_quote' => true,
    'ordered_imports' => ['sortAlgorithm' => 'length'],
    'phpdoc_align' => [
        'tags' => [
            'param',
            'type',
            'var',
        ],
    ],
    'phpdoc_annotation_without_dot' => true,
    'phpdoc_indent' => true,
    'phpdoc_scalar' => true,
    'phpdoc_single_line_var_spacing' => true,
    'phpdoc_summary' => true,
];

$excludes = [
    'vendor',
    'node_modules',
    'storage',
    'public',
    'bootstrap/cache',
];

return Config::create()
             ->setRules($rules)
             ->setFinder(Finder::create()
                               ->exclude($excludes)
                               ->notName('.phpstorm.meta.php')
                               ->notName('_ide_helper.php')
                               ->notName('_ide_helper_models.php')
                               ->notName('README.md')
                               ->notName('*.xml')
                               ->notName('*.yml')
             );
