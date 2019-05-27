<?php

namespace Sudoku\Infra\Resolver;

use Sudoku\Domain\SudokuBoard;

class BacktrackingResolver
{
    /**
     * @param SudokuBoard $board
     * @param int         $position
     *
     * @return bool
     */
    public function solve(SudokuBoard $board, int $position): bool
    {
        if ($board->getCellsCount() === $position) {
            return true;
        }

        $line = intval($position/$board->getLineLenght());
        $column = intval($position%$board->getHeightLenght());

        if (0 !== $board->getValueAt($line, $column)) {
            return $this->solve($board, $position + 1);
        }

        for ($number = 1; $number <= $board->getLineLenght(); $number++) {
            if (!$board->hasNumberOnLine($number, $line) && !$board->hasNumberOnColumn($number, $column) && !$board->hasNumberOnblock($number, $line, $column)) {
                $board->setValueAt($line, $column, $number);
                if ($this->solve($board, $position+1)) {
                    return true;
                }
            }
        }

        $board->setValueAt($line, $column, 0);

        return false;
    }
}