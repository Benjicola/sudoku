<?php

namespace Sudoku\Domain;

/**
 * Class NumberExists
 */
class NumberExists
{
    private $existsOnLine = [];
    private $existsOnColumn = [];
    private $existsOnBlock = [];

    public function __construct(int $lineLength, int $columnLength)
    {
        for ($i=0; $i < $lineLength; $i++) {
            for ($j = 0; $j < $columnLength; $j++) {
                $this->existsOnLine[$i][$j] = false;
                $this->existsOnColumn[$i][$j] = false;
                $this->existsOnBlock[$i][$j] = false;
            }
        }
    }

    public function setExistsOnLine(int $line, int $number, bool $exists)
    {
        $this->existsOnLine[$line][$number] = $exists;
    }

    public function setExistsOnColumn(int $column, int $number, bool $exists)
    {
        $this->existsOnColumn[$column][$number] = $exists;
    }

    public function setExistsOnBlock(int $block, int $number, bool $exists)
    {
        $this->existsOnBlock[$block][$number] = $exists;
    }

    public function checkExistence(int $line, int $column, int $block, int $number)
    {
        if (true === $this->existsOnLine[$line][$number] && true === $this->existsOnColumn[$column][$number] && true === $this->existsOnBlock[$block][$number]) {
            return true;
        }

        return false;
    }
}