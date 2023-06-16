<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once('fpdf.php');

class PDF extends FPDF
{
    public float $PDFx;
    public float $PDFy;
    public int $sizeTitle;
    public int $sizeContent;

    function __construct()
    {
        parent::__construct();
        $this->sizeTitle = 12;
        $this->sizeContent = 10;
    }

    function getSizeTitle()
    {
        return $this->sizeTitle;
    }

    function getSizeContent()
    {
        return $this->sizeContent;
    }

    function headerGHSC(string $logo, string $titre)
    {
        // Logo
        $this->Image($logo,10,6,30);
        // Police Arial gras 15
        $this->SetFont('Arial','B',16);
        // Titre
        $this->Text(50, 20, utf8_decode($titre));
        // Saut de ligne
        $this->Ln(30);
    }

    function TextUTF($x, $y, $txt)
    {
        $this->Text($x,$y,utf8_decode($txt));
    }

    function sectionTitle(string $titre, int $x = NULL, int $y = NULL)
    {
        if ($x != NULL) {
            $this->SetX($x, false);
        }
        if ($y != NULL) {
            $this->SetY($y, false);
        }
        $this->SetFont('Arial','B',$this->getSizeTitle());
        $this->TextUTF($this->GetX(), $this->GetY(), $titre);
        $this->SetY($this->GetY() + 10, false);
        $this->PDFx = $this->GetX();
        $this->PDFy = $this->GetY();
    }

    function Footer()
    {
        // Positionnement à 1,5 cm du bas
        $this->SetY(285);
        // Police Arial italique 8
        $this->SetFont('Arial','I',8);
        // Numéro de page
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function sectionContentElement(string $titre, string $contenu)
    {
        $this->SetFont('Arial','',$this->getSizeContent());
        $this->TextUTF($this->PDFx, $this->PDFy, $titre);
        $this->TextUTF($this->PDFx+40, $this->PDFy, $contenu);
        $this->SetY($this->PDFy, false);
        $this->PDFx = $this->GetX();
        $this->addY(4);
    }

    function sectionContentTextArea(string $titre, string $contenu)
    {
        $this->SetFont('Arial','',$this->getSizeContent());
        $this->TextUTF($this->PDFx, $this->PDFy, $titre);
        switch (true) {
            case (strlen($contenu) < 25):
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, $contenu);
                $this->addY(5);
                break;
            case (strlen($contenu) < 50 && strlen($contenu) > 25):
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, substr($contenu, 0, 25));
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, substr($contenu, 25));
                $this->addY(5);
                break;
            case (strlen($contenu) < 75 && strlen($contenu) > 50):
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, substr($contenu, 0, 25));
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, substr($contenu, 25, 25));
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, substr($contenu, 50));
                $this->addY(5);
                break;
            case (strlen($contenu) < 100 && strlen($contenu) > 75):
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, substr($contenu, 0, 25));
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, substr($contenu, 25, 25));
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, substr($contenu, 50, 25));
                $this->addY(5);
                $this->TextUTF($this->PDFx, $this->PDFy, substr($contenu, 75));
                $this->addY(5);
                break;
            default:
                $this->TextUTF($this->PDFx, $this->PDFy+5, "Commentaire trop long");
                $this->addY(5);
                break;
        }
    }

    //fonction pour ajouter une valeur à la position Y
    function addY(float $y){
        $this->PDFy = $this->PDFy + $y;
    }

    //fonction pour ajouter une valeur à la position X
    function addX(float $x){
        $this->PDFx = $this->PDFx + $x;
    }

    //fonction pour lire la position Y de PDF
    function getPDF_Y(){
        return intval($this->PDFy);
    }

    //fonction pour lire la position X de PDF
    function getPDF_X(){
        return intval($this->PDFx);
    }

    function stepTitleH1(string $titre, int $x = NULL, int $y = NULL)
    {
        if ($x != NULL) {
            $this->SetX($x, false);
        }
        if ($y != NULL) {
            $this->SetY($y, false);
        }
        $this->checkPage();
        $this->SetFont('Arial','B',$this->getSizeTitle());
        $this->TextUTF($this->GetX(), $this->GetY(), $titre);
        $this->SetY($this->GetY() + 8, false);
        $this->PDFx = $this->GetX();
        $this->PDFy = $this->GetY();
    }

    function stepText(string $contenu)
    {
        $this->checkPage();
        $this->SetFont('Arial','',$this->getSizeContent());
        $this->TextUTF($this->PDFx, $this->PDFy, $contenu);
        $this->SetY($this->PDFy, false);
        $this->PDFx = $this->GetX();
        $this->addY(4);
    }

    function stepTextWithCheckbox(string $contenu, int $space = 100)
    {
        $this->SetFont('Arial','',$this->getSizeContent());
        $this->TextUTF($this->PDFx, $this->PDFy, $contenu);
        $this->Rect($this->PDFx+$space, $this->PDFy-3, 3, 3);
        $this->addY(4);
    }

    function checkPage()
    {
        if ($this->PDFy > 260) {
            $this->AddPage();
            $this->PDFy = 20;
        }
    }
}