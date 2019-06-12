<?php

namespace Sudoku\Domain;

/**
 * Class NumberExistsGrid
 */
class NumberExistsGrid
{
    /** @var bool[][] */
    private $existsOnLine = [];

    /** @var bool[][] */
    private $existsOnColumn = [];

    /** @var bool[]v */
    private $existsOnBlock = [];

    /**
     * NumberExists constructor.
     *
     * @param int $lineLength
     * @param int $columnLength
     */
    public function __construct(int $lineLength, int $columnLength)
    {
        for ($i = 0; $i < $lineLength; $i++) {
            for ($j = 0; $j < $columnLength; $j++) {
                $this->existsOnLine[$i][$j] = false;
                $this->existsOnColumn[$i][$j] = false;
                $this->existsOnBlock[$i][$j] = false;
            }
        }
    }

    /**
     * @param int  $line
     * @param int  $number
     * @param bool $exists
     */
    public function setExistsOnLine(int $line, int $number, bool $exists): void
    {
        $this->existsOnLine[$line][$number] = $exists;
    }

    /**
     * @param int  $column
     * @param int  $number
     * @param bool $exists
     */
    public function setExistsOnColumn(int $column, int $number, bool $exists): void
    {
        $this->existsOnColumn[$column][$number] = $exists;
    }

    /**
     * @param int  $block
     * @param int  $number
     * @param bool $exists
     */
    public function setExistsOnBlock(int $block, int $number, bool $exists): void
    {
        $this->existsOnBlock[$block][$number] = $exists;
    }

    /**
     * Check if $number exists on $line or $column, or $block
     *
     * @param int $line
     * @param int $column
     * @param int $block
     * @param int $number
     *
     * @return bool
     */
    public function checkExistence(int $line, int $column, int $block, int $number): bool
    {
        if (true === $this->existsOnLine[$line][$number] || true === $this->existsOnColumn[$column][$number] || true === $this->existsOnBlock[$block][$number]) {
            return true;
        }

        return false;
    }
}
