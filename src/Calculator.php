<?php

namespace SierraKomodo\ArtilleryCalculator;


use InvalidArgumentException;

/**
 * Main class. Contains all calculation functions for artillery.
 */
class Calculator
{
    /** @var int The size of a grid sector in meters. Default value is the smallest grid size displayed in Arma 3. */
    protected int $gridSize = 100;
    
    /** @var Coordinate The origin point. */
    protected Coordinate $origin;
    
    /** @var Coordinate The target point. */
    protected Coordinate $target;
    
    
    /**
     * Artillery calculator constructor.
     *
     * @param Coordinate $origin
     * @param int        $gridSize Must be a positive integer.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Coordinate $origin, int $gridSize = 100)
    {
        $this->setOrigin($origin);
        $this->setGridSize($gridSize);
    }
    
    
    // GETTERS
    
    /**
     * Retrieves the calculator's grid size.
     *
     * @return int Always a positive integer.
     */
    public function getGridSize(): int
    {
        return $this->gridSize;
    }
    
    
    /**
     * Retrieves the calculator's origin point.
     *
     * @return Coordinate
     */
    public function getOrigin(): Coordinate
    {
        return $this->origin;
    }
    
    
    /**
     * Retrieves the calculator's target coordinate, or `false` if not target is set.
     *
     * @return Coordinate|false
     */
    public function getTarget(): Coordinate|false
    {
        if (empty($this->target)) {
            return false;
        }
        return $this->target;
    }
    
    
    // SETTERS
    
    /**
     * Set's the calculator's grid size.
     *
     * @param int $gridSize Must be a positive integer.
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setGridSize(int $gridSize): void
    {
        if ($gridSize <= 0) {
            throw new InvalidArgumentException("Grid size must be a positive integer.");
        }
        $this->gridSize = $gridSize;
    }
    
    
    /**
     * Sets the calculator's origin point.
     *
     * @param Coordinate $origin
     *
     * @return void
     */
    public function setOrigin(Coordinate $origin): void
    {
        $this->origin = $origin;
    }
    
    
    /**
     * Sets the calculator's target point.
     *
     * @param Coordinate $target
     *
     * @return void
     */
    public function setTarget(Coordinate $target): void
    {
        $this->target = $target;
    }
}
