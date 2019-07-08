<?php


function overflow32($value) {//There is no need to overflow 64 bits to 32 bit
    return $value;
}



function intval32bits($value)
{
    $value = ($value & 0xFFFFFFFF);

    if ($value & 0x80000000)
        $value = -((~$value & 0xFFFFFFFF) + 1);

    return $value;
}
