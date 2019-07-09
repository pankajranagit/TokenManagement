<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once("./vendor/autoload.php");
use Dompdf\Dompdf;

class Pdfgenerator
{

    public function generate($html, $filename = '', $stream = TRUE, $paper = 'A4', $orientation = "portrait")
    {
        $dompdf = new DOMPDF();
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        if ($stream) {
            $dompdf->stream($filename . ".pdf", array("Attachment" => 0));
        } else {
            return $dompdf->output();
        }
    }

    public function generateToken($html, $filename = '', $stream = TRUE, $orientation = "portrait")
    {
        $dompdf = new DOMPDF();
        $dompdf->loadHtml($html);

        $customPaper = array(0, 0, 288, 288);

        $dompdf->setPaper($customPaper, $orientation);
        $dompdf->render();
        if ($stream) {
            $dompdf->stream($filename . ".pdf", array("Attachment" => 0));
        } else {
            return $dompdf->output();
        }
    }
}
