<?php

namespace Kanboard\Plugin\Bigboard\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Formatter\BoardFormatter;
use Kanboard\Helper\Url;
use Kanboard\Model\UserMetadataModel;

/**
 * Bigboard Controller.
 *
 * @author Thomas Stinner
 *
 * @author2 Pierre Cadeot @ac-reunion.fr
 * + options for selecting projects to display in bigboard view
 * + store selection into db
 * + store collapsed/expanded status of those selected projetcs in db
 */
class Bigboard extends BaseController
{
    /**
     * bigboard options :
     * Display all Boards available and select which must be seen on BigBoard.
     */
    public function select()
    {

        $project_ids = $this->projectPermissionModel->getActiveProjectIds($this->userSession->getId());

        $nb_projects = count($project_ids);
        $this->listProjects($project_ids);
    }

    /**
     * List projects : display and select on options view.
     *
     * @param $project_ids : list of projects ids available for current user
     */
    private function listProjects($project_ids)
    {

        $ProjectList = [];
        foreach ($project_ids as $project_id) {
            $project = $this->projectModel->getByIdWithOwner($project_id);
            $search = $this->helper->projectHeader->getSearchQuery($project);

            $this->userMetadataCacheDecorator->set(UserMetadataModel::KEY_BOARD_COLLAPSED . $project_id, $this->userSession->isBigboardCollapsed());

            $Project['id'] = $project_id;
            $Project['nom'] = $project['name'];
            $Project['is_private'] = $project['is_private'];

            $ProjectList[] = $Project;
        }

        echo $this->template->render('bigboard:board/list', ['projectList' => $ProjectList]);
    }

    /**
     * Store into database ids of selected projects to display on bigboard view.
     */
    public function saveList()
    {
        $user = $this->getUser();
        // clear all selected
        $status = $this->bigboardModel->selectClear($user['id']);
        if (isset($_POST['selection'])) {
            $selection = $_POST["selection"];
            sort($selection);
            // take each project from selection
            foreach ($selection as $selected) {
                $status = $this->bigboardModel->selectTake($selected, $user['id']);
            }
        }
        // if called from bigboard view so refresh it
        if (isset($_POST["boardview"])) {
            return $this->response->redirect($this->helper->url->to('Bigboard', 'index', ['plugin' => 'Bigboard']));
        }

    }

    /**
     * Display a Board which contains multiple projects.
     */
    public function index()
    {
        $user = $this->getUser();
        $project_ids = $this->bigboardModel->selectFindAllProjectsById($user['id']);
        $search = urldecode($this->request->getStringParam('search'));
        $nb_projects = count($project_ids);

        $categories_list = $users_list = $custom_filters_list = array();
        foreach ($project_ids as $project_id) {
            $project_categories = $this->categoryModel->getList($project_id, false);
            if (!empty($project_categories)) { $categories_list = array_unique(array_merge($categories_list, $project_categories));}

            $project_users = $this->projectUserRoleModel->getAssignableUsersList($project_id, false);
            if (!empty($project_users)) { $users_list = array_unique(array_merge($users_list, $project_users));}

            $project_custom_filters_list = $this->customFilterModel->getAll($project_id, $this->userSession->getId());
            if (!empty($project_custom_filters_list)) { $custom_filters_list = array_unique(array_merge($custom_filters_list, $project_custom_filters_list));}
        }

        $this->response->html($this->helper->layout->app('bigboard:board/show', array(
            'values' => array(
                'search' => $search,
            ),
            'user' => $user,
            'custom_filters_list' => isset($custom_filters_list) ? $custom_filters_list : array(),
            'users_list' => isset($users_list) ? $users_list : array(),
            'categories_list' => isset($categories_list) ? $categories_list : array(),
            'title' => t('BigBoard') . ' (' . $nb_projects . ') ',
        )));
        // Draw a header First
        $menu = $this->template->render('bigboard:board/switcher', array(
            'bigboarddisplaymode' => $this->userSession->isBigboardCollapsed(),
        ));
        print('<section class="page">' . $menu . '</section>');
        print('<div align=center style="color:lightgray"><span id="status_update"></span></div>');
        $this->showProjects($project_ids);
    }

    /**
     * Show projects.
     *
     * @param $project_ids list of project ids to show
     *
     * @return bool
     */
    private function showProjects($project_ids)
    {
        print "<div id='bigboard'>";
        $user = $this->getUser();
        $nb = 0;
        foreach ($project_ids as $project_id) {

            if ($this->bigboardModel->selectFind($project_id, $user['id'])) {
                $project = $this->projectModel->getByIdWithOwner($project_id);
                $search = $this->helper->projectHeader->getSearchQuery($project);
                $nb++;
                #print "SEARCH2: $search";

                $this->userMetadataCacheDecorator->set(UserMetadataModel::KEY_BOARD_COLLAPSED . $project_id, $this->userSession->isBigboardCollapsed());

                echo $this->template->render('bigboard:board/view', array(
                    'no_layout' => true,
                    'board_selector' => false,
                    'project' => $project,
                    'title' => $project['name'],
                    'description' => $this->helper->projectHeader->getDescription($project),
                    'board_private_refresh_interval' => $this->configModel->get('board_private_refresh_interval'),
                    'board_highlight_period' => $this->configModel->get('board_highlight_period'),
                    'swimlanes' => $this->taskLexer
                        ->build($search)
                        ->format(BoardFormatter::getInstance($this->container)->withProjectId($project['id'])),
                ));
            }
        }
        if ($nb == 0) {
            print("<div align=center><p>&nbsp;<p>&nbsp;<p>&nbsp;<p></br><span class='alert'><i class='fa fa-info fa-fw js-modal-medium'></i>");
            print(t("no project has been selected yet for multiple view ; you can select some now : "));
            print("<i class='fa fa-cogs fa-fw'></i><a href='?controller=Bigboard&amp;action=select&amp;plugin=Bigboard&amp;boardview=active' class='js-modal-medium' title='options'>" . t("options") . "</a>");
            print("</span></div>");
        }
        print "</div>";
    }

    public function create(array $values = array(), array $errors = array())
    {
        $is_private = isset($values['is_private']) && $values['is_private'] == 1;
        $projects_list = array(0 => t('Do not duplicate anything')) + $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());

        $this->response->html($this->helper->layout->app('project_creation/create', array(
            'values' => $values,
            'errors' => $errors,
            'is_private' => $is_private,
            'projects_list' => $projects_list,
            'title' => $is_private ? t('New private project') : t('New project'),
        )));
    }

    public function collapseAll()
    {
        $this->changeDisplayMode(true);
    }

    public function expandAll()
    {
        $this->changeDisplayMode(false);
    }

    private function changeDisplayMode($mode)
    {
        session_set('bigboardCollapsed', $mode);

        $project_ids = $this->projectPermissionModel->getActiveProjectIds(session_get('user')['id']);

        if ($this->request->isAjax()) {
            $this->showProjects($project_ids);
        } else {
            $this->response->redirect($this->helper->url->to('Bigboard', 'index', array('plugin' => 'Bigboard')));
        }
    }
}
