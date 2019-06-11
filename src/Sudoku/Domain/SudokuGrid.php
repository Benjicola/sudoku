<?php

namespace Sudoku\Domain;

/**
 * Class SudokuGrid
 */
class SudokuGrid
{
    /** @var int[] */
    private $board = [];

    /** @var int */
    private $blockHeight;

    /** @var int */
    private $blockLength;

    /** @var int */
    private $blockNumber;

    /** @var int */
    private $currentLine;

    /** @var int */
    private $currentColumn;


    /**
     * SudokuBoard constructor.
     *
     * @param int $blockHeight
     * @param int $blockLength
     * @param int $blockNumber
     */
    public function __construct(int $blockHeight, int $blockLength, int $blockNumber)
    {
        $this->blockLength = $blockLength;
        $this->blockHeight = $blockHeight;
        $this->blockNumber = $blockNumber;
        $this->currentLine = 0;
        $this->currentColumn = 0;

        $this->initEmptyBoard();
    }

    /**
     * @return int
     */
    public function getBlockHeight(): int
    {
        return $this->blockHeight;
    }

    /**
     * @return int
     */
    public function getBlockLength(): int
    {
        return $this->blockLength;
    }

    /**
     * @return int
     */
    public function getBlockNumber(): int
    {
        return $this->blockNumber;
    }

    /**
     * @return int
     */
    public function getLineLenght(): int
    {
        return $this->blockLength * $this->blockNumber;
    }

    /**
     * @return int
     */
    public function getHeightLenght(): int
    {
        return $this->blockHeight * $this->blockNumber;
    }

    /**
     * @return int
     */
    public function getCurrentLine(): int
    {
        return $this->currentLine;
    }

    /**
     * @return int
     */
    public function getCurrentColumn(): int
    {
        return $this->currentColumn;
    }

    /**
     * @param int $line
     * @param int $column
     *
     * @return int
     */
    public function getValueAt(int $line, int $column): int
    {
        return $this->board[$line][$column];
    }

    /**
     * @param int $line
     * @param int $column
     * @param int $number
     */
    public function setValueAt(int $line, int $column, int $number): void
    {
        $this->board[$line][$column] = $number;
    }

    /**
     * Init an empty board game
     *
     * @return SudokuGrid
     */
    private function initEmptyBoard(): SudokuGrid
    {
        for ($i = 0; $i < ($this->blockLength * $this->blockNumber); $i++) {
            for ($j = 0; $j < ($this->blockHeight * $this->blockNumber); $j++) {
                $this->setValueAt($i, $j, 0);
            }
        }

        return $this;
    }
}
