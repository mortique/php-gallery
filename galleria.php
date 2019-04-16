<?php

function laskuri()
{
    $tiedosto="vierailulaskuri.txt";  //kävijälaskuri
  if(!file_exists($tiedosto)) {
    $kahva=fopen($tiedosto,"w+");
    $laskuri=1;
    fwrite($kahva,$laskuri);
    fclose($kahva);
} else {   
    // Luodaan tiedosto "kavijalaskuri.txt" ja avataan se
    $kahva=fopen($tiedosto,"x+");
    $laskuri=1;
    fwrite($kahva,$laskuri);
    fclose($kahva);
} 
$kahva=fopen($tiedosto,"r");
$laskuri=fread($kahva, filesize($tiedosto));
  fclose($kahva);
$kahva=fopen($tiedosto,"w+");
  if(!$c) {
    $laskuri=$laskuri+1;
}
  fwrite($kahva,$laskuri);
  fclose($kahva);
    
    $file="kavijalaskuri.txt";  
    IF(!IS_WRITABLE($file)){DIE("Tiedostolla ".$file." ei ole kirjoitus oikeuksia!");}
    $ip=EXPLODE(".",GETENV("REMOTE_ADDR"));
    $ip=$ip[0].'.'.$ip[1].'.'.$ip[2];
    $f=FILE($file);
    $s=true;
    FOR($i=0;$i<COUNT($f);$i++)IF($ip==TRIM($f[$i])){$s=false;BREAK;}
    IF($s==true){$fp=FOPEN($file, "a");FLOCK($fp,2);FWRITE($fp,$ip."\r\n");FCLOSE($fp);}
    $c=COUNT(FILE($file));
}
function shrink( $pathToImages, $thumbWidth ) // luodaan thumbnailit
{
  $dir = opendir( $pathToImages );
  
  while (false !== ($fname = readdir( $dir ))) {
	
    $info = pathinfo($pathToImages . $fname);
		
    if ( strtolower(substr($fname, -3)) == 'jpg' || strtolower(substr($fname, -4) == 'jpeg')) 
    {
      $img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
      $width = imagesx( $img );

      if( $width > $thumbWidth ) { 
				
				$height = imagesy( $img );
				$new_width = $thumbWidth;
				$new_height = floor( $height * ( $thumbWidth / $width ) );
				
				echo "Pienennetään kuva $fname ({$width}x{$height} -> {$new_width}x{$new_height})<br>";
				
				$tmp_img = imagecreatetruecolor( $new_width, $new_height );

				imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
				imagejpeg( $tmp_img, "{$pathToImages}{$fname}" );
			}
    }
  }  
  closedir( $dir );
}


function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth ) 
{

  $dir = opendir( $pathToImages );
	echo "Luodaan pikkukuvat:<br>";

  while (false !== ($fname = readdir( $dir ))) {

    $info = pathinfo($pathToImages . $fname);

    if ( strtolower(substr($fname, -3)) == 'jpg' || strtolower(substr($fname, -4) == 'jpeg')) 
    {
			echo "$fname<br>";

      $img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
      $width = imagesx( $img );
      $height = imagesy( $img );

      $new_width = $thumbWidth;
      $new_height = floor( $height * ( $thumbWidth / $width ) );

      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
    }
  }
  closedir( $dir );
}

