<?php
namespace Sudoku\UI\ConsoleCommand;

use Sudoku\Domain\SudokuBoard;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleDisplay
 */
class ConsoleDisplay
{
    /**
     * @param OutputInterface $output
     * @param SudokuBoard     $board
     *
     * @return void
     */
    public function display(OutputInterface $output, SudokuBoard $board): void
    {
        $output->writeln("\n");
        for ($i = 0; $i < ($board->getLineLenght() * 2); $i++) {
            $output->write('-');
        }

        $output->writeln("");

        for ($i = 0; $i < $board->getLineLenght(); $i++) {
            for ($j = 0; $j < $board->getHeightLenght(); $j++) {
                $output->write(
                    sprintf((($j+1)%3) ? "%d " : "%d|", $board->getValueAt($i, $j))
                );
            }
            $output->writeln("");
            if (!(($i+1)%$board->getBlockLength())) {
                for ($k = 0; $k < ($board->getLineLenght() * 2); $k++) {
                    $output->write('-');
                }
                $output->writeln('');
            }
        }
        $output->writeln("\n");
    }
}