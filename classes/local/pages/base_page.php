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

namespace local_certificate_management\local\pages;

use stdClass;
use moodle_page;
use context_user;
use RuntimeException;
use core\output\templatable;
use core\output\renderer_base;
use core\exception\require_login_exception;

require_once(__DIR__ . '/../../../../../config.php');

abstract class base_page
{
    protected moodle_page $page;
    protected stdClass $user;
    protected string $pageTitle;
    protected string $pageUrl;

    protected string $pageName;
    protected renderer_base $renderer;
    protected templatable $pageToRender;

    protected array $params;

    protected bool $requireAdmin = false;
    protected bool $permitGuestUser = false;


    /**
     * @throws \coding_exception
     */
    public function __construct()
    {
        global $PAGE, $USER;
        $this->page = $PAGE;
        $this->user = $USER;
        $this->setPageToRender();
        $this->setPageName();
        $this->buildPage();
        $this->renderer = $PAGE->get_renderer('local_certificate_management');
    }

    /**
     * Get base url
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return '/local/certificate_management/';
    }

    /**
     * Build current page url
     * @return void
     */
    private function buildPageUrl(): void
    {
        $this->pageUrl = $this->getBaseUrl() . $this->pageName . '.php';
    }

    /**
     * Get page title from translation
     * @return void
     * @throws \coding_exception
     */
    private function buildPageTitle(): void
    {
        $this->pageTitle = get_string($this->pageName, 'local_certificate_management');
    }

    /**
     * Build page params
     * @throws \coding_exception
     */
    private function buildPage(): void
    {
        $this->buildPageUrl();
        $this->buildPageTitle();
        $this->page->set_context(context_user::instance($this->user->id));
        $this->page->set_url($this->pageUrl);
        $this->page->set_title($this->pageTitle);
    }

    /**
     * Render Page HTML
     * @return void
     * @throws require_login_exception
     */

    public function render_page(): void
    {
        $this->manageAccess();
        echo $this->renderer->doctype();
        echo $this->renderer->header();
        echo $this->renderer->render_pages($this->pageName, $this->pageToRender);
        echo $this->renderer->footer();
    }

    private function manageAccess(): void
    {
        if (!$this->canSeePage()) {
            throw new RuntimeException('You do not have access to this resource.', 403);
        }

        if (!$this->permitGuestUser && isguestuser()) {
            throw new require_login_exception('Guests are not allowed here.');
        }

        if ($this->requireAdmin) {
            require_admin();
        }
    }

    public function requireAdmin(): void
    {
        $this->requireAdmin = true;
    }

    public function permitGuestUser(): void
    {
        if(!$this->requireAdmin) {
            $this->permitGuestUser = true;
        }
    }

    /**
     * Set page renderer
     * @return void
     */
    abstract protected function setPageToRender(): void;

    /**
     * Set page name
     * @return void
     */
    abstract protected function setPageName(): void;

    abstract protected function canSeePage(): bool;
}