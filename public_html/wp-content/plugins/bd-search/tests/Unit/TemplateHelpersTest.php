<?php

namespace BDSearch\Tests\Unit;

/**
 * Tests for template part class and ID generators.
 *
 * @see inc/templates/functions/get-classes.php
 * @see inc/templates/functions/get-id.php
 */
class TemplateHelpersTest extends BaseTestCase
{
    // -------------------------------------------------------
    // bd324_get_algolia_template_part_classes
    // -------------------------------------------------------

    public function test_classes_name_only(): void
    {
        $result = bd324_get_algolia_template_part_classes('searchbox');
        $this->assertSame('searchbox algolia-searchbox', $result);
    }

    public function test_classes_with_index(): void
    {
        $result = bd324_get_algolia_template_part_classes('searchbox', 'global');
        $this->assertSame('searchbox algolia-searchbox searchbox--global', $result);
    }

    public function test_classes_with_index_and_template(): void
    {
        $result = bd324_get_algolia_template_part_classes('searchbox', 'global', 'modal');
        $this->assertSame('searchbox algolia-searchbox searchbox--global searchbox--modal', $result);
    }

    public function test_classes_null_index_skipped(): void
    {
        $result = bd324_get_algolia_template_part_classes('hits', null, 'advanced');
        $this->assertSame('hits algolia-hits hits--advanced', $result);
    }

    public function test_classes_null_template_skipped(): void
    {
        $result = bd324_get_algolia_template_part_classes('hits', 'global', null);
        $this->assertSame('hits algolia-hits hits--global', $result);
    }

    // -------------------------------------------------------
    // bd324_get_algolia_template_part_id
    // -------------------------------------------------------

    public function test_id_name_only(): void
    {
        $result = bd324_get_algolia_template_part_id('searchbox');
        $this->assertSame('algolia-searchbox', $result);
    }

    public function test_id_with_index(): void
    {
        $result = bd324_get_algolia_template_part_id('searchbox', 'global');
        $this->assertSame('algolia-searchbox--global', $result);
    }

    public function test_id_with_index_and_template(): void
    {
        $result = bd324_get_algolia_template_part_id('searchbox', 'global', 'modal');
        $this->assertSame('algolia-searchbox--global--modal', $result);
    }

    public function test_id_null_index_skipped(): void
    {
        $result = bd324_get_algolia_template_part_id('hits', null, 'advanced');
        $this->assertSame('algolia-hits--advanced', $result);
    }

    public function test_id_null_template_skipped(): void
    {
        $result = bd324_get_algolia_template_part_id('hits', 'global', null);
        $this->assertSame('algolia-hits--global', $result);
    }
}
