<?php

class DISPLAY
{

	public static function showResume()
	{
		$bookScan = new bookScan();
		$books = $bookScan->getResumeList();
		$html = '<div id="resumeList">';
		foreach($books as $book)
		{
			$html .= '<div class="resumeListBook">';
			$html .= "<span class='resumeListBookTitle'>".$book['title']."</span>
				<a href='index.php?book=".$book['id']."'>
				<img src='".$book['thumbnail']."' class='resumeListBookThumb'/></a>
				<span class='resumeListBookAuthor'>".$book['author']."</span>
				</div> \n";
		}
		return $html;
	}
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
	public static function showBookPlayer($book,$files)
	{
		//Reindex files to zero
		$files = array_values($files);
		//TODO: convert 2 to last file played
		$html = '';
		$html .= '<div class="bookPlayerContainer">';
		$html .= '<div class="bookThumbnail"><img class="bookThumbnail" src="' . $book['thumbnail'] . '" /></div>';
		$html .= '<div class="bookDescription"><span class="bookDescription">' . $book['description'] . '</span></div>';
		$html .= '<div class="bookPlayerControls">';
		$html .= '<audio id="bookAudio" preload="auto" controls="" onended="nextTrack()">';
		$html .= '<source id="audioSrc" src="' . $book["path"] . DIRECTORY_SEPERATOR . $files[0]. '">';
		$html .= '</audio></div>';
		$html .= '<ol class="bookTrackList">';
		foreach($files as $num => $track)
		{
			$html .= "<li id='$num' class='bookTrack' onclick='playTrack($num)'>" . $track . '</li>';
		}
		$html .= '</ol>';
		$html .= '</div>';
		$html .= '<script type="text/javascript">';
		$html .= 'var fileList = ' . json_encode($files) . ';';
		$html .= 'var fileIndex = 0;';
		$html .= 'function highlightTrack() {
				var tracks = document.getElementsByClassName("bookTrack");
				for( i = 0; i < tracks.length; i++) {
					if( i == fileIndex ) tracks[i].style.color = "#ffffff";
					else tracks[i].style.color = "#bbbbbb";
				}
			}';
		$html .= 'highlightTrack();';
		$html .= 'function nextTrack() {
				var nextIndex = fileIndex+1;
				if( nextIndex > fileList.length ) nextIndex = 0;
				fileIndex = nextIndex;
				playTrack(nextIndex);
			}
			function playTrack(num) {
				var source = document.getElementById("audioSrc");
				var player = document.getElementById("bookAudio");
				source.src = "' . $book["path"] . '/" + fileList[num];
				player.load();
				player.play();
				fileIndex = num;
				highlightTrack();
			}';
		$html .= 'var player = document.getElementById("bookAudio");
			function trackTime() {
				user = "' . $_SESSION["username"] . '";
				bookid = "' . $book["id"] . '";
				bookFile = fileIndex;
				time = Math.floor(player.currentTime);
				console.log("User: " + user + " is listening to " + bookid + " on file " + bookFile + " at time: " + time);
				var xhr = new XMLHttpRequest();
				xhr.open("GET","api/savePosition.php?book="+bookid+"&file="+bookFile+"&time="+time);
				xhr.onload = function() {
					if(xhr.status === 200) console.log("time Saved!");
					else console.log("ERROR: " + xhr.status);
				};
				xhr.send();	
		};';
		$html .= 'function loadTime() {
				var xhr = new XMLHttpRequest();
				var bookid = "'.$book["id"].'";
				xhr.open("GET","api/getPosition.php?book="+bookid);
				xhr.onload = function() {
					if(xhr.status === 200) {
						//load time&file
						console.log("repsone: " + xhr.responseText);
						bookTime = JSON.parse(xhr.responseText);
						console.log(bookTime);
						playTrack(bookTime["file"]);
						player.pause();
						player.currentTime = bookTime["time"];
						player.play();
					}
					else console.log("ERROR: " + xhr.status);
				};
				xhr.send();
			}; loadTime();';
		$html .= 'setInterval(trackTime,15000);';
		
		$html .= '</script>';
		return $html;
	}
}
