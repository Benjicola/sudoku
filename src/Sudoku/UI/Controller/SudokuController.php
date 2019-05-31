<?php

namespace Sudoku\UI\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * SudokuController.
 */
class SudokuController
{
    /**
     * @Route("/sudoku", methods="GET")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     *
     * @throws \Exception
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, MessageBusInterface $commandBus): Response
    {
        return new JsonResponse(
            '== Sudoku ==',
            Response::HTTP_OK,
            []
        );
    }
}
