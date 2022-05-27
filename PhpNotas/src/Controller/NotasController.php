<?php
/* Desafio Coderbyte - Grancurso
 * Script em PHP para ler um anexo e transformar em CSV com os dados dos aprovados, incluindo candidatos com deficiência.
 * Resultado: 313 registros
 * Arquigo gerado: ..\public\file.csv
 * 
 * @author Sérigio Martins Pereira Jr <sergio.mendonca.ti@gmail.com>
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

header("Content-type: text/html; charset=utf-8");

class NotasController extends AbstractController
{
    #[Route('/notas', name: 'app_notas')]
    public function index(): Response
    {
        // Parse PDF
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile('C:/ED_6__2019__DPDF_DEFENSOR_RES_PROVISORIO_OBJETIVA.PDF');

        // Recupera o texto do PDF
        $text = $pdf->getText();
        
        // REGEX para pegar somente os dados dos candidatos aprovados
        $re = '/(?s)(?>[0-9]{8}).*?(?>[0-9]{3}.[0-9]{2})/m';
        preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);

        // Geração do CSV
        $fp = fopen('file.csv', 'w');
        foreach ($matches as $line) {
            
            // Remoção de quebra de linhas
            $line = preg_replace('#\R+#', '', $line);
            fputcsv($fp, $line);
        }
        fclose($fp);
        
        return new Response();
    }
}
