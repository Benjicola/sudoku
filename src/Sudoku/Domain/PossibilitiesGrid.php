<?php

namespace Sudoku\Domain;

/**
 * Class PossibilitiesGrid
 */
class PossibilitiesGrid
{
    /** @var int */
    private $possibility;

    /** @var bool[] */
    private $line;

    /** @var bool[] */
    private $column;

    /**
     * PossibilitiesGrid constructor.
     *
     * @param int $possibility
     * @param int $line
     * @param int $column
     */
    public function __construct(int $possibility, int $line, int $column)
    {
        $this->possibility = $possibility;
        $this->line = $line;
        $this->column = $column;
    }

    /**
     * @return int
     */
    public function getPossibility(): int
    {
        return $this->possibility;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
