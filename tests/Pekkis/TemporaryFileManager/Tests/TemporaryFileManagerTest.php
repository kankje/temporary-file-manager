<?php

namespace Pekkis\TemporaryFileManager\Tests;

use Pekkis\TemporaryFileManager\TemporaryFileManager;
use Symfony\Component\Filesystem\Filesystem;

class TemporaryFileManagerTest extends \PHPUnit_Framework_TestCase
{
    private $root;

    public function setUp()
    {
        $this->root = realpath(__DIR__ . '/../../../data');
    }

    public function tearDown()
    {
        (new Filesystem())->remove(glob($this->root . '/*'));
    }

    /**
     * @test
     */
    public function initializes()
    {
        $fm = new TemporaryFileManager($this->root . '/tussi', 0700);
        $this->assertTrue(is_dir($this->root . '/tussi'));
        $this->assertEquals('0700', substr(sprintf('%o', fileperms($this->root . '/tussi')), -4));
    }

    /**
     * @test
     */
    public function adds()
    {
        $manatee = __DIR__ . '/../../../self-lussing-manatee.jpg';
        $fm = new TemporaryFileManager($this->root . '/tussi', 0700);
        $ret = $fm->add(file_get_contents($manatee));

        $this->assertStringStartsWith($this->root, $ret);
        $this->assertFileEquals($manatee, $ret);
    }

    /**
     * @test
     */
    public function addsFile()
    {
        $manatee = __DIR__ . '/../../../self-lussing-manatee.jpg';
        $fm = new TemporaryFileManager($this->root . '/tussi', 0700);
        $ret = $fm->addFile($manatee);


        $this->assertStringStartsWith($this->root, $ret);
        $this->assertFileEquals($manatee, $ret);
    }

    /**
     * @test
     */
    public function cleansUp()
    {
        $manatee = __DIR__ . '/../../../self-lussing-manatee.jpg';
        $fm = new TemporaryFileManager($this->root . '/tussi', 0700);
        $ret = $fm->addFile($manatee);
        $ret2 = $fm->addFile($manatee);

        $this->assertNotEquals($ret, $ret2);
        $this->assertCount(2, glob($this->root . '/tussi/*'));

        unset($fm);
        $this->assertCount(0, glob($this->root . '/tussi/*'));
    }
}