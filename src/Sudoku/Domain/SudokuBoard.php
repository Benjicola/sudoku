<?php

namespace Sudoku\Domain;

/**
 * Class SudokuBoard
 */
class SudokuBoard
{
    /** @var int[] */
    private $board = [];

    /** @var int */
    private $blockHeight;

    /** @var int */
    private $blockLength;

    /** @var int */
    private $blockNumber;

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
     * @param int[] $game
     */
    public function setBoard(array $game): void
    {
        $this->board = $game;
    }

    /**
     * Return true is $number is already on the line number $line
     *
     * @param int $number
     * @param int $line
     *
     * @return bool
     */
    public function hasNumberOnLine(int $number, int $line): bool
    {
        for ($j = 0; $j < ($this->blockLength * $this->blockNumber); $j++) {
            if ($number === $this->board[$line][$j]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return true is $number is already on the column number $column
     *
     * @param int $number
     * @param int $column
     *
     * @return bool
     */
    public function hasNumberOnColumn(int $number, int $column): bool
    {
        for ($i = 0; $i < ($this->blockHeight * $this->blockNumber); $i++) {
            if ($number === $this->board[$i][$column]) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return true is $number is already inside the block to which belong $line an d $column
     *
     * @param int $number
     * @param int $line
     * @param int $column
     *
     * @return bool
     */
    public function hasNumberOnblock(int $number, int $line, int $column): bool
    {
        $blockLineIndex = $line - ($line % 3);
        $blockColumnIndex = $column - ($column % 3);  // ou encore : _i = 3*(i/3), _j = 3*(j/3);
        for ($i = $blockLineIndex; $i < ($blockLineIndex + $this->blockLength); $i++) {
            for ($j = $blockColumnIndex; $j < ($blockColumnIndex + $this->blockHeight); $j++) {
                if ($number === $this->board[$i][$j]) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     *  Number of cells in the board
     *
     * @return int
     */
    public function getCellsCount(): int
    {
        return ($this->blockLength * $this->blockNumber) * ($this->blockHeight * $this->blockNumber);
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
     * @return SudokuBoard
     */
    private function initEmptyBoard(): SudokuBoard
    {
        for ($i = 0; $i < ($this->blockLength * $this->blockNumber); $i++) {
            for ($j = 0; $j < ($this->blockHeight * $this->blockNumber); $j++) {
                $this->setValueAt($i, $j, 0);
            }
        }

        return $this;
    }
}
