<?php

namespace Alt\Test;

use Alt\Alt;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Alt\Alt
 */
class AltTest extends TestCase
{
    /** @var Alt */
    protected $alt;

    /**
     * Prepare vars.
     *
     * @return void
     */
    public function setUp()
    {
        $this->alt = new Alt(__DIR__ . '/images/people.jpg', 'unclassified', 'Image contain: ');
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::setImage
     * @covers ::getImage
     */
    public function it_sets_and_gets_image_path()
    {
        $this->assertEquals(__DIR__ . '/images/people.jpg', $this->alt->getImage());

        $this->alt->setImage('images/dog.jpg');

        $this->assertEquals('images/dog.jpg', $this->alt->getImage());
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::setDefaultText
     * @covers ::getDefaultText
     */
    public function it_sets_and_gets_default_text()
    {
        $this->assertEquals('unclassified', $this->alt->getDefaultText());

        $this->alt->setDefaultText('cannot classify');

        $this->assertEquals('cannot classify', $this->alt->getDefaultText());
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::setPrefix
     * @covers ::getPrefix
     */
    public function it_sets_and_gets_prefix()
    {
        $this->assertEquals('Image contain: ', $this->alt->getPrefix());

        $this->alt->setPrefix('prefix');

        $this->assertEquals('prefix', $this->alt->getPrefix());
    }

    /**
     * @test
     *
     * @covers ::setThreshold
     * @covers ::getThreshold
     */
    public function it_sets_and_gets_threshold()
    {
        $this->assertEquals(30, $this->alt->getThreshold());

        $this->alt->setThreshold(40);

        $this->assertEquals(40, $this->alt->getThreshold());
    }

    /**
     * @test
     *
     * @covers ::setCountable
     * @covers ::getCountable
     */
    public function it_sets_and_gets_countable()
    {
        $this->assertEquals(['person'], $this->alt->getCountable());

        $this->alt->setCountable(['person', 'chair']);

        $this->assertEquals(['person', 'chair'], $this->alt->getCountable());
    }

    /**
     * @test
     *
     * @covers ::alt
     */
    public function it_generates_alt()
    {
        $this->assertEquals('Image contain: 6 person, cup, laptop', $this->alt->alt());

        $this->alt->setImage(__DIR__ . '/images/pizza.jpg')->setPrefix('Objects - ');

        $this->assertEquals('Objects - pizza, dining table', $this->alt->alt());

        $this->alt->setImage(__DIR__ . '/images/car.png');

        $this->assertEquals('Objects - car', $this->alt->alt());
    }

    /**
     * @test
     *
     * @covers ::alt
     */
    public function it_displays_error_if_image_size_is_less_than_300x300()
    {
        $this->alt->setImage(__DIR__ . '/images/small.jpg');

        $this->assertEquals('Image contain: dog', $this->alt->alt());
    }

    /**
     * @test
     *
     * @covers ::arrange
     */
    public function it_gets_unique_objects_with_count()
    {
        $this->assertEquals([], $this->alt->arrange([]));

        $objects = ['person', 'person', 'person', 'car', 'car', 'dog'];

        $this->assertEquals(['person' => 3, 'car' => 2, 'dog' => 1], $this->alt->arrange($objects));
    }

    /**
     * @test
     *
     * @covers ::getObjects
     */
    public function it_gets_objects()
    {
        $this->assertEquals(
            ['person', 'person', 'person', 'person', 'person', 'person', 'cup', 'laptop'],
            $this->alt->getObjects()
        );
    }
}
