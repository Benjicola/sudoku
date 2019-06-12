<?php

namespace Sudoku\Infra\Resolver;

use Doctrine\Common\Collections\ArrayCollection;
use Sudoku\Domain\NumberExistsGrid;
use Sudoku\Domain\PossibilitiesGrid;
use Sudoku\Domain\SudokuBoard;

/**
 * Class BacktrackingResolver
 */
class BacktrackingResolverOptimized
{

    /**
     * Solve the Sudoku game
     * Init the environment and then solve the game
     *
     * @param SudokuBoard $board
     *
     * @return bool
     */
    public function solve(SudokuBoard $board): bool
    {
        $possibilitiesList = [];
        $numberExistsGrid = new NumberExistsGrid($board->getLineLenght(), $board->getHeightLenght());

        // init $numberExists object with the numbers on game board
        for ($line = 0; $line < $board->getLineLenght(); $line++) {
            for ($column =
                     0; $column < $board->getHeightLenght(); $column++) {
                $number = $board->getValueAt($line, $column);
                if (0 !== $number) {
                    $block = 3*intval($line/3)+intval($column/3);
                    $numberExistsGrid->setExistsOnLine($line, $number-1, true);
                    $numberExistsGrid->setExistsOnColumn($column, $number-1, true);
                    $numberExistsGrid->setExistsOnBlock($block, $number-1, true);
                }
            }
        }

        // then, find the number of possibilities for each cell of the board
        for ($line = 0; $line < $board->getLineLenght(); $line++) {
            for ($column = 0; $column < $board->getHeightLenght(); $column++) {
                $number = $board->getValueAt($line, $column);
                if (0 === $number) {
                    $possibilitiesCount = $this->findPossibilities($line, $column, $board->getLineLenght(), $numberExistsGrid);
                    $possibility = new PossibilitiesGrid($possibilitiesCount, $line, $column);
                    $possibilitiesList[] = $possibility;
                }
            }
        }

        $this->sortPossibilities($possibilitiesList);

        $possibilitiesCollection = new ArrayCollection($possibilitiesList);

        return $this->fillBoard($board, $possibilitiesCollection, $numberExistsGrid);
    }

    /**
     * Solve and fill the Sudoku game board
     *
     * @param SudokuBoard      $sudokuBoard
     * @param ArrayCollection  $possibilitiesGridCollection
     * @param NumberExistsGrid $numberExistsGrid
     *
     * @return bool
     */
    private function fillBoard(SudokuBoard $sudokuBoard, ArrayCollection $possibilitiesGridCollection, NumberExistsGrid $numberExistsGrid): bool
    {
        $possibilityGrid = $possibilitiesGridCollection->current();

        if (!$possibilityGrid) {
            return true;
        }

        $line = $possibilityGrid->getLine();
        $column = $possibilityGrid->getColumn();
        $block = 3*intval($line/3)+intval($column/3);

        for ($number = 0; $number < $sudokuBoard->getLineLenght(); $number++) {
            if (!$numberExistsGrid->checkExistence($line, $column, $block, $number)) {
                $numberExistsGrid->setExistsOnLine($line, $number, true);
                $numberExistsGrid->setExistsOnColumn($column, $number, true);
                $numberExistsGrid->setExistsOnBlock($block, $number, true);

                $clone = clone $possibilitiesGridCollection;
                $clone->next();

                if ($this->fillBoard($sudokuBoard, $clone, $numberExistsGrid)) {
                    $sudokuBoard->setValueAt($line, $column, $number+1);

                    return true;
                }

                $numberExistsGrid->setExistsOnLine($line, $number, false);
                $numberExistsGrid->setExistsOnColumn($column, $number, false);
                $numberExistsGrid->setExistsOnBlock($block, $number, false);
            }
        }

        return false;
    }

    /**
     * Find possibilities for position targeted by $line, $column, $block
     *
     * @param int              $line
     * @param int              $column
     * @param int              $boardMaxNumber
     * @param NumberExistsGrid $numberExistsGrid
     *
     * @return int
     */
    private function findPossibilities(int $line, int $column, int $boardMaxNumber, NumberExistsGrid $numberExistsGrid): int
    {
        $possibilities = 0;

        for ($number = 0; $number < $boardMaxNumber; $number++) {
            $block = 3*intval($line/3)+intval($column/3);
            if (!$numberExistsGrid->checkExistence($line, $column, $block, $number)) {
                $possibilities++;
            }
        }

        return $possibilities;
    }

    /**
     * Sort possibilities array, ASC order
     *
     * @param PossibilitiesGrid[] $data
     */
    private function sortPossibilities(array $data): void
    {
        uasort($data, function (PossibilitiesGrid $a, PossibilitiesGrid $b) {

            if ($a->getPossibility() === $b->getPossibility()) {
                return 0;
            }

            return ($a->getPossibility() < $b->getPossibility()) ? 1 : -1;
        });
    }
}
