<?php

namespace src;

class Status {
    const NEW_STATUS = 1;
    const REJECTED = 2;
    const ACCEPTED = 3;

    public static function getAll() {
        return [
            self::NEW_STATUS => 'Новая',
            self::REJECTED => 'Отклонить',
            self::ACCEPTED => 'Принять'
        ];
    }

    public static function getName($id) {
        $statuses = self::getAll();
        return $statuses[$id] ?? 'Неизвестный статус';
    }
}