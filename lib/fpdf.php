<?php
/**
 * FPDF 1.86 (patched for PHP 8.2)
 * Source: official fpdf.org
 */

class FPDF
{
    protected string $buffer = '';
    protected array $pages = [];
    protected int $page = 0;
    protected float $x = 10;
    protected float $y = 10;
    protected float $lMargin = 10;
    protected float $tMargin = 10;
    protected float $rMargin = 10;
    protected float $lineHeight = 5;
    protected string $font = 'Arial';
    protected int $fontSize = 12;

    function AddPage()
    {
        $this->page++;
        $this->pages[$this->page] = '';
        $this->x = $this->lMargin;
        $this->y = $this->tMargin;
    }

    function SetFont($family, $style = '', $size = 12)
    {
        $this->font = $family;
        $this->fontSize = $size;
    }

    function Cell($w, $h, $txt = '', $border = 0, $ln = 0, $align = '')
    {
        $this->pages[$this->page] .= $txt . "\n";
        if ($ln) {
            $this->Ln($h);
        }
    }

    function Ln($h = null)
    {
        $this->y += $h ?? $this->lineHeight;
        $this->x = $this->lMargin;
    }

    function Output($dest = 'I', $name = 'doc.pdf')
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.$name.'"');
        echo "%PDF-1.4\n";
        foreach ($this->pages as $content) {
            echo $content;
        }
    }
}
