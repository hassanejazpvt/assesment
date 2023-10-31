<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__) // Change this path to the directory where your PHP files are located
    ->name('*.php')
    ->notPath('vendor');

return (new Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'function_typehint_space' => true,
        'no_unused_imports' => true,
        'binary_operator_spaces' => ['operators' => ['=>' => 'align', '=' => 'align']],
        'include' => true,
        'no_superfluous_phpdoc_tags' => ['allow_mixed' => true],
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'function_declaration' => ['closure_function_spacing' => 'one'],
        'linebreak_after_opening_tag' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'visibility_required' => ['elements' => ['property', 'method', 'const']],
        'no_extra_blank_lines' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_trailing_comma_in_list_call' => true,
        'ordered_class_elements' => ['order' => ['use_trait', 'constant', 'property', 'construct', 'destruct', 'magic', 'method']],
        'php_unit_strict' => true,
        'single_line_throw' => true,
        'yoda_style' => false,
        'phpdoc_to_comment' => false
    ]);
