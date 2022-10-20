<?php

namespace SierraKomodo\ArtilleryCalculator\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SierraKomodo\ArtilleryCalculator\Calculator;
use SierraKomodo\ArtilleryCalculator\Coordinate;

/**
 * @covers \SierraKomodo\ArtilleryCalculator\Calculator
 * @coversDefaultClass \SierraKomodo\ArtilleryCalculator\Calculator
 */
final class CalculatorTest extends TestCase
{
    private Coordinate $coordinate;
    private Calculator $calculator;
    
    
    public function setUp(): void
    {
        $this->coordinate = new Coordinate(1, 1);
        $this->calculator = new Calculator($this->coordinate);
    }
    
    
    public function tearDown(): void
    {
        unset($this->coordinate, $this->calculator);
    }
    
    
    /**
     * @covers ::__construct
     */
    public function testConstructorGettersDefaultValues(): void
    {
        $this->assertSame($this->coordinate, $this->calculator->getOrigin());
        $this->assertSame(100, $this->calculator->getGridSize());
    }
    
    
    /**
     * @covers ::__construct
     */
    public function testConstructorGettersNonDefaultValues(): void
    {
        $this->calculator = new Calculator($this->coordinate, 500);
        $this->assertSame($this->coordinate, $this->calculator->getOrigin());
        $this->assertSame(500, $this->calculator->getGridSize());
    }
    
    
    /**
     * @covers ::setGridSize
     */
    public function testSetGridSize(): void
    {
        $this->calculator->setGridSize(150);
        $this->assertSame(150, $this->calculator->getGridSize());
    }
    
    
    public function setGridSizeInvalidValuesProvider(): array
    {
        return [
            'zero'     => [0],
            'negative' => [-1],
        ];
    }
    
    
    /**
     * @covers ::setGridSize
     * @dataProvider setGridSizeInvalidValuesProvider
     */
    public function testSetGridSizeBlocksInvalidValues(int $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->calculator->setGridSize($value);
    }
    
    
    /**
     * @covers ::setOrigin
     */
    public function testSetOrigin(): void
    {
        $newCoordinate = new Coordinate(10, 10);
        $this->calculator->setOrigin($newCoordinate);
        $this->assertSame($newCoordinate, $this->calculator->getOrigin());
    }
    
    
    /**
     * @covers ::getTarget
     */
    public function testGetTargetWithNoTarget(): void
    {
        $this->assertFalse($this->calculator->getTarget());
    }
    
    
    /**
     * @covers ::setTarget
     * @covers ::getTarget
     */
    public function testSetTargetGetTarget(): void
    {
        $newTarget = new Coordinate(10, 10);
        $this->calculator->setTarget($newTarget);
        $this->assertSame($newTarget, $this->calculator->getTarget());
    }
}
