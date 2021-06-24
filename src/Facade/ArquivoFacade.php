<?php

namespace src\Facade;

use PHPUnit\Exception;

class ArquivoFacade
{

    const PATH = 'repository';

    public function getArquivo($uri,$path)
    {
        try {
            $client = new \GuzzleHttp\Client([
                'verify' => false
            ]);

            $response = $client->request('GET',
                $uri)->getBody();

           if($response->getSize() == 0)
           {
               throw new ("Falha na busca do arquivo no servidor");
           }

            if(!is_dir($path))
            {
                throw new ("Diretorio não existe");
            }

            file_put_contents($path . "\\teste_true_term.zip", $response);

            echo "Arquivo capturado com sucesso".PHP_EOL;
            return 'teste_true_term.zip';

        }catch(Exception $e)
        {
            throw new ("Erro na busca do arquivo no servidor ");
        }
    }





}