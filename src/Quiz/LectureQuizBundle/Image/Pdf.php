<?php
namespace Quiz\LectureQuizBundle\Image;

use fpdf\FPDF;
use fpdi\FPDI;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection;

class PDF extends FPDI
{
    // Page header
    function PageHeader()
    {
        // Arial bold 15
        $this->SetFont('Arial', 'B', 30);
        // Colors of frame, background and text
        $this->SetFillColor(200,220,255);
        $this->SetTextColor(220, 50, 50);
        // Title
        $this->Cell( 0, 20, 'Lecture Quiz', 0, 1, 'C',true);

       // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Text color in gray
        $this->SetTextColor(128);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function OcUrl($id,$setY){

        $this->SetY($setY);
        $this->SetFontSize('25');
        $this->SetTextColor(220, 50, 50);
        $this->MultiCell(0, 15, 'Visit http://localhost:8000/take/oc/' . $id);

    }

    function TfUrl($id, $setY){

        $this->SetY($setY);
        $this->SetFontSize('25');
        $this->SetTextColor(220, 50, 50);
        $this->MultiCell(0, 15, 'Visit http://localhost:8000/take/tf/' . $id);

    }

    function GdUrl($id,$setY){

        $this->SetY($setY);
        $this->SetFontSize('25');
        $this->SetTextColor(220, 50, 50);
        $this->MultiCell(0, 15, 'Visit http://localhost:8000/take/gd/' . $id);

    }

    function getImage($id){
        $imgPath = '%kernel.root_dir%/../web/img/quiz/'.$id.'/'.$id.'.original.jpg';

        if (file_exists($imgPath)){
            $this->MultiCell(0,15, $this->Image($imgPath, 20, 110, 50, 50));
        }else{

            $this->Cell(40, 10, " ", 0, 1);
        }

    }

    function getNote($id){
        $notePath = '%kernel.root_dir%/../pdf/quiz/'.$id.'/'.$id.'.original.pdf';
        $space = '$this->Cell(40, 10, " ", 0, 1)';
        if (file_exists($notePath)){

            return $notePath;
        }else{

            return null;
        }

    }

    function getQuizPage($str){


        $this->PageHeader();
        $this->SetFont('Times', '', 30);
        $this->SetTextColor(0, 0, 0);
        $this->AddLink();
        $this->MultiCell(0, 15, $str);
        $this->Ln(8);
    }

    function getTfOption($a, $b){

        $this->Cell(40, 10, $a, 0, 1);
        $this->Ln(8);
        $this->Cell(40, 10, $b, 0, 1);
    }

    function getGradedOption( $len, array $alp , array $ans){

        //loop through based on answer option lenght and append the correcponding alphabets
        for ($i = 0; $i < $len; $i++) {

            $str = $alp[$i] . $ans[$i];

            //add each answer choice to the pdf generator
            $this->MultiCell(0, 10, $str);
            $this->Ln(4);
        }
    }


}