<?php

namespace Sudoku\UI\ConsoleCommand;

use Sudoku\Domain\SudokuBoard;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * class SudokuBackTrackingCommand
 */
class SudokuBackTrackingCommand extends Command
{

    /**
     * ExtractFileCommand constructor.
     */
    public function __construct( // phpcs:ignore
    ) {

        parent::__construct();
    }

    /**
     * Configure command
     */
    protected function configure(): void
    {
        $this
            ->setName('sudoku:backtracking:solve')
            ->setDescription('Solve Sudoku using backtracking')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Sudoku solve with backtracking');

        $game = new SudokuBoard(3, 3, 3);
        $game->setBoard([
            [0, 9, 0, 0, 7, 0, 0, 0, 0],
            [0, 8, 0, 5, 2, 0, 0, 1, 0],
            [5, 0, 3, 0, 0, 0, 2, 0, 0],
            [0, 0, 1, 9, 0, 0, 0, 2, 5],
            [2, 0, 8, 0, 0, 0, 7, 0, 4],
            [9, 5, 0, 0, 0, 7, 1, 0, 0],
            [0, 0, 5, 0, 0, 0, 4, 0, 6],
            [0, 4, 0, 0, 3, 8, 0, 7, 0],
            [0, 0, 0, 0, 6, 0, 0, 3, 0],
        ]);


        $a = [
            [9, 0, 0, 1, 0, 0, 0, 0, 5],
            [0, 0, 5, 0, 9, 0, 2, 0, 1],
            [8, 0, 0, 0, 4, 0, 0, 0, 0],
            [0, 0, 0, 0, 8, 0, 0, 0, 0],
            [0, 0, 0, 7, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 2, 6, 0, 0, 9],
            [2, 0, 0, 3, 0, 0, 0, 0, 6],
            [0, 0, 0, 2, 0, 0, 9, 0, 0],
            [0, 0, 1, 9, 0, 4, 5, 7, 0],
        ];

        $game->setBoard($a);

        $this->display($output, $game);
        $start = microtime(true);
        $this->solve($game, 0);
        $end = microtime(true);
        $output->writeln("\n");
        $this->display($output, $game);

        $output->write(
            sprintf("\n Temps écoulé : %d secondes", $end-$start)
        );
    }

    /**
     * @param SudokuBoard $board
     * @param int         $position
     *
     * @return bool
     */
    protected function solve(SudokuBoard $board, int $position): bool
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

    /**
     * @param OutputInterface $output
     * @param SudokuBoard     $board
     *
     * @return void
     */
    protected function display(OutputInterface $output, SudokuBoard $board): void
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
