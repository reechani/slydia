<?php

// only available if logged in
class CCtrl4Page implements IController {

	public function Index() {
		global $ly;

		if (isset($_SESSION["user"])) {
			$html = "<hr /><h2>All pages</h2>";
// show pages
			$res = $ly->db->select(TBL_PREFIX . "Pages");
			if (empty($res)) {
				$html .= "Tables do not exist. <a href='" . $ly->cfg["baseurl"] . "/db/install/" . "'>Install</a> first.";
			} else {
				$html .= "<p><a href='" . $ly->cfg["baseurl"] . "/page/edit/' title='New page' >New page</a></p>";
				$html .= "<table><tr><th>ID</th><th>Title</th></tr>";
				while ($row = $res->fetch_object()) {
					$html .= "<tr><td><a href='" . $ly->cfg["baseurl"] . "/page/edit/" . $row->id . "'>" . $row->id . "</td><td>" . $row->title . "</td></tr>";
				}
				$html .= "</table>";
			}
// get them from database
// list pages
		} else {
			$html = "<div class='error'>You need to be logged in to access this page</div>";
		}
		$ly->template->regions->main = $html;
	}

	public function edit($page = false) {
		global $ly;
// show form for creating new page
		$html = "";
		if (isset($_SESSION["user"])) {

			if (!empty($_POST)) {
				$html = "<hr /><h2>Saving page...</h2>";
				$row = array();
				foreach ($_POST as $key => $post) {
					if ($key != "id") {
						$html .= "$key => $post<br/>";
						$row[$key] = $post;
					} else {
						$id = $post;
					}
				}
				if ($id != "") {
					$ly->db->update(TBL_PREFIX . "Pages", $row, "id = $id");
				} else {
					$ly->db->insert(TBL_PREFIX . "Pages", $row);
				}
//			$ly->db->set("Pages", $_POST);
				// save page to database
			} else {
				$title = "";
				$content = "";
				$id = "";
				if ($page != false) {
					$res = $ly->db->select(TBL_PREFIX . "Pages", "id = $page");
					$row = $res->fetch_object();
					$title = $row->title;
					$content = $row->content;
					$id = $row->id;
				}
				$html = "<hr /><h2>Edit</h2>";
				$html .= <<<EOD
<form action="." method="post">
	<fieldset>
		<input type="hidden" name="id" value="$id" />
		<label for="title" >Title</label><br /><input type="text" name="title" id="title-text" class="title" value="$title" /><br />
		<label for="content" >Content</label><br /><textarea id="content-text" name="content" >$content</textarea><br />
		<input type="submit" value="Submit" />
	</fieldset>
</form>
EOD;
			}
		} else {
			$html = "<div class='error'>You need to be logged in to access this page</div>";
		}
		// get post content and save page to database
		$ly->template->regions->main = $html;
	}

	public function show($page) {
		global $ly;

		$res = $ly->db->select(TBL_PREFIX . "Pages", "id = $page");
		$row = $res->fetch_object();

		$html = "<h2>" . $row->title . "</h2>";
		$ly->template->title = $row->title;
		$html .= html_entity_decode($row->content);

		$ly->template->regions->main = $html;
	}

}
