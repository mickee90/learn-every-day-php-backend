<?php

namespace MN\Services;

use MN\Entities\Media;
use MN\Repositories\MediaRepository;
use MN\System\Db;

class MediaForm extends Form {
	
	public $files = [];
	public $added_files = [];
	public $counter = 0;
	
	public function add() {
		$this->setValidated(true);
		
		if(count($this->request->files->all()) > 0) {
			$this->handleFiles($this->request->files->all());
		} else {
			$this->errors[] = 'Filer saknas';
		}
		
		if(!empty($this->request->request->get("submit")))
			$this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->counter = count($this->files);
			$this->setValidated(true);
		}
		
		return $this;
	}
	
	public function handleFiles($files) {
		$successes = 0;
		$files = $files['files'];
		
		$count = count($files['name']);
		
		for ($i = 0; $i < $count; $i++) {
			$error = $files['error'][$i];
			$file_type = $files['type'][$i];
			$public_name = str_replace(' ', '_', urldecode($files['name'][$i]));
			$public_name = preg_replace('/[^A-Za-z0-9\-_.]/', '', $public_name);
			
			if($error != UPLOAD_ERR_OK) {
				$this->errors[] = "Uppladdningen gick fel";
				return false;
			}
			
			if(!in_array($file_type, ["image/png", 'image/jpeg'])) {
				$this->errors[] = "Ogiltig filtyp";
				return false;
			}
			
			$file_name = uniqid('', true);
			$ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
			$full_path = MN_DIR_MEDIA.'/'.$file_name.".".$ext;
			
			while (file_exists($full_path)) {
				$file_name = uniqid('', true);
				$full_path = MN_DIR_MEDIA."/".$file_name.".".$ext;
			}
			
			if(!is_uploaded_file($files['tmp_name'][$i])) {
				$this->errors[] = "Uppladdingen gick fel";
				return false;
			}
			
			if(!move_uploaded_file($files['tmp_name'][$i], $full_path)) {
				$this->errors[] = "Uppladdingen gick fel";
				return false;
			}
			
			chmod($full_path, 0777);
			$media = MediaRepository::insert($file_name.".".$ext, $public_name, str_replace('.'.$ext, '', $public_name), 1);
			if(!$media) {
				$this->errors[] = "Uppladdingen gick fel";
				return false;
			}
			
			$this->added_files[] = $media;
			++$successes;
		}
	}
	
}