<?php

namespace SierraKomodo\ArtilleryCalculator\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SierraKomodo\ArtilleryCalculator\Coordinate;

/**
 * @coversDefaultClass \SierraKomodo\ArtilleryCalculator\Coordinate
 */
final class CoordinateTest extends TestCase
{
    private Coordinate $coordinate;
    
    
    const X_COORDINATE = 5;
    const Y_COORDINATE = 10;
    
    
    public function setUp(): void
    {
        $this->coordinate = new Coordinate(self::X_COORDINATE, self::Y_COORDINATE);
    }
    
    
    /**
     * @covers ::__construct
     */
    public function testClassConstructor(): void
    {
        $this->assertSame(self::X_COORDINATE, $this->coordinate->getX());
        $this->assertSame(self::Y_COORDINATE, $this->coordinate->getY());
    }
    
    
    /**
     * @covers ::getCoordinates
     */
    public function testGetCoordinates(): void
    {
        $this->assertSame(['x' => self::X_COORDINATE, 'y' => self::Y_COORDINATE], $this->coordinate->getCoordinates());
    }
    
    
    /**
     * @covers ::getName
     */
    public function testGetName(): void
    {
        $x = self::X_COORDINATE;
        $y = self::Y_COORDINATE;
        $this->assertSame("({$x}, {$y})", $this->coordinate->getName());
    }
    
    
    /**
     * @covers ::getX
     */
    public function testGetX(): void
    {
        $this->assertSame(self::X_COORDINATE, $this->coordinate->getX());
    }
    
    
    /**
     * @covers ::getY
     */
    public function testGetY(): void
    {
        $this->assertSame(self::Y_COORDINATE, $this->coordinate->getY());
    }
}
