<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR2' => true,

    'array_syntax'                              => ['syntax' => 'short'],
    'binary_operator_spaces'                    => [
        'operators' => ['=>' => 'align_single_space'],
    ],
    'class_attributes_separation'               => ['elements' => ['const', 'method', 'property']],
    'concat_space'                              => ['spacing' => 'one'],
    'function_typehint_space'                   => true,
    'multiline_whitespace_before_semicolons'    => true,
    'no_multiline_whitespace_before_semicolons' => true,
    'no_short_echo_tag'                         => true,
    'no_unused_imports'                         => true,
    'not_operator_with_successor_space'         => true,
    'ordered_imports'                           => ['sortAlgorithm' => 'length'],
    'single_quote'                              => true,
    'phpdoc_align'                              => [
        'tags' => [
            'param',
            'type',
            'var',
        ],
    ],
    'phpdoc_annotation_without_dot'             => true,
    'phpdoc_indent'                             => true,
    'phpdoc_scalar'                             => true,
    'phpdoc_single_line_var_spacing'            => true,
    'phpdoc_summary'                            => true,
    'phpdoc_trim'                               => true,
    'phpdoc_types'                              => true,
    'simplified_null_return'                    => true,
    'single_line_comment_style'                 => true,
    'trailing_comma_in_multiline_array'         => true,
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
