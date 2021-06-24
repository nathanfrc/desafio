<?php
namespace src\Service;

use PHPUnit\Exception;
use ZipArchive;

class ArquivoService
{


     private  $mapaBits = [
        "num"=> ['start'=>0, 'end'=>3,'space'=>1,'pos'=>'left'],
        "nome"=> ['start'=>5, 'end'=>17],'space'=>2, 'pos'=>'right',
        "pot"=> ['start'=>19, 'end'=>24],'space'=>1,  'pos'=>'left',
        "fcmx"=> ['start'=>30, 'end'=>4],'space'=>2, 'pos'=>'left',
        "others"=> ['start'=>0, 'end'=>6],'space'=>1,'pos'=>'left'
        ];

     private $column = ['num','nome','pot','fcmx','others'];

    public function getArquivo()
    {
        try {

            $arquivoFacade = new \src\Facade\ArquivoFacade();
            $this->descompactarArquivos(
                $arquivoFacade->getArquivo(URI_ARQUIVOS,PATH_REPOSITORY));

          $usinas = $this->readCSV('encad-termicas.csv');

          if(sizeof($usinas)>0)
          {
             $arquivoOriginal =   $this->readFileBat('TERM.DAT');
          }

         //var_dump($usinas);
         // var_dump($arquivoOriginal);

            $usinasList = $this->listIds($usinas);

           $tamanhoColumns = sizeof($this->column);
           $iniColumns =0;


          for($i=0;$i<sizeof($arquivoOriginal);$i++)
          {
              // echo $arquivoOriginal[$i];
              if($i>1)
              {
                  $code = trim(substr($arquivoOriginal[$i],0,4));
                  echo $code.PHP_EOL;

                    if( ($ret = array_search($code,$usinasList))!==false)
                    {
                        echo "achou=".$code;
                        echo "[". $this->createNewLine($arquivoOriginal,$iniColumns)."]";
                        ++$iniColumns;


                        break;
                    }


              }


          }




        }catch(Exception $e)
        {
            throw new ("Erro na service");
        }
    }

    private function descompactarArquivos($name)
    {
        try {

            $arquivo = getcwd() . '/'.PATH_REPOSITORY.'/'.$name;
            $destino = getcwd() . '/'.PATH_REPOSITORY;
            $zip = new ZipArchive;
            $zip->open($arquivo);

            if ($zip->extractTo($destino) == TRUE) {
                echo 'Arquivo descompactado com sucesso.'.PHP_EOL;
            } else {
                echo 'O Arquivo não pode ser descompactado.'.PHP_EOL;
            }
            $zip->close();
        }catch(Exception $e)
        {
            throw new("Error ->".$e->getMessage());
        }
    }

    private function readCSV($name)
    {
        try{

            $handle = fopen(PATH_REPOSITORY."/".$name, "r");
            $row = 0;
            $usinas =[];
            while ($line = fgetcsv($handle, 1000, ",")) {
                if ($row++ == 0) {
                    continue;
                }
                $usinas[] = [
                    'numeroUsina' => $line[0],
                    'nome' => $line[1],
                    'capInstalada' => $line[2],
                    'fatorCapacidadeMax' => $line[3],
                    'teif' => $line[4],
                    'jan' => $line[5],
                    'fev' => $line[6],
                    'mar' => $line[7],
                    'abr' => $line[8],
                    'mai' => $line[9],
                    'jun' => $line[10],
                    'jul' => $line[11],
                    'ago' => $line[12],
                    'set' => $line[13],
                    'out' => $line[14],
                    'nov' => $line[15],
                    'dez' => $line[16],
                    'futuro' => $line[17]
                ];
            }

            fclose($handle);

            return $usinas;

        }catch (Exception $e)
        {
            throw new("Error ->".$e->getMessage());
        }
    }

    private function readFileBat($name)
    {
        try{
            $char = "";
            $fp = fopen("./".PATH_REPOSITORY."/".$name, "r");

            $lines =[];

            while (!feof($fp)){
                $char .= fgetc($fp);
                $linha = fgets($fp,1024);

                array_push($lines,$linha);
            }
            fclose($fp);


            return $lines;

        }catch(Exception $e)
        {
            throw new("Error ->".$e->getMessage());
        }

    }

    private function listIds($usinas)
    {
        $new = [];
        try {

            for ($i = 0; $i < sizeof($usinas); $i++) {
                array_push($new, $usinas[$i]['numeroUsina']);
            }

            return $new;
        }catch(\Exception $e)
        {
            throw  new ("Erro na busca das listas de IDs");
        }
    }

    private function createNewLine($line,$key)
    {
        try{

            $mapBits = $this->mapaBits[$key];



            if(strlen($line) < $mapBits['end']) {
                $qt = $mapBits['end'] - strlen($line);

                if ($mapBits['pos'] == 'left') {
                    /*  if ($mapBits2['start'] == 1) {
                          ++$qt;
                      }*/
                    echo "qt=" . $qt . PHP_EOL;
                    $format = str_pad($line, $qt, " ", STR_PAD_LEFT);

                } else {
                    $format = str_pad($line, $qt, " ", STR_PAD_RIGHT);
                }

                //  $format = str_pad($format, $mapBits2['space'], " ", STR_PAD_RIGHT);
                // $format = str_replace(" ", "&nbsp;", $format);
                echo "[" . $format . "]";

                $pad = str_pad($nome, 20, "0",STR_PAD_LEFT);

                echo $nome." ".strlen($nome).PHP_EOL;
                exit;
            }




        }catch(\Exception $e)
        {
            throw  new ("Erro na createNewLine");
        }

    }

}


