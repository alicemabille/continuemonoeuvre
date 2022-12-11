<?php
    require('classes/fpdf/fpdf.php');
    /**
     * Class to generate a pdf
     */
    class PDF extends FPDF {

        /**
         * Creation of the header for the pdf doc
         */
        function Header(){
            $title = $this->metadata['Title'];
            $this->Image('images/logo.png',10,6,30);
            // Police Arial gras 15
            $this->SetFont('Arial','B',15);
            // Décalage à droite
            $this->Cell(80);
            // Titre
            $this->Cell(30,10,$title,0,0,'C');
            // Saut de ligne
            $this->Ln(30);
        }
        
        /**
         * Creation of the footer for the pdf doc
         */
        function Footer(){
            // Positionnement à 1,5 cm du bas
            $this->SetY(-15);
            // Police Arial italique 8
            $this->SetFont('Arial','I',8);
            // Numéro de page
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }

        /**
         * Creation of the body of the chapter
         * @param txt content of the chapter 
         */
        function CorpsChapitre($txt){
            // Times 12
            $this->SetFont('Times','',12);
            // justified text
            $this->MultiCell(0,5,$txt);
            // Line break
            $this->Ln();
            // italic notes
            $this->SetFont('','I');
            $this->Cell(0,5,"");
        }

        /**
         * Add a chapter in the pdf doc
         * @param num the number of the chapter
         * @param titre the title of the chapter
         * @param texte the contents of the chapter
         */
        function AjouterChapitre($num, $titre, $texte){
            $this->AddPage();
            $this->CorpsChapitre($texte);
        }
    }
?>