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

    //get url of outcome question and render on pdf doc
    function OcUrl($id,$setY){

        $this->SetY($setY);
        $this->SetFontSize('25');
        $this->SetTextColor(220, 50, 50);
        $this->MultiCell(0, 15, 'Visit http://localhost:8000/take/oc/' . $id);

    }

    //get url of true/false question and render on pdf doc
    function TfUrl($id, $setY){

        $this->SetY($setY);
        $this->SetFontSize('25');
        $this->SetTextColor(220, 50, 50);
        $this->MultiCell(0, 15, 'Visit http://localhost:8000/take/tf/' . $id);

    }

    //get url of graded question and render on pdf doc
    function GdUrl($id,$setY){

        $this->SetY($setY);
        $this->SetFontSize('25');
        $this->SetTextColor(220, 50, 50);
        $this->MultiCell(0, 15, 'Visit http://localhost:8000/take/gd/' . $id);

    }

    //get image path of quiz
    function getGdImage($id){
        $imgPath = '%kernel.root_dir%/../web/img/quiz/'.$id.'/'.$id.'.original.jpg';

        //if image exist the add to pdf doc
        if (file_exists($imgPath)){
            $this->MultiCell(0,15, $this->Image($imgPath, 20, 95, 50, 50));
            $this->Ln(45);
        }else{
       //else render empty space
            $this->Cell(0, 0, " ", 0, 1);
        }

    }
    //get image path of quiz
    function getTfImage($id){
        $imgPath = '%kernel.root_dir%/../web/img/quiz/'.$id.'/'.$id.'.original.jpg';

        //if image exist the add to pdf doc
        if (file_exists($imgPath)){
            $this->MultiCell(0,15, $this->Image($imgPath, 20, 80, 50, 50));
            $this->Ln(50);
        }else{
       //else render empty space
            $this->Cell(0, 0, " ", 0, 1);
        }

    }

    //get lecture note path
    function getNote($id){
        $notePath = '%kernel.root_dir%/../pdf/quiz/'.$id.'/'.$id.'.original.pdf';
        $space = '$this->Cell(40, 10, " ", 0, 1)';

        //if the lecture note exist then return the path to it
        if (file_exists($notePath)){

            return $notePath;
        }else{
          //return null if note does not exist
            return null;
        }

    }

    //render quiz page based on question
    function getQuizPage($str){


        $this->PageHeader();
        $this->SetFont('Times', '', 20);
        $this->SetTextColor(0, 0, 0);
        $this->AddLink();
        $this->MultiCell(0, 10, $str);
        $this->Ln(8);
    }

    //render true/false quiz page based on question
    function getTfOption(){
        $option= array("(A) True", "(B) False");
        foreach($option as $op){
            $this->MultiCell(0, 10, $op);
            $this->Ln(6);

        }


    }

    //render graded quiz page based on question and answer choice
    function getGradedOption( $len, array $alp , array $ans){

        //loop through based on answer option length and append the corresponding alphabets
        for ($i = 0; $i < $len; $i++) {

            $str = $alp[$i] . $ans[$i];

            //add each answer choice to the pdf generated
            $this->MultiCell(0, 10, $str);
            $this->Ln(6);
        }
    }


}