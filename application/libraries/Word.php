<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
Simplifie le création de documents avec PHPWord

*/


use PhpOffice\PhpWord\PhpWord;

class Word {

    const BASE_FONT = 'Liberation Sans';

    public function __construct() {
        $this->CI =& get_instance();

        $this->word = new PhpWord();

        // default styles
        $this->word->addFontStyle('globalFont', ['name' => $this::BASE_FONT, 'size' => 11]);
        $this->word->addFontStyle('footer', ['size' => 9]);
        $this->word->addFontStyle('link', ['color' => '3366ff', 'underline' => 'single']);
        $this->word->addTitleStyle(1, ['size' => 18], ['alignment' => 'center']);
        $this->word->addTitleStyle(2, ['size' => 16]);
        $this->word->addTableStyle('mainTable', [
            'borderColor' => 'FFFFFF',
            'borderSize' => 6,
            'cellMargin' => 100,
        ]);

        $this->section = $this->word->addSection();
    }

    public function addTitle($txt, $level=1) {
        $this->section->addTitle($txt, $level);
    }

    public function addTable() {
        return $this->section->addTable('mainTable');
    }

    public function out($format) {
        // Create file
        $formats = [
            'odt' => 'ODText',
            'docx' => 'Word2007',
        ];
        if (! array_key_exists($format, $formats)) {
            show_404();
            return;
        }

        $this->CI->load->helper('download');
        $file = 'photos/output.' . $format;

        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->word, $formats[$format]);
        $xmlWriter->save($file);
        force_download('socle_export.' . $format, file_get_contents($file));
        unlink($file);
    }

}