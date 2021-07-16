<?php

use App\Container;

class ContainerTest extends TestCase
{
    /**
     * @test
     */
    public function binds_single_item()
    {
        Container::bind('date', new DateTime());

        $this->assertInstanceOf(DateTime::class, Container::get('date'));
    }

    /**
     * @test
     */
    public function binds_multiple_items()
    {
        Container::bind([
            'date'      => new DateTime(),
            'directory' => new DirectoryIterator(__DIR__)
        ]);

        // echo PHP_EOL;
        // echo PHP_EOL;
        // echo PHP_EOL;
        // var_dump(Container::get('date'));
        // echo PHP_EOL;
        // echo PHP_EOL;
        // echo PHP_EOL;

        $this->assertInstanceOf(DateTime::class, Container::get('date'));
        $this->assertInstanceOf(DirectoryIterator::class, Container::get('directory'));
    }
}
