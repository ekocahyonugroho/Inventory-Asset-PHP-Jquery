<?php
/*
   Copyright (c) 2003, 2009 Danilo Segan <danilo@kvota.net>.
   Copyright (c) 2005 Nico Kaiser <nico@siriux.net>

   This file is part of PHP-gettext.

   PHP-gettext is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   PHP-gettext is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with PHP-gettext; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/**
 * Provides a simple gettext replacement that works independently from
 * the system's gettext abilities.
 * It can read MO files and use them for translating strings.
 * The files are passed to gettext_reader as a Stream (see streams.php)
 *
 * This version has the ability to cache all strings and translations to
 * speed up the string lookup.
 * While the cache is enabled by default, it can be switched off with the
 * second parameter in the constructor (e.g. whenusing very large MO files
 * that you don't want to keep in memory)
 */


 class StringReader {
   var $_pos;
   var $_str;

   function StringReader($str='') {
     $this->_str = $str;
     $this->_pos = 0;
   }

   function read($bytes) {
     $data = substr($this->_str, $this->_pos, $bytes);
     $this->_pos += $bytes;
     if (strlen($this->_str)<$this->_pos)
       $this->_pos = strlen($this->_str);

     return $data;
   }

   function seekto($pos) {
     $this->_pos = $pos;
     if (strlen($this->_str)<$this->_pos)
       $this->_pos = strlen($this->_str);
     return $this->_pos;
   }

   function currentpos() {
     return $this->_pos;
   }

   function length() {
     return strlen($this->_str);
   }

 };

  ?>
