<?php

namespace SierraKomodo\ArtilleryCalculator;


use Exception;
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
    
    
    // LOGIC
    
    /**
     * Calculates the angle from origin to target and returns the value in radians.
     *
     * @return float Angle in radians.
     * @throws Exception if target is not defined.
     * @uses \SierraKomodo\ArtilleryCalculator\Calculator::calculateHypotenuse()
     */
    protected function calculateAngle(): float
    {
        if (!$this->getTarget()) {
            throw new Exception("Attempted to calculate angle without a target.");
        }
        $differenceX = $this->differenceX();
        $differenceY = $this->differenceY();
        
        // Some quick checks for straight north, south, east, and west targets due to division by zero errors
        if ($differenceX == 0 and $differenceY == 0) {
            return 0; // Target is identical to origin, angle is irrelevant
        } elseif ($differenceX == 0) {
            if ($differenceY > 0) {
                return 0; // Target is north
            } elseif ($differenceY < 0) {
                return pi(); // Target is south
            }
        } elseif ($differenceY == 0) {
            if ($differenceX > 0) {
                return pi() / 2; // Target is east
            } elseif ($differenceX < 0) {
                return pi() * 1.5; // Target is west
            }
        }
        
        // Angle calculation using the Law of Cosines with the X difference serving as the opposing face. This ensures
        // that north equates to 0.
        $hypotenuse           = $this->calculateHypotenuse();
        $xSquared             = $differenceX ** 2;
        $hSquared             = $hypotenuse ** 2;
        $ySquared             = $differenceY ** 2;
        $negative2HY          = -2 * $hypotenuse * $differenceY;
        $addHYSquared         = $hSquared + $ySquared;
        $subtractFromXSquared = $xSquared - $addHYSquared;
        $divideBy2HY          = $subtractFromXSquared / $negative2HY;
        $inverseCosine        = acos($divideBy2HY);
        
        // If the X value is negative, subtract the result from the maximum to accomodate the above calculations only
        // working for 180 degrees instead of the full circle.
        $result = $inverseCosine;
        if ($differenceX < 0) {
            $result = pi() * 2 - $inverseCosine;
        }
        
        return $result;
    }
    
    
    /**
     * Calculates the angle from origin to target and returns the value in degrees.
     *
     * @return int Angle in degrees.
     * @throws Exception if target is not defined.
     * @uses \SierraKomodo\ArtilleryCalculator\Calculator::calculateAngle()
     */
    public function calculateAngleDegrees(): int
    {
        return round($this->calculateAngle() * 180 / pi());
    }
    
    
    /**
     * Calculates the angle from origin to target and returns the value in milliradians.
     *
     * @return int Angle in milliradians.
     * @throws Exception if target is not defined.
     * @uses \SierraKomodo\ArtilleryCalculator\Calculator::calculateAngle()
     */
    public function calculateAngleMilliradians(): int
    {
        return round($this->calculateAngle() * 1000);
    }
    
    
    /**
     * Calculates and returns the hypotenuse of a right triangle using the origin and target coordinates, where side `a`
     * is the difference between the X coordinates, and side `b` is the difference between the Y coordinates.
     *
     * @return float
     * @throws Exception if target is not defined.
     */
    protected function calculateHypotenuse(): float
    {
        if (empty($this->getTarget())) {
            throw new Exception("Attempted to calculate hypotenuse without a target.");
        }
        $differenceX = $this->differenceX();
        $differenceY = $this->differenceY();
        return sqrt($differenceX ** 2 + $differenceY ** 2);
    }
    
    
    /**
     * Calculates and returns the range between origin and target.
     *
     * @return int Non-negative integer.
     * @throws Exception
     * @todo Add support for elevation differences.
     * @uses \SierraKomodo\ArtilleryCalculator\Calculator::calculateHypotenuse()
     */
    public function calculateRange(): int
    {
        // Determine the distance between origin and target using pythagorean's theorem, where `a` is the difference in
        // X coordinates, and `b` is the difference in Y coordinates.
        $hypotenuse         = $this->calculateHypotenuse();
        $gridSizeMultiplied = $hypotenuse * $this->getGridSize(); // Multiply the result by the grid size to account for the distance between grid lines.
        return round($gridSizeMultiplied);
    }
    
    
    protected function differenceX(): int
    {
        return $this->getTarget()->getX() - $this->getOrigin()->getX();
    }
    
    
    protected function differenceY(): int
    {
        return $this->getTarget()->getY() - $this->getOrigin()->getY();
    }
}
