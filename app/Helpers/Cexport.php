<?php
namespace App\Helpers;

/**
 * @file
 * Content administration and module settings UI.
 */
require_once realpath(__DIR__) . '/Spout/src/Box/Spout/Autoloader/autoload.php';

use Box\Spout\Export;

define('UPLOAD_TEMP', storage_path('app/export'));

class Cexport
{
    public static function test() {
        $arrData = [
            [
                'full_name' => 'Nguyễn Văn A',
                'email'		=> 'abc@gmail.com',
                'address' 	=> 'Quận 7, Hồ Chí Minh',
                'created_date' => '2017-06-05 15:36:27'
            ]
        ];

        $oExport = new Export('test'); // Tên file
        $oExport->setTitle('Danh sách thành viên') // Title cell
            ->setHeader(['STT', 'Họ tên', 'Email', 'Địa chỉ', 'Ngày tạo'])
            ->setFieldDisplay(['full_name', 'email', 'address', 'created_date'])
            ->setData($arrData);

        $oExport->save();
        die();
    }

    public static function export($filename, $title, $header, $fields, $data) {

        $oExport = new Export( $filename ); // Tên file
        $oExport->setTitle( $title ) // Title cell
        ->setHeader( $header )
            ->setFieldDisplay( $fields )
            ->setData( $data );

        $oExport->save();
        die();
    }

    public static function createFile($filename, $title, $header, $fields, $data) {

        $oExport = new Export( $filename ); // Tên file
        $oExport->setTitle( $title ) // Title cell
        ->setHeader( $header )
            ->setFieldDisplay( $fields )
            ->setData( $data );

        $tmpFile = $oExport->createFile();

        return $tmpFile;
    }

    public static function downloadFile($filename,$file){
        $oExport = new Export($filename);

        $oExport->downloadFile($file);
    }
}