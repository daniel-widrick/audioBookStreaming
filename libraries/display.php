<?php

class DISPLAY
{

	public static function showAuthorList()
	{
		$bookScan = new bookScan();
		$bookList = $bookScan->getBookList();

		$authors = [];
		$books = [];
		foreach($bookList as $book)
		{
			$author = $book["author"];
			if($author == "") $author = "Unknown";
			$authors[] = $author;
			$books[$author][] = $book;
		}
		$list = '';
		$list = '<ul class="authorList">';
		foreach($books as $author => $book)
		{
			$list .= '<li class="authorItem" onclick="authorClick(this)">' . $author;
			$list .= '<ul class="bookList">';
			foreach($book as $b)
			{
				$list .= '<li class="bookItem"><a href="index.php?book='.$b["id"].'">'.$b["title"].'</a></li>';
			}
			$list .= '</ul></li>';
		}

		$list .= '
		<script type="text/javascript">
		function authorClick(el)
		{
			var bookList = el.getElementsByClassName("bookList")[0];
			console.log(bookList);
			if(bookList.style.display == "") bookList.style.display = "none";
			if(bookList.style.display == "none") bookList.style.display = "block";
			else bookList.style.display = "none";
		}
		</script>';
		return $list;
	}
}
