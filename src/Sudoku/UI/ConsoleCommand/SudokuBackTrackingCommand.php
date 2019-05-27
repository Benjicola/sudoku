<?php

namespace Sudoku\UI\ConsoleCommand;

use Sudoku\Domain\SudokuBoard;
use Sudoku\Infra\Resolver\BacktrackingResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * class SudokuBackTrackingCommand
 */
class SudokuBackTrackingCommand extends Command
{
    /** @var ConsoleDisplay */
    private $consoleDisplay;

    /** @var BacktrackingResolver */
    private $resolver;

    /**
     * ExtractFileCommand constructor.
     *
     * @param ConsoleDisplay $consoleDisplay
     * @param BacktrackingResolver $resolver
     */
    public function __construct( // phpcs:ignore
        ConsoleDisplay $consoleDisplay,
        BacktrackingResolver $resolver
    ) {
        $this->consoleDisplay = $consoleDisplay;
        $this->resolver = $resolver;

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

        //$game->setBoard($game);

        $this->consoleDisplay->display($output, $game);
        $start = microtime(true);
        $this->resolver->solve($game, 0);
        $end = microtime(true);
        $output->writeln("\n");
        $this->consoleDisplay->display($output, $game);

        $output->write(
            sprintf("\n Temps écoulé : %d secondes", $end-$start)
        );
    }
}
