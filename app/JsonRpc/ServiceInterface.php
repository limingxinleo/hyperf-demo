<?php


namespace App\JsonRpc;


interface ServiceInterface
{
    public function ping(): string;
}
