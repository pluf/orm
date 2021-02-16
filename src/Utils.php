<?php

// /*
//  * This file is part of Pluf Framework, a simple PHP Application Framework.
//  * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
//  *
//  * This program is free software: you can redistribute it and/or modify
//  * it under the terms of the GNU General Public License as published by
//  * the Free Software Foundation, either version 3 of the License, or
//  * (at your option) any later version.
//  *
//  * This program is distributed in the hope that it will be useful,
//  * but WITHOUT ANY WARRANTY; without even the implied warranty of
//  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//  * GNU General Public License for more details.
//  *
//  * You should have received a copy of the GNU General Public License
//  * along with this program. If not, see <http://www.gnu.org/licenses/>.
//  */
// namespace Pluf\Orm;

// class Utils
// {

//     /**
//      * Produces a random string.
//      *
//      * @param
//      *            int Length of the random string to be generated.
//      * @return string Random string
//      */
//     static function getRandomString($len = 35)
//     {
//         $string = '';
//         $chars = '0123456789abcdefghijklmnopqrstuvwxyz' . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&*()+=-_}{[]><?/';
//         $lchars = strlen($chars);
//         $i = 0;
//         while ($i < $len) {
//             $string .= substr($chars, mt_rand(0, $lchars - 1), 1);
//             $i ++;
//         }
//         return $string;
//     }

//     /**
//      * Produces a random string contains only alphanumeric characters
//      *
//      * @param
//      *            int Length of the random string to be generated.
//      * @return string Random string
//      */
//     static function getRandomAlphanumericString($len = 35)
//     {
//         $string = '';
//         $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//         $lchars = strlen($chars);
//         $i = 0;
//         while ($i < $len) {
//             $string .= substr($chars, mt_rand(0, $lchars - 1), 1);
//             $i ++;
//         }
//         return $string;
//     }

//     /**
//      * Produces a random string contains only numeric characters
//      *
//      * @param
//      *            int Length of the random string to be generated.
//      * @return string Random string
//      */
//     static function getRandomNumericString($len = 10)
//     {
//         $string = '';
//         $chars = '0123456789';
//         $lchars = strlen($chars);
//         $i = 0;
//         while ($i < $len) {
//             $string .= substr($chars, mt_rand(0, $lchars - 1), 1);
//             $i ++;
//         }
//         return $string;
//     }

//     /**
//      * Produces a random password.
//      *
//      * The random password generator avoid characters that can be
//      * confused like 0,O,o,1,l,I.
//      *
//      * @param
//      *            int Length of the password (8)
//      * @return string Password
//      */
//     static function getPassword($len = 8)
//     {
//         $string = '';
//         $chars = '23456789abcdefghijkmnpqrstuvwxyz' . 'ABCDEFGHJKLMNPQRSTUVWXYZ';
//         $lchars = strlen($chars);
//         $i = 0;
//         while ($i < $len) {
//             $string .= substr($chars, mt_rand(0, $lchars - 1), 1);
//             $i ++;
//         }
//         return $string;
//     }

//     /**
//      * Clean the name of a file to only have alphanumeric characters.
//      *
//      * @param
//      *            string Name
//      * @return string Clean name
//      */
//     static function cleanFileName($name)
//     {
//         return mb_ereg_replace("/\015\012|\015|\012|\s|[^A-Za-z0-9\.\-\_]/", '_', $name);
//     }

//     static function prettySize($size)
//     {
//         switch (strtolower(substr($size, - 1))) {
//             case 'k':
//                 $size = substr($size, 0, - 1) * 1000;
//                 break;
//             case 'm':
//                 $size = substr($size, 0, - 1) * 1000 * 1000;
//                 break;
//             case 'g':
//                 $size = substr($size, 0, - 1) * 1000 * 1000 * 1000;
//                 break;
//         }
//         if ($size > (1000 * 1000 * 1000)) {
//             $mysize = sprintf('%01.2f', $size / (1000 * 1000 * 1000)) . ' ' . __('GB');
//         } elseif ($size > (1000 * 1000)) {
//             $mysize = sprintf('%01.2f', $size / (1000 * 1000)) . ' ' . __('MB');
//         } elseif ($size >= 1000) {
//             $mysize = sprintf('%01.2f', $size / 1000) . ' ' . __('kB');
//         } else {
//             $mysize = sprintf(_n('%d byte', '%d bytes', $size), $size);
//         }
//         return $mysize;
//     }


