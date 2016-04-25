<?php
namespace PhakeBuilder\Tests;

class TemplateTest extends \PHPUnit_Framework_TestCase
{

    public function testParse()
    {
        $template = new \PhakeBuilder\Template('hello %%name%%', false);
        $result = $template->parse(['name' => 'Leonid']);

        $this->assertEquals('hello Leonid', $result, "Failed to parse template correctly");
    }

    public function testGetPlaceholders()
    {
        $template = new \PhakeBuilder\Template('hello %%name%%', false);
        $result = $template->getPlaceholders();

        $this->assertEquals(array('name'), $result, "Failed to get placeholders");
    }
}