function nuoli( $suunta, $ret ){			// nuolifunktio		
	if( $suunta == 'vasen') { echo "<a href=\"javascript:edellinen()\" name='left'><img src='arrow_left50.png' name='vasen' 
		onmouseout=\"javascript: vasen.src='arrow_left50.png';\" onmouseover=\"javascript: vasen.src='arrow_leftb50.png';\" class='vasen'></a>";  }
	elseif( $suunta == 'oikea' ) { echo "<a href=\"javascript:seuraava(0)\" name='right'><img name='oikea' src='arrow_right50.png' 
		onmouseout=\"javascript: oikea.src='arrow_right50.png';\" onmouseover=\"javascript: oikea.src='arrow_rightb50.png';\" class='oikea'></a>"; }
	elseif( $suunta == 'roll' ) { 
		if( $ret != '' ) { echo "<a href='$ret'><img style=\"vertical-align: top;\" name='takaisin1' onmouseout=\"javascript: takaisin1.src='back50.png';\" onmouseover=\"javascript: takaisin1.src='backb50.png';\" src='back50.png'></a>"; }
		else { 	echo "<a href='$ret'><img style=\"vertical-align: top;\" name='takaisin1' onmouseout=\"javascript: takaisin1.src='back50.png';\" onmouseover=\"javascript: takaisin1.src='backb50.png';\" src='back50.png'></a>"; }
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		echo  "<a class='noeffect' href=\"javascript:doTimer()\"><img name='roll' onclick='ikoni(2)' onmouseover=\"ikoni(1)\" onmouseout=\"ikoni(0)\" src='play50.png'></a>"; 
	}
}


	$val = $_GET['id'];  		//  ------------------ koodi alkaa ------------------
	$getinfo = $_GET['get'];
	if( $getinfo == 'add' ) { shrink('galleria/', '1024'); createThumbs("galleria/","galleria/thumbs/",100); exit; } //kuvien lisäys
	if( $getinfo == '') {
		$tt = fopen( 'html.txt', 'r' ); // html-sivun lukeminen
		while (!feof($tt)) {		
			$rivi = fgets($tt, 1024); 
			echo $rivi;
		}
		fclose($tt);
	}	
	if($val == '' && $getinfo == ''){								// galleria pääsivu	
	laskuri();
		echo "
		<tr><td align='center' valign='top' colspan=5>
		<table style='margin-top:25px;' border=1 cellspacing=10 align='center'><tr>";
		$hak = opendir("galleria/");
		$nimi = readdir($hak);
		$nimi = readdir($hak);					
		while ($nimi) {		// kuvien taulukointi			
			$nimi = readdir($hak);					
			$teksti = '';			
			if($nimi != 'thumbs' && $nimi != '' && substr($nimi, 0, 1) != '.'){				
				$i++;				
				$exif = exif_read_data('galleria/'.$nimi, 0, true);
				foreach ($exif as $key => $section) {
					if($key == 'IFD0' || $key== 'WINXP'){
						foreach ($section as $name => $arvo) {							
							if($name == 'Keywords'){ $teksti = $arvo; }
						}
					}
				}
				echo "<td align='center' width=100><a href='galleria2.php?id=$nimi'><img id='kuva' src='galleria/thumbs/$nimi' alt='$teksti' /><br><font size=-1>$teksti</font></a></td>";
				if($i / 8 == round($i / 8)){ 	echo "</tr><tr>";
				}	
			}
		}
		closedir($hak);
		echo "</tr></table></font>";				

	}
	elseif($val != "" || $getinfo != "")													// galleria kuvankatselu
	{
		$ret = $_GET['ret'];
		if($ret == '') $ret = 'galleria2.php';		
		$laji = $_GET['laji'];
	
		$hak = opendir("galleria/");  // kuvien nimien taulukointi
		$nimi = readdir($hak);
		$nimi = readdir($hak);
		$nimi = readdir($hak);
		$i = 0;
		while ($nimi) {						
			if($nimi != 'thumbs'){				
				$kuvat[$i] = $nimi;
				$nimi = readdir($hak);
				$i++;						
				}	// kuvia on $i-1 kpl
				else
					$nimi = readdir($hak);
		}
		closedir($hak);		
		$exif = exif_read_data('galleria/'.$val, 0, true);
		foreach ($exif as $key => $section) {
			if($key == 'IFD0' || $key== 'WINXP'){
				foreach ($section as $name => $arvo) {	
					if($name == 'Keywords'){ $teksti = $arvo; }
				}
			}
		}
		if( $getinfo == '') {  // navigaatiopalkki
			
			echo "<td colspan=5 align='center' valign='top'><table border=0 cellpadding=0 width=100%><td background='steel3.jpg' align='center'>";			
			nuoli("roll", $ret);
			echo "&nbsp;&nbsp;&nbsp;
			<form style='display:inline;' name='lajinvalinta'>
			<select style='padding:5px;' name='laji' onchange='lajinvaihto()'>";
			if($laji == 'M_') echo "<option value='M_'>Mansikkakausi<option value=''>Kaikki kuvat<option value='k_'>Koirat<option value='h_'>Hevoset<option value='m_'>Muut eläimet"; 
			if($laji == '') echo "<option value=''>Kaikki kuvat<option value='M_'>Mansikkakausi<option value='k_'>Koirat<option value='h_'>Hevoset<option value='m_'>Muut eläimet"; 
			if($laji == 'k_') echo "<option value='k_'>Koirat<option value='h_'>Hevoset<option value='m_'>Muut eläimet<option value='M_'>Mansikkakausi<option value=''>Kaikki kuvat"; 
			elseif($laji == 'h_') echo "<option value='h_'>Hevoset<option value='m_'>Muut eläimet<option value='M_'>Mansikkakausi<option value=''>Kaikki kuvat<option value='k_'>Koirat"; 
			elseif($laji == 'm_') echo "<option value='m_'>Muut eläimet<option value='h_'>Hevoset<option value='k_'>Koirat<option value='M_'>Mansikkakausi<option value=''>Kaikki kuvat";
			echo "</select></form>";				
		}		
		for($g=0;$kuvat[$g] != $val;$g++) if($g > $i-1) { 
			echo "Virhe: kuvaa ei löydy."; exit; }
		
		if( $laji != '' ) { 		// kuvaryhmä valittu	
		
			for( $s=$g-1; $s >= 0; $s-- ) { // etsitään edellinen kuva				
				if ( $laji == substr($kuvat[$s], 0, 2) ) { $edellinen = $kuvat[$s]; break;}
			}
			for( $s=$g+1; $s <= $i-1; $s++ ) { // etsitään seuraava kuva				
				if ( $laji == substr($kuvat[$s], 0, 2) ) { $seur = $kuvat[$s]; break; }
			}
			if( $edellinen == '' ) { 			// jos edellistä ei löytynyt, jatketaan lopusta						 
				for( $s=$i-1; $s >= 0; $s-- ) { 				
					if ( $laji == substr($kuvat[$s], 0, 2) ) {  $edellinen = $kuvat[$s]; break;}
				}
			}			
			if( $seur == '' ) { 			// jos seuraavaa ei löytynyt, jatketaan alusta
				for( $s=0; $s <= $i - 1; $s++ ) { 					
					if ( $laji == substr($kuvat[$s], 0, 2) ) { $seur = $kuvat[$s]; break;}
				}
			}
			
			if( $edellinen != '' && $getinfo == '' ) {			
				nuoli('vasen',0);
			}
			if( $seur != '' && $getinfo == '' )	{			  
				nuoli('oikea',0);
			}
		} else {  				// ei kuvaryhmää, kaikki kuvat

			if($g > 0) {		
					$edellinen = $kuvat[$g - 1];
					if( $getinfo == '' ) { nuoli('vasen',0);
					}
			} else { 
					$edellinen = $kuvat[$i - 1];  // edellistä ei ole, jatketaan lopusta
					if( $getinfo == '' ){ nuoli('vasen',0);}
			}
			
			if($g < $i - 1) {
				$seur = $kuvat[$g + 1];
				if( $getinfo == '' ) { nuoli('oikea',0);  }				
			}	else { 	 // seuraavaa ei ole, jatketaan alusta
				$seur = $kuvat[0]; 
				if( $getinfo == '' ) { nuoli('oikea',0); }		
			}
		}
		
	if( $getinfo == '' ){			
	echo "</td></table><a href='galleria2.php'><img name='main' src='galleria/$val' /></a><br>$teksti<br>";	
		$tt = fopen( 'html2.txt', 'r' );
		while (!feof($tt)) {		
			$rivi = fgets($tt, 1024); 
			echo $rivi;
		}
		fclose($tt);
	}
	else {
	if($getinfo == "lajinvaihto"){  // kuvaryhmän vaihto		
		echo "
		<html><body>
		Jos tämä sivu jää näkyviin pitkäksi aikaa, paina <a href='galleria2.php?id=$seur&laji=$laji'>tästä</a>
		<script type='text/javascript'>
		i = 0;
		location.replace('galleria2.php?id=$seur&laji=$laji');
		</script>
		</body></html>;";
	}	
	if( $getinfo == "seuraava" ){ echo $seur; }
	if( $getinfo == "edellinen" ){ echo $edellinen; }	
	}
}

?>
