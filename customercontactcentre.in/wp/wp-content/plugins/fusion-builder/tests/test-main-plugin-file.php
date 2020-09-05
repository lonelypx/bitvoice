<?php
/**
 * Class Test_Main_Plugin_File
 *
 * @package Fusion_Builder
 */

/**
 * Sample test case.
 */
class Test_Main_Plugin_File extends WP_UnitTestCase {

	public function test_FusionBuilder() {
		$this->assertTrue( is_object( FusionBuilder() ) );
		$this->assertTrue( FusionBuilder() instanceof FusionBuilder );	}

	public function test_fusion_builder_add_elements_options() {
		$this->assertTrue( 1 === count( fusion_builder_add_elements_options( array() ) ) );
		$this->assertTrue( isset( fusion_builder_add_elements_options( array() )['elements'] ) );
	}
}
