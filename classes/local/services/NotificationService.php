<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version metadata for the local_manage_enrollments plugin.
 *
 * @package   local_certificate_management
 * @copyright 2025 Lucas Mendes <lucas.mendes.dev@outlook.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_certificate_management\local\services;

use core_user;
use core\message\message;


class NotificationService
{
    private static ?NotificationService $service = null;

    /**
     * Sends a moodle notification of the certificate issued.
     *
     * @param \stdClass $issue
     * @param \stored_file $file
     */
    public function sendHistoryNotification(array $data, \stored_file $file): void {
        $user = core_user::get_user($data['userId']);

        $userfullname = fullname($user, true);
        $subject = get_string('history_available', 'local_certificate_management');
        $fullmessage = get_string(
            'history_body_message',
            'local_certificate_management',
            ['name' => $userfullname, 'url' => $data['history']->out(false)]
        );

        $message = new message();
        $message->courseid = $issue->courseid ?? SITEID;
        $message->component = 'local_certificate_management';
        $message->name = 'certificatehistoryissued';
        $message->notification = 1;
        $message->userfrom = core_user::get_noreply_user();
        $message->userto = $user;
        $message->subject = $subject;
        $message->contexturl = $data['history'];
        $message->contexturlname = $subject;
        $message->fullmessage = html_to_text($fullmessage);
        $message->fullmessagehtml = $fullmessage;
        $message->fullmessageformat = FORMAT_HTML;
        $message->smallmessage = '';
        $message->attachment = $file;
        $message->attachname = $file->get_filename();

        message_send($message);
    }


    public static function getService(): NotificationService
    {
        if (self::$service === null) {
            self::$service = new self();
        }

        return self::$service;
    }
}