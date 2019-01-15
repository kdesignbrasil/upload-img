<?php 

namespace UploadLeandro;

class Upload
{
    private $files; 
    private $size;
    private $dir;
    public function __construct(array $files)
    {
        if(isset($files)){
            $this->files = $files;
        }else{
            return 'ERRO: Nenhum arquivo selecionado';
        }        
    } 

    // setando o tamanho máximo do arquivo
    public function setSize($size)
    {
        $this->size = $size;
    }
    // seta o diretorio onde os arquivos vão ser salvos na aplicação
    public function setDir($dir)
    {
        $this->dir = $dir;
    }
    // return o limete de tamanho de arquivo definido
    private function getSize()
    {
        return $this->size;
    }
    // retorn o o diretorio onde os aquivos vão ser salvos
    private function getDir()
    {
        return $this->dir;
    }
    // return total de arquivos
    public function totalFiles()
    {
        $totalFiles = count($this->files['name']);
        return $totalFiles;
    }  
    private function getUniqid()
    {
        return md5(uniqid(rand(), true)) . '.';
    }
    private function upload()
    {   
        $suporteIMG = array (IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $errorFiles  = array();
        for ($i=0; $i < $this->totalFiles(); $i++) {   
            $tmpEX = explode('.', $this->files['name'][$i]);
            $ext = end($tmpEX);    
            $nome_final =  $this-> getUniqid(). $ext;  
           if($this->getSize() >= $this->files['size'][$i] && in_array($this->files['type'][$i] , array('image/gif','image/jpeg', 'image/jpg', 'image/png'))):
                if(move_uploaded_file($this->files['tmp_name'][$i],'../src'. '/' . $nome_final )):
                    array_push($errorFiles, "ER01");
                endif;
                if (!in_array(  exif_imagetype($nome_final) , $suporteIMG) ) :
                    array_push($errorFiles, "ER00");
                    unlink($nome_final);
                endif;
           else:
                array_push($errorFiles, "ER00");
           endif; 
        }
        return $errorFiles;
    }
    public function startUpload()
    {
       return $this->upload();
    }
}




    if(isset($_FILES['file'])):
        $upload = new upload($_FILES['file']);  
        $upload->setSize(1000);  
        $resultato = $upload->startUpload();
        if(in_array('ER00', $resultato)):
            echo  'Um ou mais arquivos pode não ter enviado. Verifique se o arquivo é uma image ou se é maior que 1MB';
        else:
            echo 'Upload realizado com sucesso';
        endif;
    endif;
    
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
<link rel="stylesheet" href="style.css">
<title>CLass testes</title>
</head>
<body>
 <form action="../src/Upload.php" method="post" enctype="multipart/form-data" >
	<input type="file" name="file[]" multiple>
	<input type="submit">
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>