<?php


function display(array $board)
{
    print_r("\n\n------------------\n");
    for ($i=0; $i<9; $i++) {
        for ($j=0; $j<9; $j++) {
            printf( (($j+1)%3) ? "%d " : "%d|", $board[$i][$j]);
        }
    print_r("\n");
    if (!(($i+1)%3))
        print_r("------------------\n");
    }
    print_r("\n\n");
}


function absentSurLigne($k, array $board, $i)
{
    for ($j=0; $j < 9; $j++){
        if ($board[$i][$j] == $k) {
            return false;
        }
    }

    return true;
}

function absentSurColonne($k, array $board, $j)
{
    for ($i=0; $i < 9; $i++) {
        if ($board[$i][$j] == $k) {
            return false;
        }
    }

return true;
}



function absentSurBloc($k, array $board, $i, $j)
{
    $_i = $i-($i%3);
    $_j = $j-($j%3);  // ou encore : _i = 3*(i/3), _j = 3*(j/3);
    for ($i=$_i; $i < $_i+3; $i++) {
        for ($j = $_j; $j < $_j + 3; $j++) {
            if ($board[$i][$j] == $k) {
                return false;
            }
        }
    }

return true;
}




function estValide (array &$board, $position)
{
    if ($position == 9*9) {
        return true;
    }

    $i = $position/9;
    $j = $position%9;

    if ($board[$i][$j] != 0) {
        return estValide($board, $position + 1);
    }

    for ($k=1; $k <= 9; $k++)
    {
        if (absentSurLigne($k,$board,$i) && absentSurColonne($k,$board,$j) && absentSurBloc($k,$board,$i,$j))
        {
            $board[$i][$j] = $k;

            if ( estValide ($board, $position+1) ) {
                return true;
            }
        }
    }

    $board[$i][$j] = 0;

    return false;
}




$game = [
[9,0,0,1,0,0,0,0,5],
[0,0,5,0,9,0,2,0,1],
[8,0,0,0,4,0,0,0,0],
[0,0,0,0,8,0,0,0,0],
[0,0,0,7,0,0,0,0,0],
[0,0,0,0,2,6,0,0,9],
[2,0,0,3,0,0,0,0,6],
[0,0,0,2,0,0,9,0,0],
[0,0,1,9,0,4,5,7,0]
];

// esasy
$game = [
    [0,0,9,0,5,0,0,0,0],
    [0,0,0,0,0,0,7,9,6],
    [4,3,7,6,0,0,5,1,0],
    [9,7,0,0,4,8,0,0,2],
    [3,0,0,1,0,9,0,0,5],
    [1,0,0,3,2,0,0,4,7],
    [0,9,8,0,0,7,4,5,1],
    [5,1,2,0,0,0,0,0,0],
    [0,0,0,0,1,0,2,0,0],
];

// intermediate
$game = [
    [1,0,0,7,6,5,9,0,0],
    [5,0,0,0,3,0,6,1,0],
    [6,0,0,9,0,0,0,0,4],
    [0,0,0,0,0,0,7,9,5],
    [0,0,5,0,0,0,2,0,0],
    [8,6,2,0,0,0,0,0,0],
    [3,0,0,0,0,7,0,0,9],
    [0,8,4,0,9,0,0,0,1],
    [0,0,7,1,2,8,0,0,6],
];
// hard
$game = [
    [0,9,0,0,7,0,0,0,0],
    [0,8,0,5,2,0,0,1,0],
    [5,0,3,0,0,0,2,0,0],
    [0,0,1,9,0,0,0,2,5],
    [2,0,8,0,0,0,7,0,4],
    [9,5,0,0,0,7,1,0,0],
    [0,0,5,0,0,0,4,0,6],
    [0,4,0,0,3,8,0,7,0],
    [0,0,0,0,6,0,0,3,0],
];





display($game);
estValide($game,0);
print_r("\n\n");
display($game);
