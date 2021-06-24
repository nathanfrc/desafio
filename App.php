<?php

  require 'vendor/autoload.php';

  define('PATH_REPOSITORY','repository');
  define('URI_ARQUIVOS','https://datawarehouse-true.s3-sa-east-1.amazonaws.com/teste-true/teste_true_term.zip');

  $arquivoService = new \src\Service\ArquivoService();
  $arquivoService->getArquivo();