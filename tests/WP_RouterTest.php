<?php
require_once 'PHPUnit/Framework.php';
require_once dirname( __FILE__ ) . '/../WP_Router_Utility.class.php';
require_once dirname( __FILE__ ) . '/../WP_Router.class.php';
require_once '/usr/lib/mockpress/mockpress.php';

class Mock_WP_Route {
	public $id;
	public $properties;
	public function __construct($id, $properties) {
		$this->id = $id;
		$this->properties = $properties;
	}
	public function set($key, $value) {
		$this->properties[$key] = $value;
	}
}
/**
 * Test class for WP_Router.
 * Generated by PHPUnit on 2011-05-19 at 20:44:44.
 */
class WP_RouterTest extends PHPUnit_Framework_TestCase {
	protected $router;
	protected $routes = array();

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp( ) {
		$this->router = $this->get_router();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown( ) {
	}

	private function get_router() {
		$router = $this->getMock( 'WP_Router', array( 'create_route' ), array(), '', FALSE );
		$router->expects( $this->any( ) )
						->method( 'create_route' )
						->will( $this->returnCallback( array( $this, 'mock_create_route' ) ) );
		return $router;
	}

	public function mock_create_route($id, $parameters) {
		return new Mock_WP_Route($id, $parameters);
	}

	public function testAdd_route( ) {
		$args = array(
			'page_callback' => 'test',
		);
		$this->router->add_route('test', $args);
		$this->assertEquals($args, $this->router->get_route('test')->properties);
		$this->assertEquals('test', $this->router->get_route('test')->id);
	}

	public function testGet_route( ) {
		$args = array(
			array(
				'page_callback' => 'test',
			),
			array(
				'page_callback' => 'test2',
			),
			array(
				'page_callback' => 'test3',
			)
		);
		$this->router->add_route('test', $args[0]);
		$this->router->add_route('test2', $args[1]);
		$this->router->add_route('test3', $args[2]);
		$this->assertEquals($args[0], $this->router->get_route('test')->properties);
		$this->assertEquals($args[1], $this->router->get_route('test2')->properties);
		$this->assertEquals($args[2], $this->router->get_route('test3')->properties);
		$this->assertEquals('test', $this->router->get_route('test')->id);
		$this->assertEquals('test2', $this->router->get_route('test2')->id);
		$this->assertEquals('test3', $this->router->get_route('test3')->id);
	}

	public function testEdit_route( ) {
		$args = array(
			'page_callback' => 'test',
		);
		$this->router->add_route('test', $args);
		$this->assertEquals($args, $this->router->get_route('test')->properties);
		$this->assertEquals('test', $this->router->get_route('test')->id);
		$first = array(
			'page_callback' => 'purple',
			'title' => 'new title',
		);
		$this->router->edit_route('test', $first);
		$this->assertEquals($first, $this->router->get_route('test')->properties);
		$this->assertEquals('test', $this->router->get_route('test')->id);
		$second = array(
			'title' => 'new title',
			'access_arguments' => array('post'),
		);
		$this->router->edit_route('test', $second);
		$this->assertEquals(array_merge($first, $second), $this->router->get_route('test')->properties);
		$this->assertEquals('test', $this->router->get_route('test')->id);
	}

	public function testRemove_route( ) {
		$args = array(
			'page_callback' => 'test',
		);
		$this->router->add_route('test', $args);
		$this->router->remove_route('test');
		$this->assertNull($this->router->get_route('test'));
	}
}

?>
