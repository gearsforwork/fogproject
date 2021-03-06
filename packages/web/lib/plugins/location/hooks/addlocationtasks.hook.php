<?php
class AddLocationTasks extends Hook {
    public $name = 'AddLocationTasks';
    public $description = 'Add Location to Active Tasks';
    public $author = 'Rowlett';
    public $active = true;
    public $node = 'location';
    public function TasksActiveTableHeader($arguments) {
        if (!in_array($this->node,(array)$_SESSION['PluginsInstalled'])) return;
        if ($_REQUEST['node'] != 'task') return;
        $arguments['headerData'][3] = _('Location');
    }
    public function TasksActiveData($arguments) {
        if (!in_array($this->node,(array)$_SESSION['PluginsInstalled'])) return;
        if ($_REQUEST['node'] != 'task') return;
        $arguments['templates'][3] = '${location}';
        $arguments['attributes'][3] = array('class'=>'r');
        foreach ((array)$arguments['data'] AS $i => &$data) {
            $locationID = self::getSubObjectIDs('LocationAssociation',array('hostID'=>$data['host_id']),'locationID');
            $locID = array_shift($locationID);
            $arguments['data'][$i]['location'] = self::getClass('Location',$locID)->get('name');
            unset($data);
        }
    }
}
$AddLocationTasks = new AddLocationTasks();
$HookManager->register('HOST_DATA',array($AddLocationTasks,'TasksActiveTableHeader'));
$HookManager->register('HOST_DATA',array($AddLocationTasks,'TasksActiveData'));
