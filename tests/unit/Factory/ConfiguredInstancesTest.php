<?php

namespace Factory;

use Maslosoft\Gazebo\PluginFactory;
use Maslosoft\GazeboTest\Model\GenericPlugin;
use Maslosoft\GazeboTest\Model\MetalPlugin;
use Maslosoft\GazeboTest\Model\TestModel;
use Maslosoft\GazeboTest\Model\WaterPlugin;
use UnitTester;

class ConfiguredInstancesTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * Test Configuration
	 * @var mixed[][]
	 */
	private $config = [];

	/**
	 * Test Configuration
	 * @var mixed[][]
	 */
	private $config2 = [];

	protected function _before()
	{
		$this->config = [
			TestModel::class => [
				WaterPlugin::class,
				[
					'class' => MetalPlugin::class,
					'options' => true
				],
				GenericPlugin::class,
			],
		];
		$this->config2 = [
			TestModel::class => [
				WaterPlugin::class,
				[
					'class' => MetalPlugin::class,
					'options' => false
				],
				GenericPlugin::class,
			],
		];
	}

	public function testIfWillConfigureModels()
	{
		$model = new TestModel;
		$plugins = (new PluginFactory())->instance($this->config, $model);
		$this->assertSame(3, count($plugins), 'Should create 3 plugin instances');

		// Now configure second instance with different config
		$plugins2 = (new PluginFactory())->instance($this->config2, $model);
		$this->assertSame(3, count($plugins2), 'Should create 3 plugin instances');

		// Default config check
		$expectedCfg = $this->config[TestModel::class];
		$expectedCfg2 = $this->config2[TestModel::class];

		$this->assertInstanceOf($expectedCfg[0], $plugins[0]);
		$this->assertInstanceOf($expectedCfg[1]['class'], $plugins[1]);
		$this->assertInstanceOf($expectedCfg[2], $plugins[2]);
		$this->assertSame($expectedCfg[1]['options'], $plugins[1]->options);

		$this->assertInstanceOf($expectedCfg2[0], $plugins2[0]);
		$this->assertInstanceOf($expectedCfg2[1]['class'], $plugins2[1]);
		$this->assertInstanceOf($expectedCfg2[2], $plugins2[2]);
		$this->assertSame($expectedCfg2[1]['options'], $plugins2[1]->options);
	}

}
