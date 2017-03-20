<?php

namespace App;

use App\ApplicationForm as af;
use CCSVData;
use CIBlock;
use CIBlockElement;
use Core\Underscore as _;
use Core\Iblock as ib;
use CUser;
use Core\View as v;
use CUtil;

// bitrix csv util
require_once ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/csv_data.php');

class Admin {
    private static function applicationsTable($name, $iblockPred) {
        $iblockIds = array_filter(af::iblockIds(), $iblockPred);
        $groupedByIblock = _::mapValues($iblockIds, function ($iblockId) {
            // TODO additional business logic filters?
            $filter = array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y');
            $elements = ib::collectElements((new CIBlockElement)->GetList(array('SORT' => 'ASC'), $filter));
            $row = _::mapValues($elements, function($el) {
                // TODO refactor: optimize
                $user = CUser::GetByID(_::get($el, 'PROPERTIES.USER.VALUE'))->GetNext();
                return array(
                    $user['~WORK_COMPANY'],
                    $user['~NAME'],
                    $user['~EMAIL'],
                    $user['~WORK_PHONE'],
                    $user['~WORK_STREET'],
                    $el['~IBLOCK_NAME']
                );
            });
            return $row;
        });
        $rows = array_reduce($groupedByIblock, function($acc, $rows) {
            return array_merge($acc, $rows);
        }, array());
        $header = array(
            'Название компании',
            'ФИО контактного лица',
            'Адрес электронной почты',
            'Телефон',
            'Фактический адрес',
            'Номинация'
        );
        if (!_::isEmpty($rows)) {
            assert(count($header) === count(_::first($rows)));
        }
        return array(
            'NAME' => $name,
            'HEADER' => $header,
            'ROWS' => $rows
        );
    }

    static function companyApplicationsTable() {
        return self::applicationsTable('Анкеты компаний поставщиков', function ($iblockId) {
            // TODO refactor: extract filter
            return $iblockId !== Iblock::GENERAL_INFO && !af::isPersonalNomination($iblockId);
        });
    }

    static function personalApplicationsTable() {
        return self::applicationsTable('Анкеты в персональной номинации', function ($iblockId) {
            // TODO refactor: extract filter
            return $iblockId !== Iblock::GENERAL_INFO && af::isPersonalNomination($iblockId);
        });
    }

    private static function filename($text) {
        $name = CUtil::translit($text, 'ru', array('replace_space' => '_', 'replace_other' => '-'));
        return join('_', array($name, date('d_m_Y'))).'.csv';
    }

    private static function table($params) {
        if ($params['summary'] === 'companies') {
            return self::companyApplicationsTable();
        } else if ($params['summary'] === 'personal') {
            return self::personalApplicationsTable();
        } else {
            return array();
        }
    }

    private static function writeCsvFile($table, $path) {
        // php unlink doesn't work with symlinks?
        $path = realpath($path);
        if (file_exists($path)) {
            unlink($path);
        }
        // TODO bug: file encoding is utf-8 (content is 1251)
        $csv = new CCSVData('R', true);
        $csv->SetDelimiter(';');
        $rows = array_merge(array($table['HEADER']), $table['ROWS']);
        foreach ($rows as $row) {
            // TODO refactor: wth? can we write all rows at once?
            $csv->SaveFile($path, array_map(function($s) {
                // just in case we pass escaped strings
                return iconv('utf-8', 'windows-1251', htmlspecialcharsBack($s));
            }, array_values($row)));
        }
        return $path;
    }

    static function render($params) {
        if (User::ensureUserIsAdmin()) {
            $view = _::get($params, 'view', 'index');
            if ($view === 'table') {
                $table = self::table($params);
                if (!_::isEmpty($table)) {
                    $csvPath = '/admin/files/'.self::filename($table['NAME']);
                    self::writeCsvFile($table, $_SERVER['DOCUMENT_ROOT'].$csvPath);
                    // TODO personal
                    return v::twig()->render(v::partial('admin/table.twig'), array(
                        'table' => $table,
                        // TODO require authorization
                        'download_link' => $csvPath
                    ));
                } else {
                    // TODO handle invalid params
                    return '';
                }
            } else {
                $iblocks = ib::collect(CIBlock::GetList(array('SORT' => 'ASC'), array('IBLOCK_TYPE')));
                $iblockIds = ApplicationForm::iblockIds();
                $nominations = array_filter($iblocks, function($iblock) use ($iblockIds) {
                    $iblockId = intval($iblock['ID']);
                    // TODO refactor: extract filter
                    return $iblockId !== Iblock::GENERAL_INFO && in_array($iblockId, $iblockIds);
                });
                return v::twig()->render(v::partial('admin/index.twig'), array('nominations' => $nominations));
            }
        } else {
            return '';
        }
    }
}