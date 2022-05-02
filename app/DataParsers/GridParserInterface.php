<?php

namespace App\DataParsers;

interface GridParserInterface
{
    public function parseInputToRepositoryFormat(array $input);

    public function parseOutputFromRepositoryFormat(array $output, array $input, $count, $total);
}