//     /**
//      * Convert a whatever separated list of items and returns an array
//      * of them.
//      *
//      * @param
//      *            string Items.
//      * @param
//      *            string Separator (',')
//      * @return array Items.
//      */
//     static function itemsToArray($items, $sep = ',')
//     {
//         $_t = explode($sep, $items);
//         $res = array();
//         foreach ($_t as $item) {
//             $item = trim($item);
//             if (strlen($item) > 0) {
//                 $res[] = $item;
//             }
//         }
//         return $res;
//     }

//     /**
//      * Run an external program capturing both stdout and stderr.
//      *
//      * @credits dk at brightbyte dot de
//      * @source http://www.php.net/manual/en/function.shell-exec.php
//      *
//      * @param
//      *            string Command to run (will be passed to proc_open)
//      * @param
//      *            &int Return code of the command
//      * @return mixed false in case of error or output string
//      */
//     public static function runExternal($cmd, &$code)
//     {
//         $descriptorspec = array(
//             // stdin is a pipe that the child will read from
//             0 => array(
//                 'pipe',
//                 'r'
//             ),
//             // stdout is a pipe that the child will write to
//             1 => array(
//                 'pipe',
//                 'w'
//             ),
//             // stderr is a file to write to
//             2 => array(
//                 'pipe',
//                 'w'
//             )
//         );
//         $pipes = array();
//         $process = proc_open($cmd, $descriptorspec, $pipes);
//         $output = '';
//         if (! is_resource($process))
//             return false;
//         fclose($pipes[0]); // close child's input imidiately
//         stream_set_blocking($pipes[1], false);
//         stream_set_blocking($pipes[2], false);
//         // $todo = array(
//         // $pipes[1],
//         // $pipes[2]
//         // );
//         while (true) {
//             $read = array();
//             if (! feof($pipes[1]))
//                 $read[] = $pipes[1];
//             if (! feof($pipes[2]))
//                 $read[] = $pipes[2];
//             if (! $read)
//                 break;
//             $write = $ex = array();
//             $ready = stream_select($read, $write, $ex, 2);
//             if ($ready === false) {
//                 break; // should never happen - something died
//             }
//             foreach ($read as $r) {
//                 $s = fread($r, 1024);
//                 $output .= $s;
//             }
//         }
//         fclose($pipes[1]);
//         fclose($pipes[2]);
//         $code = proc_close($process);
//         return $output;
//     }

//     /**
//      * URL safe base 64 encoding.
//      *
//      * Compatible with python base64's urlsafe methods.
//      *
//      * @link http://www.php.net/manual/en/function.base64-encode.php#63543
//      */
//     public static function urlsafe_b64encode($string)
//     {
//         return str_replace(array(
//             '+',
//             '/',
//             '='
//         ), array(
//             '-',
//             '_',
//             ''
//         ), base64_encode($string));
//     }

//     /**
//      * URL safe base 64 decoding.
//      */
//     public static function urlsafe_b64decode($string)
//     {
//         $data = str_replace(array(
//             '-',
//             '_'
//         ), array(
//             '+',
//             '/'
//         ), $string);
//         $mod4 = strlen($data) % 4;
//         if ($mod4) {
//             $data .= substr('====', $mod4);
//         }
//         return base64_decode($data);
//     }

//     /**
//      * Flatten an array.
//      *
//      * @param array $array
//      *            The array to flatten.
//      * @return array
//      */
//     public static function flattenArray($array)
//     {
//         $result = array();
//         foreach (new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array)) as $value) {
//             $result[] = $value;
//         }

//         return $result;
//     }
// }
