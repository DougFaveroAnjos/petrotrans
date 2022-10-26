<?php
function str_mes_ano(): string
{
    $numero_mes = date('m')*1;
    $ano = date('Y');
    $mes = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
    return mb_strtoupper("{$mes[$numero_mes]} {$ano}");
}
