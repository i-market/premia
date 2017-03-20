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
    static function companyApplicationsTable() {
        $iblockIds = array_filter(af::iblockIds(), function($id) {
            // TODO refactor: extract filter
            return $id !== Iblock::GENERAL_INFO && !af::isPersonalNomination($id);
        });
        $groupedByIblock = _::mapValues($iblockIds, function ($iblockId) {
            // TODO additional business logic filters?
            $filter = array('IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y');
            $elements = ib::collectElements((new CIBlockElement)->GetList(array('SORT' => 'ASC'), $filter));
            $row = _::mapValues($elements, function ($el) {
                // TODO refactor: optimize
                $user = CUser::GetByID(_::get($el, 'PROPERTIES.USER.VALUE'))->GetNext();
                return array(
                    'COMPANY' => $user['~WORK_COMPANY'],
                    'FULL_NAME' => $user['~NAME'],
                    'EMAIL' => $user['~EMAIL'],
                    'PHONE' => $user['~WORK_PHONE'],
                    'ADDRESS' => $user['~WORK_STREET'],
                    'NOMINATION' => $el['~IBLOCK_NAME']
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
            'NAME' => 'Анкеты компаний поставщиков',
            'HEADER' => $header,
            'ROWS' => $rows
        );
    }

    private static function filename($text) {
        $name = CUtil::translit($text, 'ru', array('replace_space' => '_', 'replace_other' => '-'));
        return join('_', array($name, date('d_m_Y'), uniqid())).'.csv';
    }

    private static function table($params) {
        if ($params['summary'] === 'companies') {
            return self::companyApplicationsTable();
        } else {
            return array();
        }
    }

    private static function writeCsvFile($table, $path) {
        $csv = new CCSVData('R', true);
        $csv->SetDelimiter(';');
        $rows = array_merge(array($table['HEADER']), $table['ROWS']);
        foreach ($rows as $row) {
            // TODO refactor: wth? can we write all rows at once?
            $csv->SaveFile($path, array_map(function($s) {
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