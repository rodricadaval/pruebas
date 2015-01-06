<?php class HTML_ENTITIES_DECODE {

	public function text_to_pdf_decode($string){
		return iconv('UTF-8', 'windows-1252', html_entity_decode($string));
	}
} ?>