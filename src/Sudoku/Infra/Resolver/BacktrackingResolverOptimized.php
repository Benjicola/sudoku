<?php

namespace Sudoku\Infra\Resolver;

use Doctrine\Common\Collections\ArrayCollection;
use Sudoku\Domain\NumberExists;
use Sudoku\Domain\PossibilitiesGrid;
use Sudoku\Domain\SudokuBoard;

/**
 * Class BacktrackingResolver
 */
class BacktrackingResolverOptimized
{

    public function solve(SudokuBoard $board)
    {
        $possibilitiesList = [];
        $numberExists = new NumberExists($board->getLineLenght(), $board->getHeightLenght());

        for ($line=0; $line < $board->getLineLenght(); $line++) {
            for ($column=0; $column < $board->getHeightLenght(); $column++) {
                $number = $board->getValueAt($line, $column);
                // Enregistre dans les tableaux toutes les valeurs déjà présentes
                if (0 != $number) {
                    $block = 3*intval($line/3)+intval($column/3);
                    $numberExists->setExistsOnLine($line, $number-1, true);
                    $numberExists->setExistsOnColumn($column, $number-1, true);
                    $numberExists->setExistsOnBlock($block, $number-1, true);

                    // crée et remplit une liste pour les cases vides à visiter
                } else {
                    $possibilitiesCount = $this->findPossibilities($line, $column, $board->getLineLenght(), $numberExists);
                    $possibility = new PossibilitiesGrid($possibilitiesCount, $line, $column);
                    $possibilitiesList[] = $possibility;
                }
            }
        }

        $this->sortPossibilities($possibilitiesList);

        $possibilitiesCollection = new ArrayCollection($possibilitiesList);

        return $this->fillBoard($board, $possibilitiesCollection, $numberExists);
    }

    private function fillBoard(SudokuBoard $sudokuBoard, ArrayCollection $possibilitiesGrid, NumberExists $numberExists)
    {
        $line = $possibilitiesGrid->current()->getLine();
        $column = $possibilitiesGrid->current()->getColumn();

        for ($number = 0; $number < $sudokuBoard->getLineLenght(); $number++) {
            $block = 3*intval($line/3)+intval($column/3);

            if (!$numberExists->checkExistence($line, $column, $block, $number)) {
                $numberExists->setExistsOnLine($line, $number, true);
                $numberExists->setExistsOnColumn($column, $number, true);
                $numberExists->setExistsOnBlock($block, $number, true);

                if ($this->fillBoard($sudokuBoard, $possibilitiesGrid->next(), $numberExists)) {
                    $sudokuBoard->setValueAt($line, $column, $number+1);

                    return true;
                }

                $numberExists->setExistsOnLine($line, $number, false);
                $numberExists->setExistsOnColumn($column, $number, false);
                $numberExists->setExistsOnBlock($block, $number, false);
            }
        }

        return false;
    }

    private function findPossibilities(int $line, int $column, int $boardMaxNumber, NumberExists $numberExists)
    {
        $possibilities = 0;

        for ($number=0; $number < $boardMaxNumber; $number++) {
            $block = 3*intval($line/3)+intval($column/3);
            if (!$numberExists->checkExistence($line, $column, $block, $number)) {
                $possibilities++;
            }
        }

        return $possibilities;
    }

    private function sortPossibilities(array $data)
    {
        uasort($data, function (PossibilitiesGrid $a, PossibilitiesGrid $b) {

            if ($a->getPossibility() == $b->getPossibility()) {
                return 0;
            }
            return ($a->getPossibility() < $b->getPossibility()) ? 1 : -1;
        });
    }
}
