<?php

namespace SierraKomodo\ArtilleryCalculator;


/**
 * Individual coordinates.
 */
class Coordinate
{
    /** @var int $x X-axis coordinate. Intentionally immutable after construct. */
    protected int $x;
    
    /** @var int $y Y-axis coordinate. Intentionally immutable after construct. */
    protected int $y;
    
    
    /**
     * Coordinate constructor.
     *
     * @param int $x X-axis coordinate.
     * @param int $y Y-axis coordinate.
     */
    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
    
    
    // GETTERS
    /**
     * Retrieves the X and Y coordinates as an associative array.
     *
     * @return array{x: int, x: int}
     */
    public function getCoordinates(): array
    {
        return ['x' => $this->getX(), 'y' => $this->getY()];
    }
    
    
    /**
     * Auto-generates a name using the coordinate's x/y values in the format `(X, Y)`.
     *
     * @return string
     */
    public function getName(): string
    {
        return "({$this->getX()}, {$this->getY()})";
    }
    
    
    /**
     * Retrieves the coordinate's X value.
     *
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }
    
    
    /**
     * Retrieves the coordinate's Y value.
     *
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }
}
