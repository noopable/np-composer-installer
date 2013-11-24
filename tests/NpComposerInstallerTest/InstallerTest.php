<?php

namespace NpComposerInstallerTest;

use NpComposerInstaller\Installer;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-27 at 17:57:59.
 */
class InstallerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Installer
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $io = new \Composer\IO\NullIO;
        $composer = new \Composer\Composer;
        $config = new \Composer\Config;
        $composer->setConfig($config);
        $this->object = new Installer($io, $composer);
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    /**
     * @covers NpComposerInstaller\Installer::supports
     * @dataProvider providesType
     */
    public function testSupports($type, $expects)
    {
        $res = $this->object->supports($type);
        if ($expects) {
            $this->assertTrue($res);
        }
        else {
            $this->assertFalse($res);
        }
    }
    
    /**
     * @covers NpComposerInstaller\Installer::getInstallPath
     * @dataProvider providesPackageMock
     */
    public function testGetInstallPath(array $extra, $type, $expects)
    {
        $package = $this->getMock('Composer\Package\PackageInterface');
        $package->expects($this->any())
                ->method('getExtra')
                ->will($this->returnValue($extra));
        
        $package->expects($this->any())
                ->method('getPrettyName')
                ->will($this->returnValue('vendorName/packageName'));
        
        $package->expects($this->any())
                ->method('getType')
                ->will($this->returnValue($type));
        
        $res = $this->object->getInstallPath($package);
        $this->assertEquals($expects, $res);
    }

    public function providesType()
    {
        return array(
            array('np-local-public', true),
            array('np-local-module', true),
            array('np-local-config', true),
            array('np-local-data', true),
            array('np-local', false),
            array('np-local-cache', false),
            array('wordpress-plugin', false),
        );
    }
    public function providesPackageMock()
    {
        return array(
            array(array(), 'np-local-public', 'public\packageName'),
            array(array(), 'np-local-module', 'module\packageName'),
            array(array(), 'np-local-config', 'config\packageName'),
            array(array(), 'np-local-data', 'data\packageName'),
            array(array('target_dir' => 'foo'), 'np-local-module', 'module\packageName\foo'),
        );
    }

}
