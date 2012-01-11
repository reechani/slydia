<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CCtrl4Database
 *
 * @author Jane
 */
class CCtrl4Database implements IController {

	public function Index() {
		global $ly;
		$ly->template->regions->main = "Index for database";
	}

	public function Install() {
		global $ly;
		$ly->template->regions->main = "Installing database tables...";

//		$ly->db->addCreateTable("Pages");
//		$ly->db->addCreateCol("Pages", "id", "int", "auto_increment primary key");

		$table = Array(
			"name" => "Pages",
			"cols" => Array(
				Array("name" => "id", "type" => "int", "additional" => "auto_increment primary key"),
				Array("name" => "content", "type" => "text", "additional" => ""),
				Array("name" => "title", "type" => "varchar(255)", "additional" => "")
			),
			"engine" => "MYISAM",
			"charset" => "utf8",
			"collate" => "utf8_swedish_ci"
		);

		$ly->db->create($table);
	}

}
