<?php
    function PhysicsBibliographyStyle(&$bibentry) {
      // This function provides a bibtex style close to the APS journal style yet
      // with customized links and fields added at the end of the bibtex entries.
      // Written by Xiaodong Qi (qxd @ unm.edu) 2015-2016.
      // This program is free software; you can redistribute it and/or
      // modify it under the terms of the GNU General Public License as
      // published by the Free Software Foundation; either version 2 of the
      // License, or (at your option) any later version.
      $title = $bibentry->getTitle();
      $type = $bibentry->getType();

      // later on, all values of $entry will be joined by a comma
      $entry=array();

      // title
      // usually in bold: .bibtitle { font-weight:bold; }
      $title = '<span class="bibtitle"  itemprop="name">&quot;'.$title.'&quot;</span>';
      if ($bibentry->hasField('url')) $title = ' <a'.get_target().' href="'.$bibentry->getField('url').'">'.$title.'</a>';


      // author
      // All authors will be abbreviated for their first names, and the last author--if more than 1--will be after the key word "and".
      $author = '';
      if ($bibentry->hasField('author')) {
        $authors = $bibentry->getRawAuthors();
    		for ($i = 0; $i < count($authors); $i++) {
    			$a = ($bibentry->formatAuthor($authors[$i]));//$authors[$i];
    			// check author format; "Firstname Lastname" or "Lastname, Firstname"
    			if (strpos($a, ',') === false) {
    				$parts = explode(' ', $a);
    				$lastname = trim(array_pop($parts));
    				$firstnames = $parts;
    			} else {
    				$parts = explode(',', $a);
    				$lastname = trim($parts[0]);
    				$firstnames = explode(' ', trim($parts[1]));
    			}
          //list($firstnames, $lastname) = splitFullName($a);
    			$name = array();
          // Abbreviate the first name part.
    			foreach ($firstnames as $fn){
            if (strpos($fn, '-') === false) {
    				  $name[] = preg_replace("/(\p{Lu})\w*[- ]*/Su","$1.", $fn);//substr(trim($fn), 0, 1) . '.';//This will handle most of case.
            } else {
              $name[] = preg_replace("/(\p{Lu})\w*([-\s])(\p{Lu})\w*/Su","$1.$2$3.", $fn);//This takes care of names separated with "-".
            }//Notice that if the first name has two capital letters next to each other without space, this program will only abbreviate the first letter.
          }
          // do not forget the author links if available
    			if (BIBTEXBROWSER_AUTHOR_LINKS=='homepage') {
    				$authors[$i] = $bibentry->addHomepageLink(implode(' ', $name) . ' ' . $lastname);
    			}
    			if (BIBTEXBROWSER_AUTHOR_LINKS=='resultpage') {
    				$authors[$i] = $bibentry->addAuthorPageLink(implode(' ', $name) . ' ' . $lastname);
    			}
          // Connect the last author with 'and' or deal with single author case.
          if ($i < count($authors)-1) {
              $author .= $authors[$i] . ', ';
          }
          else {
               if (count($authors)>1) {
                  $author .= 'and ' . $authors[$i] . ', ';
               }
               else $author .= $authors[$i] . ', ';
          }
    		}
        $coreInfo = ' <span class="bibauthor">'.$author.'</span>' . $title ;}
      else $coreInfo = $title;

      // core info usually contains title + author
      $entry[] = $coreInfo;

      // now the book title
      $booktitle = '';
      if ($type=="inproceedings") {
          $booktitle = '<span itemprop="isPartOf">'.$bibentry->getField(BOOKTITLE).'</span>'; }
      if ($type=="incollection") {
          $booktitle = __('in').' '.'<span itemprop="isPartOf">'.$bibentry->getField(BOOKTITLE).'</span>';}
      if ($type=="inbook") {
          $booktitle = __('in').' '.$bibentry->getField('chapter');}
      if ($type=="article") {
          $booktitle = '<span itemprop="isPartOf">'.$bibentry->getField("journal").'</span>';}

      //// we may add the editor names to the booktitle; all first names are abbreviated.
      $editor='';
      if ($bibentry->hasField(EDITOR)) {
        $editors = $bibentry->getEditors();
  		for ($i = 0; $i < count($editors); $i++) {
  			$a = $editors[$i];
  			// check author format; "Firstname Lastname" or "Lastname, Firstname"
  			if (strpos($a, ',') === false) {
  				$parts = explode(' ', $a);
  				$lastname = trim(array_pop($parts));
  				$firstnames = $parts;
  			} else {
  				$parts = explode(',', $a);
  				$lastname = trim($parts[0]);
  				$firstnames = explode(' ', trim($parts[1]));
  			}
  			$name = array();
  			foreach ($firstnames as $fn){
          if (strpos($fn, '-') === false) {
            $name[] = preg_replace("/(\p{Lu})\w*[- ]*/Su","$1.", $fn);//substr(trim($fn), 0, 1) . '.';//This will handle most of case.
          } else {
            $name[] = preg_replace("/(\p{Lu})\w*([-\s])(\p{Lu})\w*/Su","$1.$2$3.", $fn);//This takes care of names separated with "-".
          }//Notice that if the first name has two capital letters next to each other without space, this program will only abbreviate the first letter.
        }
  			// do not forget the author links if available
  			if (BIBTEXBROWSER_AUTHOR_LINKS=='homepage') {
  				$editors[$i] = $bibentry->addHomepageLink(implode(' ', $name) . ' ' . $lastname);
  			}
  			if (BIBTEXBROWSER_AUTHOR_LINKS=='resultpage') {
  				$editors[$i] = $bibentry->addAuthorPageLink(implode(' ', $name) . ' ' . $lastname);
  			}
        // Connection to the last editor or handle the single editor case.
        if ($i < count($editors)-1) {
            $editor .= $editors[$i] . ', ';
        }
        else {
             if (count($editors)>1) {
                $editor .= 'and ' . $editors[$i] . ', ';
             }
             else $editor .= $editors[$i] . ', ';
        }
  		}
        // $editor = $bibentry->getFormattedEditors();
      }
      if ($editor!='') $booktitle .=', edited by '.$editor;
      // end editor section

      // is the booktitle available
      if ($booktitle!='') {
        $entry[] = '<span class="bibbooktitle">'.$booktitle.'</span>';
      }


      $publisher='';
      if ($type=="phdthesis") {
          $publisher = __('PhD thesis').', '.$bibentry->getField(SCHOOL);
      }
      if ($type=="mastersthesis") {
          $publisher = __('Master\'s thesis').', '.$bibentry->getField(SCHOOL);
      }
      if ($type=="bachelorsthesis") {
          $publisher = __('Bachelor\'s thesis').', '.$bibentry->getField(SCHOOL);
      }
      if ($type=="techreport") {
          $publisher = __('Technical report');
          if ($bibentry->hasField("number")) {
              $publisher .= ' '.$bibentry->getField("number");
          }
          $publisher .= ', '.$bibentry->getField("institution");
      }

      if ($type=="misc") {
          $publisher = $bibentry->getField('howpublished');
      }

      if ($bibentry->hasField("publisher")) {
        $publisher = $bibentry->getField("publisher");
      }
      /* Omit publisher for journal articles. */
      if ($type=="article") { $publisher=''; }

      if ($publisher!='') $entry[] = '<span class="bibpublisher">'.$publisher.'</span>';

      /* Volume and issue number (some journals use Issue while some use Number.) */
      if ($bibentry->hasField('volume')) { //$entry[] =  __('volume').' '.$bibentry->getField("volume");
         /* if ($bibentry->hasField('number')) {$entry[] = '<span itemprop="volumenumber">'.$bibentry->getField("volume").'</span>'.'(<span itemprop="issuenumber">'.$bibentry->getField("number").'</span>)';}
         elseif ($bibentry->hasField('issue')) {$entry[] = '<span itemprop="volumenumber">'.$bibentry->getField("volume").'</span>'.'(<span itemprop="issuenumber">'.$bibentry->getField("issue").'</span>)';}
         else $entry[] =  __('volume').' '.$bibentry->getField("volume");  */
         $entry[] = '<span itemprop="volume"><b>'.$bibentry->getField("volume").'</b></span>';
        }


      if ($bibentry->hasField(YEAR)) {
            if ($bibentry->hasField('pages')) {
                $entry[] = '<span itemprop="pagenumbers">'.$bibentry->getField("pages").'</span> <span itemprop="datePublished">('.$bibentry->getYear().')</span>';
            }
            else
                $entry[] = '<span itemprop="datePublished">('.$bibentry->getYear().')</span>';
      }
      else {
            if ($bibentry->hasField('pages')) {
                $entry[] = '<span itemprop="pagenumbers">'.$bibentry->getField("pages").'</span>';}
      }


      // DOI link.
      // if ($bibentry->hasField('doi')) {$entry[] = '<span itemprop="doilink"><a href=http://doi.org/"'.$bibentry->getField('doi').'">DOI:'.$bibentry->getField("doi").'</a></span>';}

      $result = implode(", ",$entry).'.';

      // some comments (e.g. acceptance rate)?
      if ($bibentry->hasField('comment')) {
          $result .=  " <span class=\"bibcomment\">(".$bibentry->getField("comment").")</span>";
      }
      if ($bibentry->hasField('note')) {
          $result .=  " [".$bibentry->getField("note")."]";
      }

      // add the Coin URL
      $result .=  $bibentry->toCoins();

      return '<span itemscope itemtype="http://schema.org/ScholarlyArticle">'.$result.'</span>';
    }
?>
