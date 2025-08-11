<?php

define('PHP_CS_FIXER_CACHE_FILE', __DIR__ . '/var/cache/.php-cs-fixer.cache');

try {
    mkdir(dirname(PHP_CS_FIXER_CACHE_FILE), 0755, true);
} catch (exception $e) {
}

// doc: https://github.com/FriendsOfPhp/PHP-CS-Fixer#usage
// https://symfony.com/doc/current/components/finder.html
$finder = new PhpCsFixer\Finder();

$finder->exclude(['.git', 'tpl_c', 'build', '.phpdoc', 'var', 'tools', 'vendor'])
    ->notPath(['lib/external'])
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

// rules: https://cs.symfony.com/doc/rules/index.html
return $config
    ->setCacheFile(PHP_CS_FIXER_CACHE_FILE)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true, // https://www.php-fig.org/psr/psr-12/
        'array_syntax' => ['syntax' => 'short'],
        'array_indentation' => true,
        'combine_consecutive_unsets' => true,
        'single_quote' => true,
    ])
    ->setFinder($finder);
