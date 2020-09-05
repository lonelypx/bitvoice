<?php
/**
 * Class Test_FusionBuilder
 *
 * @package Fusion_Builder
 */

/**
 * Sample test case.
 */
class Test_Object_FusionBuilder extends WP_UnitTestCase {

	public function test_register_rich_buttons() {
		$fb = FusionBuilder();
		$this->assertTrue( false === $fb->register_rich_buttons( false ) );
		$this->assertEquals( array( 'fusion_button' ), $fb->register_rich_buttons( array() ) );
		$this->assertEquals( array( 'test', 'fusion_button' ), $fb->register_rich_buttons( array( 'test' ) ) );
	}
}
