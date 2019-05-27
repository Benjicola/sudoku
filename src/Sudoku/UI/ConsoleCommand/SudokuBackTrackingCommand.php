<?php

namespace Sudoku\UI\ConsoleCommand;

use Sudoku\Domain\SudokuBoard;
use Sudoku\Infra\Resolver\BacktrackingResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    /** @var string */
    private $boardsDirectory;

    private $levels = [
        'easy',
        'medium',
        'hard'
    ];

    /**
     * ExtractFileCommand constructor.
     *
     * @param ConsoleDisplay $consoleDisplay
     * @param BacktrackingResolver $resolver
     */
    public function __construct( // phpcs:ignore
        ConsoleDisplay $consoleDisplay,
        BacktrackingResolver $resolver,
        string $boardsDirectory
    ) {
        $this->consoleDisplay = $consoleDisplay;
        $this->resolver = $resolver;
        $this->boardsDirectory = $boardsDirectory;

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
            ->addOption('level', 'lvl',  InputOption::VALUE_OPTIONAL, 'easy, medium or hard?', 'easy')
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
        $level = $input->getOption('level');
        if (!in_array($level, $this->levels)) {
            $level = 'easy';
        }

        $boards = json_decode(file_get_contents($this->boardsDirectory.$level.'.json'), true);
        $randKeys = array_rand($boards);
        $currentGoard = $boards[$randKeys];

        $output->writeln(
            sprintf('Sudoku solve with backtracking - level %s',
                $level
            )
        );

        $game = new SudokuBoard(3, 3, 3);
        $game->setBoard($currentGoard);

        $this->consoleDisplay->display($output, $game);
        $start = microtime(true);
        $this->resolver->solve($game, 0);
        $end = microtime(true);
        $output->writeln("\n");
        $this->consoleDisplay->display($output, $game);

        $output->writeln(
            sprintf("\n Temps écoulé : %d secondes", $end-$start)
        );
    }
}
