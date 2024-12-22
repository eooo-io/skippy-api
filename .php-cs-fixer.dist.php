<?php

$finder = PhpCsFixer\Finder::create()
                           ->in([
                               __DIR__ . '/config',
                               __DIR__ . '/public',
                               __DIR__ . '/src',
                               __DIR__ . '/tests',
                           ])
                           ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRules([
        'align_multiline_comment' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'align_single_space'],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'cast_spaces' => ['space' => 'single'],
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'none'],
        'function_declaration' => ['closure_function_spacing' => 'none'],
        'indentation_type' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'no_extra_blank_lines' => ['tokens' => ['extra']],
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_align' => ['align' => 'vertical'],
        'phpdoc_order' => true,
        'single_quote' => true,
        'space_after_semicolon' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);