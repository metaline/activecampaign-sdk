<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()

    // paths
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withRootFiles()

    // rules
    ->withEditorConfig()
    ->withSets([
        SetList::PSR_12,
    ])
    ->withPhpCsFixerSets(
        false, // doctrineAnnotation
        false, // per
        false, // perCS
        false, // perCS10
        false, // perCS10Risky
        true, // perCS20
        false, // perCS20Risky
        false, // perCSRisky
        false, // perRisky
        false, // php54Migration
        false, // php56MigrationRisky
        false, // php70Migration
        false, // php70MigrationRisky
        false, // php71Migration
        false, // php71MigrationRisky
        false, // php73Migration
        false, // php74Migration
        false, // php74MigrationRisky
        false, // php80Migration
        false, // php80MigrationRisky
        false, // php81Migration
        false, // php82Migration
        false, // php83Migration
        true, // php84Migration
        false, // phpunit30MigrationRisky
        false, // phpunit32MigrationRisky
        false, // phpunit35MigrationRisky
        false, // phpunit43MigrationRisky
        false, // phpunit48MigrationRisky
        false, // phpunit50MigrationRisky
        false, // phpunit52MigrationRisky
        false, // phpunit54MigrationRisky
        false, // phpunit55MigrationRisky
        false, // phpunit56MigrationRisky
        false, // phpunit57MigrationRisky
        false, // phpunit60MigrationRisky
        false, // phpunit75MigrationRisky
        false, // phpunit84MigrationRisky
        false, // phpunit100MigrationRisky
        false, // psr1
        false, // psr2
        true, // psr12
        false, // psr12Risky
        false, // phpCsFixer
        false, // phpCsFixerRisky
        false, // symfony
        false, // symfonyRisky
    )
    ->withRules([
        CastSpacesFixer::class,
    ])
    ->withConfiguredRule(BinaryOperatorSpacesFixer::class, [
        'operators' => [
            // Aligns array key-value pairs (@link https://stackoverflow.com/a/66886439)
            '=>' => 'align_single_space_minimal_by_scope',
        ],
    ])

    // exclusion
    ->withSkip([]);
