<?php

namespace Sudoku\Tests\Units\Domain;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use Sudoku\Domain\Exception\OutOfBoardBoundsExceptionException;
use Sudoku\Domain\SudokuBoard;

/**
 * Class SudokuBoardProphecyTest
 *
 * @group prophecy
 */
class SudokuBoardProphecyTest extends TestCase
{
    /** @var Prophet */
    private $prophet;

    /**
     * Build the Sudoku board game
     */
    protected function setUp(): void
    {
        $this->prophet = new Prophet;
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }



    public function testPasswordHashing()
    {
        $board = $this->prophet->prophesize(SudokuBoard::class);

        $board->getLineLenght()->willReturn(9);
        $test = $board->reveal();

        $this->assertEquals(9, $test->getLineLenght());
    }





    /**
     * Set up the board
     */
    private function buildSut(): void
    {
        $board = json_decode('[
                [0, 0, 9, 0, 5, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 7, 9, 6],
                [4, 3, 7, 6, 0, 0, 5, 1, 0],
                [9, 7, 0, 0, 4, 8, 0, 0, 2],
                [3, 0, 0, 1, 0, 9, 0, 0, 5],
                [1, 0, 0, 3, 2, 0, 0, 4, 7],
                [0, 9, 8, 0, 0, 7, 4, 5, 1],
                [5, 1, 2, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 1, 0, 2, 0, 0]
        ]');

        $this->sut = new SudokuBoard(3, 3, 3);
        $this->sut->setBoard($board);
    }
}
