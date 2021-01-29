<?php
function format_price($price)
{
    return  number_format($price, 0, ',', ' ') . ' <b class="rub">р</b>';
}

function timer($date){
    $timer_end = new DateTime($date);
    $current_time = new DateTime();
    $diff = $current_time -> diff($timer_end);
    if( $timer_end < $current_time){
        return array('status' => 'timer--finishing', 'remaining_time' => '00:00' );
    }
    elseif ($diff->format("%H") < 1 ){
        return array('status' => 'timer--finishing', 'remaining_time' => $diff -> format("%H:%I") );
    }
    return array('status' => '', 'remaining_time' => $diff -> format("%H:%I") );
}

