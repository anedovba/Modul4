<?php
namespace Library;
interface ExportDocumentInterface
{
    public function createDocument($filename, array $data);
    public function download();
    public function open();
}