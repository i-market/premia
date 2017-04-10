<?php

namespace App;

use App\ApplicationForm as af;
use CCSVData;
use CIBlock;
use CIBlockElement;
use Core\Underscore as _;
use Core\Iblock as ib;
use Core\Nullable as nil;
use CUser;
use Core\View as v;
use CUtil;
use WS_PSettings;

// bitrix csv util
require_once ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/csv_data.php');

class Admin {
    static function layoutContext() {
        return array(
            'next_award_suggestion' => intval(App::getActiveAward()) + 1
        );
    }

    private static function applicationsTable($name, $iblockPred) {
        $iblockIds = array_filter(af::iblockIds(), $iblockPred);
        $groupedByIblock = _::mapValues($iblockIds, function($iblockId) {
            // TODO additional business logic filters?
            $filter = array_merge(array('IBLOCK_ID' => $iblockId), af::activeFilter());
            $elements = ib::collectElements((new CIBlockElement)->GetList(array('SORT' => 'ASC'), $filter));
            $rows = _::clean(_::mapValues($elements, function($el) {
                // TODO refactor: optimize
                $user = CUser::GetByID(_::get($el, 'PROPERTIES.USER.VALUE'))->GetNext();
                if (!$user) {
                    return null;
                }
                return array(
                    $user['~WORK_COMPANY'],
                    $user['~NAME'],
                    $user['~EMAIL'],
                    $user['~WORK_PHONE'],
                    $user['~WORK_STREET'],
                    $el['~IBLOCK_NAME']
                );
            }));
            return $rows;
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
        return self::applicationsTable('Анкеты компаний поставщиков', function($iblockId) {
            // TODO refactor: extract filter
            return $iblockId !== Iblock::GENERAL_INFO && !af::isPersonalNomination($iblockId);
        });
    }

    static function personalApplicationsTable() {
        return self::applicationsTable('Анкеты в персональной номинации', function($iblockId) {
            // TODO refactor: extract filter
            return $iblockId !== Iblock::GENERAL_INFO && af::isPersonalNomination($iblockId);
        });
    }

    static function nominationSummaryTable($iblockId) {
        $voteIblockId = af::voteIblockId($iblockId);
        // TODO clean up
        /** @noinspection PhpPassByRefInspection */
        $experts = ib::collect(CUser::GetList(($by = "NAME"), ($order = "asc"), Array("GROUPS_ID"=>Array(User::EXPERT_GROUP), "ACTIVE"=>"Y"), Array("FIELDS"=>Array("ID", "NAME"))));
        $header = array_merge(
            array(
                'Название компании',
                'ФИО контактного лица',
                'Адрес электронной почты',
                'Фактический адрес',
            ),
            // [Общий балл каждого эксперта]
            _::pluck($experts, 'NAME'),
            array(
                'Общий балл',
                'Средний балл'
            )
        );
        // TODO additional business logic filters?
        $filter = array_merge(array('IBLOCK_ID' => $iblockId), af::activeFilter());
        $forms = ib::collectElements((new CIBlockElement)->GetList(array('SORT' => 'ASC'), $filter));
        $votes = ib::collectElements((new CIBlockElement)->GetList(array('SORT' => 'ASC'), array(
            'IBLOCK_ID' => $voteIblockId, 'ACTIVE' => 'Y'
        )));
        $formVotes = _::groupBy($votes, 'PROPERTIES.FORM.VALUE');
        $rows = _::clean(_::mapValues($forms, function($form) use ($voteIblockId, $experts, $formVotes) {
            // TODO refactor: optimize
            $user = CUser::GetByID(_::get($form, 'PROPERTIES.USER.VALUE'))->GetNext();
            if (!$user) {
                return null;
            }
            $votes_ = _::get($formVotes, $form['ID'], array());
            // [Общий балл каждого эксперта] name → overall score
            $expertOverallScores = array_reduce($experts, function($scores, $expert) use ($votes_) {
                $voteMaybe = _::find($votes_, function($vote) use ($expert) {
                    return _::get($vote, 'PROPERTIES.USER.VALUE') === intval($expert['ID']);
                });
                // important to assoc even if there is no vote
                return _::set($scores, $expert['NAME'], nil::map($voteMaybe, function($vote) {
                    return Vote::overallScore($vote);
                }));
            }, array());
            // important to get rid of null votes
            $cleanScores = _::clean(array_values($expertOverallScores));
            // Общий балл
            $overallScore = array_sum($cleanScores);
            // Средний балл (общий балл/количество оценок)
            $averageScore = _::isEmpty($cleanScores) ? 0 : $overallScore / count($cleanScores);
            return array_merge(
                array(
                    $user['~WORK_COMPANY'],
                    $user['~NAME'],
                    $user['~EMAIL'],
                    $user['~WORK_STREET'],
                ),
                $expertOverallScores,
                array(
                    $overallScore,
                    $averageScore
                )
            );
        }));
        if (_::isEmpty($forms)) {
            trigger_error('empty application form set', E_USER_WARNING);
        }
        return array(
            'NAME' => nil::get($forms[0]['~IBLOCK_NAME'], ''),
            'HEADER' => $header,
            'ROWS' => $rows
        );

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
        } else if (isset($params['nomination_id'])) {
            return self::nominationSummaryTable($params['nomination_id']);
        } else {
            trigger_error('invalid request params', E_USER_WARNING);
            return null;
        }
    }

    private static function writeCsvFile($table, $path) {
        if (file_exists($path)) {
            // php unlink doesn't work with symlinks?
            unlink(realpath($path));
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

    private static function nominations() {
        $iblocks = ib::collect(CIBlock::GetList(array('SORT' => 'ASC'), array('IBLOCK_TYPE')));
        $iblockIds = ApplicationForm::iblockIds();
        return array_filter($iblocks, function($iblock) use ($iblockIds) {
            $iblockId = intval($iblock['ID']);
            // TODO refactor: extract filter
            return $iblockId !== Iblock::GENERAL_INFO && in_array($iblockId, $iblockIds);
        });
    }

    private static function renderIndex($flashMessage = null) {
        $nominations = self::nominations();
        $awardStateText = array(
            AwardState::OPEN => 'Конкурс открыт',
            AwardState::LOCK_APPLICATIONS => 'Завершение подачи заявок',
            AwardState::CLOSED => 'Завершение конкурса'
        )[App::state()['AWARD_STATE']];
        $ctx = array(
            'flash' => $flashMessage,
            'nominations' => $nominations,
            'status_options' => ApplicationForm::statusOptions(),
            'award_state' => $awardStateText,
            'active_award' => App::getActiveAward()
        );
        return v::twig()->render(v::partial('admin/index.twig'), $ctx);
    }

    private static function renderTable($table, $setTitle) {
        global $APPLICATION;
        $csvPath = '/admin/files/'.self::filename($table['NAME']);
        self::writeCsvFile($table, $_SERVER['DOCUMENT_ROOT'].$csvPath);
        if ($setTitle) {
            // mutate
            $APPLICATION->SetTitle($table['NAME']);
        }
        return v::twig()->render(v::partial('admin/table.twig'), array(
            'table' => $table,
            // TODO require authorization
            'download_link' => $csvPath
        ));
    }

    static function render($params, $setTitle) {
        global $APPLICATION;
        if (User::ensureUserIsAdmin()) {
            $view = _::get($params, 'view', 'index');
            if ($view === 'table') {
                $table = self::table($params);
                if ($table !== null) {
                    return self::renderTable($table, $setTitle);
                } else {
                    return '';
                }
            } else if ($params['action'] === 'change-award-state') {
                WS_PSettings::setFieldValue(App::AWARD_STATE_SETTING, $params['to']);
                return self::renderIndex('Состояние конкурса изменено.');
            } else if ($params['action'] === 'set-active-award') {
                $award = $params['to'];
                App::setActiveAward($award);
                return self::renderIndex('Конкурс «'.$award.'» запущен.');
            } else if ($params['view'] === 'nominees') {
                $fields = array('EMAIL', 'NAME');
                $table = array(
                    'NAME' => 'Все участники конкурса',
                    'HEADER' => array('Адрес электронной почты', 'ФИО контактного лица'),
                    'ROWS' =>
                        array_map(function($user) use ($fields) {
                            return _::pick($user, $fields);
                        }, User::nominees($fields))

                );
                return self::renderTable($table, $setTitle);
            } else if ($params['view'] === 'by-status') {
                $option = _::find(ApplicationForm::statusOptions(), function($option) use ($params) {
                    return $option['XML_ID'] === $params['status'];
                });
                $fields = array('EMAIL', 'NAME');
                $groups = User::groupByAppStatus(ApplicationForm::all(), $fields);
                $users = $groups[$option['XML_ID']];
                $table = array(
                    'NAME' => $option['VALUE'],
                    'HEADER' => array('Адрес электронной почты', 'ФИО контактного лица'),
                    'ROWS' =>
                        array_map(function($user) use ($fields) {
                            return _::pick($user, $fields);
                        }, $users)

                );
                return self::renderTable($table, $setTitle);
            } else if ($params['view'] === 'by-nomination') {
                $nomination = _::find(self::nominations(), function($iblock) use ($params) {
                    return $iblock['ID'] === $params['nomination_id'];
                });
                $fields = array('EMAIL', 'NAME');
                $groups = User::groupByNomination(ApplicationForm::all(), $fields);
                $users = $groups[$nomination['ID']];
                $table = array(
                    'NAME' => $nomination['NAME'],
                    'HEADER' => array('Адрес электронной почты', 'ФИО контактного лица'),
                    'ROWS' =>
                        array_map(function($user) use ($fields) {
                            return _::pick($user, $fields);
                        }, $users)

                );
                return self::renderTable($table, $setTitle);
            } else {
                return self::renderIndex();
            }
        } else {
            return '';
        }
    }
}