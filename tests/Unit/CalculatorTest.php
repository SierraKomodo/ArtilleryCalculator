<?php

namespace SierraKomodo\ArtilleryCalculator\Tests\Unit;

use Exception;
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
    
    
    public function calculateAngleMilliradianProvider(): array
    {
        return [
            'equal to origin' => [1, 1, 0],
            'north'           => [1, 2, 0],
            'south'           => [1, 0, 3142],
            'east'            => [2, 1, 1571],
            'west'            => [0, 1, 4712],
            'northeast'       => [2, 2, 785],
            'northwest'       => [0, 2, 5498],
            'southeast'       => [2, 0, 2356],
            'southwest'       => [0, 0, 3927],
        ];
    }
    
    
    /**
     * @covers ::calculateAngle ::calculateAngleMilliradians
     * @dataProvider calculateAngleMilliradianProvider
     */
    public function testCalculateAngleMilliradian(int $targetX, int $targetY, int $expectedMilliradians): void
    {
        $target = new Coordinate($targetX, $targetY);
        $this->calculator->setTarget($target);
        $this->assertSame($expectedMilliradians, $this->calculator->calculateAngleMilliradians());
    }
    
    
    public function calculateAngleDegreesProvider(): array
    {
        return [
            'equal to origin' => [1, 1, 0],
            'north'           => [1, 2, 0],
            'south'           => [1, 0, 180],
            'east'            => [2, 1, 90],
            'west'            => [0, 1, 270],
            'northeast'       => [2, 2, 45],
            'northwest'       => [0, 2, 315],
            'southeast'       => [2, 0, 135],
            'southwest'       => [0, 0, 225],
        ];
    }
    
    
    /**
     * @covers ::calculateAngle ::calculateAngleDegrees
     * @dataProvider calculateAngleDegreesProvider
     */
    public function testCalculateAngleDegrees(int $targetX, int $targetY, int $expectedDegrees): void
    {
        $target = new Coordinate($targetX, $targetY);
        $this->calculator->setTarget($target);
        $this->assertSame($expectedDegrees, $this->calculator->calculateAngleDegrees());
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
    
    
    /**
     * @covers ::calculateRange
     */
    public function testCalculateRangeWithNoTarget(): void
    {
        $this->expectException(Exception::class);
        $this->calculator->calculateRange();
    }
    
    
    public function calculateRangeProvider(): array
    {
        return [
            'equal to origin'     => [1, 1, 0],
            'east of origin'      => [2, 1, 100],
            'north of origin'     => [1, 2, 100],
            'west of origin'      => [0, 1, 100],
            'south of origin'     => [1, 0, 100],
            'northeast of origin' => [2, 2, 141],
            'northwest of origin' => [0, 2, 141],
            'southeast of origin' => [2, 0, 141],
            'southwest of origin' => [0, 0, 141],
            'negative target'     => [-1, -1, 283],
        ];
    }
    
    
    /**
     * @covers ::calculateRange
     * @dataProvider calculateRangeProvider
     */
    public function testCalculateRange(int $targetX, int $targetY, int $expectedRange): void
    {
        $target = new Coordinate($targetX, $targetY);
        $this->calculator->setTarget($target);
        $this->assertSame($expectedRange, $this->calculator->calculateRange());
    }
    
    
    /**
     * @covers ::calculateRange
     * @dataProvider calculateRangeProvider
     */
    public function testCalculateRangeWithSmallerGridSize(int $targetX, int $targetY, int $expectedRange): void
    {
        $target        = new Coordinate($targetX, $targetY);
        $expectedRange = (int)round($expectedRange / 10); // Typecasting is needed for assertion to pass (`1.0` vs `1`).
        $this->calculator->setGridSize(10);
        $this->calculator->setTarget($target);
        $this->assertSame($expectedRange, $this->calculator->calculateRange());
    }
}
