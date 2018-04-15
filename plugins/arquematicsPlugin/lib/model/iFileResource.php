<?php

/**
 * funciones básicas para ficheros
 */
interface iFileResource
{
    public function getPathAndFile();
    public function getBaseName();
    public function getExtension();
}
