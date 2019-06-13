<?php

namespace Sudoku\Tests\Units\Domain;

use PHPUnit\Framework\TestCase;
use Sudoku\Domain\Exception\OutOfBoardBoundsExceptionException;
use Sudoku\Domain\SudokuBoard;

/**
 * Class SudokuBoardTest
 *
 * @group board
 */
class SudokuBoardTest extends TestCase
{
    /** @var SudokuBoard */
    private $sut;

    /**
     * Build the Sudoku board game
     */
    protected function setUp(): void
    {
        $this->buildSut();
    }

    /**
     * Test hasNumberOnLine
     *
     * @dataProvider hasNumberOnLineDataProvider
     *
     * @param int  $number
     * @param int  $line
     * @param bool $expectedResult
     */
    public function testHasNumberOnLine(int $number, int $line, bool $expectedResult): void
    {
        $result = $this->sut->hasNumberOnLine($number, $line);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test hasNumberOnLine
     */
    public function testHasNumberOnLineWithException(): void
    {
        $this->expectException(OutOfBoardBoundsExceptionException::class);
        $this->sut->hasNumberOnLine(2, 9);
    }

    /**
     * Test exception on hasNumberOnColumn
     *
     * @dataProvider hasNumberOnColumnDataProvider
     *
     * @param int  $number
     * @param int  $column
     * @param bool $expectedResult
     */
    public function testHasNumberOnColumn(int $number, int $column, bool $expectedResult): void
    {
        $result = $this->sut->hasNumberOnColumn($number, $column);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test exception on hasNumberOnColumn
     */
    public function testHasNumberOnColumnWithException(): void
    {
        $this->expectException(OutOfBoardBoundsExceptionException::class);
        $this->sut->hasNumberOnColumn(4, 9);
    }

    /**
     * Test hasNumberOnblock
     *
     * @dataProvider hasNumberOnBlockDataProvider
     *
     * @param int  $number
     * @param int  $line
     * @param int  $column
     * @param bool $expectedResult
     */
    public function testHasNumberOnblock(int $number, int $line, int $column, bool $expectedResult): void
    {
        $result = $this->sut->hasNumberOnblock($number, $line, $column);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test exception on hasNumberOnblock
     *
     * @dataProvider hasNumberOnBlockExceptionDataProvider
     *
     * @param int $number
     * @param int $line
     * @param int $column
     */
    public function testHasNumberOnblockWithException(int $number, int $line, int $column): void
    {
        $this->expectException(OutOfBoardBoundsExceptionException::class);
        $this->sut->hasNumberOnblock($number, $line, $column);
    }

    /**
     * Data provider for testHasNumberOnLine
     *
     * @return mixed[]
     */
    public function hasNumberOnLineDataProvider(): array
    {
        return [
            [2, 1, false],
            [9, 1, true],
            [3, 7, false],
            [5, 7, true],
        ];
    }

    /**
     * Data provider for hasNumberOnColumn
     *
     * @return mixed[]
     */
    public function hasNumberOnColumnDataProvider(): array
    {
        return [
            [2, 1, false],
            [9, 1, true],
            [3, 8, false],
            [7, 8, true],
        ];
    }

    /**
     * Data provider for hasNumberOnBlock
     *
     * @return mixed[]
     */
    public function hasNumberOnBlockDataProvider(): array
    {
        return [
            [8, 1, 3, false],
            [5, 1, 3, true],
            [5, 3, 7, true],
            [2, 3, 8, true],
            [7, 8, 4, true],
            [2, 8, 2, true],
            [2, 8, 1, true],
        ];
    }

    /**
     * Data provider for hasNumberOnBlockExceptionDataProvider
     *
     * @return int[]
     */
    public function hasNumberOnBlockExceptionDataProvider(): array
    {
        return [
            [8, 9, 3],
            [5, 1, 9],
        ];
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
